<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Validators;

use Multicoin\AddressValidator\AbstractValidator;
use Multicoin\AddressValidator\Utils\Base58;
use Multicoin\AddressValidator\Utils\CryptoUtils;

/**
 * Polkadot (DOT) address validator
 */
class PolkadotValidator extends AbstractValidator
{
    private const SS58_PREFIX = [
        0,  // Polkadot
        2,  // Kusama
        42, // Generic Substrate
    ];

    /**
     * {@inheritdoc}
     */
    public function isValidAddress(string $address, array $options = []): bool
    {
        if (!$this->isValidFormat($address)) {
            return false;
        }

        // Polkadot addresses are typically 47-48 characters
        if (strlen($address) < 47 || strlen($address) > 48) {
            return false;
        }

        // Check if it's valid Base58
        if (!Base58::isValid($address)) {
            return false;
        }

        $decoded = Base58::decode($address);
        if ($decoded === null) {
            return false;
        }

        // Polkadot addresses are 35 bytes when decoded (1 + 32 + 2)
        if (strlen($decoded) !== 35) {
            return false;
        }

        // Check SS58 prefix
        $prefix = ord($decoded[0]);
        if (!in_array($prefix, self::SS58_PREFIX, true)) {
            return false;
        }

        // Verify checksum (simplified - real implementation would use Blake2b)
        return $this->verifyChecksum($decoded);
    }

    /**
     * Verify SS58 checksum using Blake2b-512 hash
     *
     * @param string $decoded The decoded address bytes
     * @return bool
     */
    private function verifyChecksum(string $decoded): bool
    {
        // SS58 addresses should be exactly 35 bytes (1 + 32 + 2)
        if (strlen($decoded) !== 35) {
            return false;
        }
        
        // Extract components
        $payload = substr($decoded, 0, 33); // address type + account ID
        $providedChecksum = substr($decoded, 33, 2); // last 2 bytes
        
        // Verify basic structure
        if (strlen($payload) !== 33 || strlen($providedChecksum) !== 2) {
            return false;
        }
        
        // Check that the public key part (after version byte) is not all zeros
        $publicKey = substr($payload, 1, 32);
        if ($publicKey === str_repeat("\x00", 32)) {
            return false;
        }
        
        // Calculate the expected checksum using Blake2b-512
        // SS58 checksum formula: Blake2b512("SS58PRE" + payload)[0..1]
        $ss58Prefix = 'SS58PRE';
        $hashInput = $ss58Prefix . $payload;
        $hash = CryptoUtils::blake2b512($hashInput);
        $expectedChecksum = substr($hash, 0, 2);
        
        // Compare the checksums
        return hash_equals($providedChecksum, $expectedChecksum);
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedNetworks(): array
    {
        return ['polkadot', 'kusama', 'substrate'];
    }
}