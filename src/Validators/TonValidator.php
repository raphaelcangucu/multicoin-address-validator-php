<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Validators;

use Multicoin\AddressValidator\AbstractValidator;

/**
 * TON (The Open Network) address validator
 */
class TonValidator extends AbstractValidator
{
    private const USER_FRIENDLY_MIN_LENGTH = 48;
    private const USER_FRIENDLY_MAX_LENGTH = 50;
    private const RAW_FORMAT_PATTERN = '/^-?[0-1]:[a-fA-F0-9]{64}$/';
    
    // Address flags
    private const FLAG_BOUNCEABLE = 0x11;
    private const FLAG_NON_BOUNCEABLE = 0x51;
    private const FLAG_TEST_ONLY = 0x80;
    
    // CRC16-CCITT lookup table
    private const CRC16_TABLE = [
        0x0000, 0x1021, 0x2042, 0x3063, 0x4084, 0x50a5, 0x60c6, 0x70e7,
        0x8108, 0x9129, 0xa14a, 0xb16b, 0xc18c, 0xd1ad, 0xe1ce, 0xf1ef,
        0x1231, 0x0210, 0x3273, 0x2252, 0x52b5, 0x4294, 0x72f7, 0x62d6,
        0x9339, 0x8318, 0xb37b, 0xa35a, 0xd3bd, 0xc39c, 0xf3ff, 0xe3de,
        0x2462, 0x3443, 0x0420, 0x1401, 0x64e6, 0x74c7, 0x44a4, 0x5485,
        0xa56a, 0xb54b, 0x8528, 0x9509, 0xe5ee, 0xf5cf, 0xc5ac, 0xd58d,
        0x3653, 0x2672, 0x1611, 0x0630, 0x76d7, 0x66f6, 0x5695, 0x46b4,
        0xb75b, 0xa77a, 0x9719, 0x8738, 0xf7df, 0xe7fe, 0xd79d, 0xc7bc,
        0x48c4, 0x58e5, 0x6886, 0x78a7, 0x0840, 0x1861, 0x2802, 0x3823,
        0xc9cc, 0xd9ed, 0xe98e, 0xf9af, 0x8948, 0x9969, 0xa90a, 0xb92b,
        0x5af5, 0x4ad4, 0x7ab7, 0x6a96, 0x1a71, 0x0a50, 0x3a33, 0x2a12,
        0xdbfd, 0xcbdc, 0xfbbf, 0xeb9e, 0x9b79, 0x8b58, 0xbb3b, 0xab1a,
        0x6ca6, 0x7c87, 0x4ce4, 0x5cc5, 0x2c22, 0x3c03, 0x0c60, 0x1c41,
        0xedae, 0xfd8f, 0xcdec, 0xddcd, 0xad2a, 0xbd0b, 0x8d68, 0x9d49,
        0x7e97, 0x6eb6, 0x5ed5, 0x4ef4, 0x3e13, 0x2e32, 0x1e51, 0x0e70,
        0xff9f, 0xefbe, 0xdfdd, 0xcffc, 0xbf1b, 0xaf3a, 0x9f59, 0x8f78,
        0x9188, 0x81a9, 0xb1ca, 0xa1eb, 0xd10c, 0xc12d, 0xf14e, 0xe16f,
        0x1080, 0x00a1, 0x30c2, 0x20e3, 0x5004, 0x4025, 0x7046, 0x6067,
        0x83b9, 0x9398, 0xa3fb, 0xb3da, 0xc33d, 0xd31c, 0xe37f, 0xf35e,
        0x02b1, 0x1290, 0x22f3, 0x32d2, 0x4235, 0x5214, 0x6277, 0x7256,
        0xb5ea, 0xa5cb, 0x95a8, 0x8589, 0xf56e, 0xe54f, 0xd52c, 0xc50d,
        0x34e2, 0x24c3, 0x14a0, 0x0481, 0x7466, 0x6447, 0x5424, 0x4405,
        0xa7db, 0xb7fa, 0x8799, 0x97b8, 0xe75f, 0xf77e, 0xc71d, 0xd73c,
        0x26d3, 0x36f2, 0x0691, 0x16b0, 0x6657, 0x7676, 0x4615, 0x5634,
        0xd94c, 0xc96d, 0xf90e, 0xe92f, 0x99c8, 0x89e9, 0xb98a, 0xa9ab,
        0x5844, 0x4865, 0x7806, 0x6827, 0x18c0, 0x08e1, 0x3882, 0x28a3,
        0xcb7d, 0xdb5c, 0xeb3f, 0xfb1e, 0x8bf9, 0x9bd8, 0xabbb, 0xbb9a,
        0x4a75, 0x5a54, 0x6a37, 0x7a16, 0x0af1, 0x1ad0, 0x2ab3, 0x3a92,
        0xfd2e, 0xed0f, 0xdd6c, 0xcd4d, 0xbdaa, 0xad8b, 0x9de8, 0x8dc9,
        0x7c26, 0x6c07, 0x5c64, 0x4c45, 0x3ca2, 0x2c83, 0x1ce0, 0x0cc1,
        0xef1f, 0xff3e, 0xcf5d, 0xdf7c, 0xaf9b, 0xbfba, 0x8fd9, 0x9ff8,
        0x6e17, 0x7e36, 0x4e55, 0x5e74, 0x2e93, 0x3eb2, 0x0ed1, 0x1ef0
    ];

    /**
     * {@inheritdoc}
     */
    public function isValidAddress(string $address, array $options = []): bool
    {
        if (!$this->isValidFormat($address)) {
            return false;
        }

        // Extract core address from query parameters (e.g., ?memoId=123)
        $coreAddress = $this->extractCoreAddress($address);

        // Check if it's raw format (workchain:address)
        if ($this->isRawFormat($coreAddress)) {
            return $this->validateRawAddress($coreAddress);
        }

        // Check if it's user-friendly format
        return $this->validateUserFriendlyAddress($coreAddress, $options);
    }

    /**
     * Extract core address from full address with potential memo parameters
     */
    private function extractCoreAddress(string $address): string
    {
        // Split by '?' to separate address from query parameters (e.g., ?memoId=123)
        $parts = explode('?', $address, 2);
        return $parts[0];
    }

    /**
     * Check if address is in raw format
     */
    private function isRawFormat(string $address): bool
    {
        return preg_match(self::RAW_FORMAT_PATTERN, $address) === 1;
    }

    /**
     * Validate raw format address
     */
    private function validateRawAddress(string $address): bool
    {
        if (!preg_match(self::RAW_FORMAT_PATTERN, $address, $matches)) {
            return false;
        }

        $parts = explode(':', $address);
        if (count($parts) !== 2) {
            return false;
        }

        $workchain = (int) $parts[0];
        $accountId = $parts[1];

        // Currently only workchain -1 (masterchain) and 0 (basechain) are supported
        if ($workchain !== -1 && $workchain !== 0) {
            return false;
        }

        // Account ID should be exactly 64 hex characters (256 bits)
        if (strlen($accountId) !== 64 || !ctype_xdigit($accountId)) {
            return false;
        }

        return true;
    }

    /**
     * Validate user-friendly format address
     */
    private function validateUserFriendlyAddress(string $address, array $options): bool
    {
        // Check length first (48-50 characters for user-friendly addresses)
        $length = strlen($address);
        if ($length < self::USER_FRIENDLY_MIN_LENGTH || $length > self::USER_FRIENDLY_MAX_LENGTH) {
            return false;
        }

        // Check if it's valid base64url format (allow = padding at the end)
        if (!preg_match('/^[A-Za-z0-9_-]+=*$/', $address)) {
            return false;
        }

        // Convert base64url to base64
        $base64Address = str_replace(['-', '_'], ['+', '/'], $address);
        
        // Remove any existing padding first
        $base64Address = rtrim($base64Address, '=');
        
        // Add correct padding
        $paddingNeeded = 4 - (strlen($base64Address) % 4);
        if ($paddingNeeded < 4) {
            $base64Address .= str_repeat('=', $paddingNeeded);
        }

        $decoded = base64_decode($base64Address, true);
        if ($decoded === false || strlen($decoded) !== 36) {
            return false;
        }

        // Extract components
        $tag = ord($decoded[0]);
        $workchain = ord($decoded[1]);
        $accountHash = substr($decoded, 2, 32);
        $crc = substr($decoded, 34, 2);

        // Validate tag
        if (!$this->isValidTag($tag)) {
            return false;
        }

        // Validate workchain (convert unsigned byte to signed)
        if ($workchain > 127) {
            $workchain -= 256;
        }
        if ($workchain !== -1 && $workchain !== 0) {
            return false;
        }

        // Check testnet flag if needed
        $networkType = $this->getNetworkType($options);
        $isTestnet = ($tag & self::FLAG_TEST_ONLY) !== 0;
        
        if ($networkType === 'prod' && $isTestnet) {
            return false;
        }
        if ($networkType === 'testnet' && !$isTestnet) {
            return false;
        }

        // Validate CRC16
        $payload = substr($decoded, 0, 34);
        $expectedCrc = $this->calculateCrc16($payload);
        $actualCrc = unpack('n', $crc)[1];

        return $expectedCrc === $actualCrc;
    }

    /**
     * Check if string is valid base64
     */
    private function isValidBase64(string $string): bool
    {
        $decoded = base64_decode($string, true);
        if ($decoded === false) {
            return false;
        }
        return base64_encode($decoded) === $string;
    }

    /**
     * Check if tag is valid
     */
    private function isValidTag(int $tag): bool
    {
        $baseTag = $tag & ~self::FLAG_TEST_ONLY;
        return $baseTag === self::FLAG_BOUNCEABLE || $baseTag === self::FLAG_NON_BOUNCEABLE;
    }

    /**
     * Calculate CRC16-CCITT checksum
     */
    private function calculateCrc16(string $data): int
    {
        $crc = 0;
        for ($i = 0; $i < strlen($data); $i++) {
            $byte = ord($data[$i]);
            $crc = (($crc << 8) ^ self::CRC16_TABLE[(($crc >> 8) ^ $byte) & 0xFF]) & 0xFFFF;
        }
        return $crc;
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedNetworks(): array
    {
        return ['prod', 'testnet'];
    }
}