<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Utils;

/**
 * Simplified Bech32 validation for Segwit addresses
 */
class Bech32
{
    private const CHARSET = 'qpzry9x8gf2tvdw0s3jn54khce6mua7l';

    /**
     * Simple Bech32 format validation
     *
     * @param string $bech32
     * @return bool
     */
    public static function isValidFormat(string $bech32): bool
    {
        $bech32 = strtolower($bech32);
        
        // Basic format checks
        if (strlen($bech32) < 8 || strlen($bech32) > 90) {
            return false;
        }

        $pos = strrpos($bech32, '1');
        if ($pos === false || $pos === 0 || $pos + 7 > strlen($bech32)) {
            return false;
        }

        $hrp = substr($bech32, 0, $pos);
        $data = substr($bech32, $pos + 1);

        // Validate HRP
        if (!self::isValidHrp($hrp)) {
            return false;
        }

        // Validate data part contains only valid characters
        for ($i = 0; $i < strlen($data); $i++) {
            if (strpos(self::CHARSET, $data[$i]) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Extract HRP and data from Bech32 string
     *
     * @param string $bech32
     * @return array{hrp: string, data: string}|null
     */
    public static function extractParts(string $bech32): ?array
    {
        if (!self::isValidFormat($bech32)) {
            return null;
        }

        $bech32 = strtolower($bech32);
        $pos = strrpos($bech32, '1');
        
        return [
            'hrp' => substr($bech32, 0, $pos),
            'data' => substr($bech32, $pos + 1)
        ];
    }

    /**
     * Validate Segwit address format with proper Bech32 checksum
     *
     * @param string $address
     * @param array<string> $validHrps
     * @return bool
     */
    public static function isValidSegwitAddress(string $address, array $validHrps = []): bool
    {
        // Convert to lowercase for case-insensitive validation (like JS implementation)
        $address = strtolower($address);
        
        $parts = self::extractParts($address);
        if ($parts === null) {
            return false;
        }

        // Check HRP if provided
        if (!empty($validHrps) && !in_array($parts['hrp'], $validHrps, true)) {
            return false;
        }

        // Basic length validation for data part
        $dataLength = strlen($parts['data']);
        if ($dataLength < 6) { // Minimum: 1 witness version + 1 program byte + 4 checksum
            return false;
        }

        // Check witness version
        if (!self::isValidWitnessVersion($parts['data'])) {
            return false;
        }

        // For now, be permissive with program lengths to support various Segwit versions
        // This allows for v0, v1 (Taproot), and future versions
        
        // Validate Bech32 checksum
        return self::verifyChecksum($parts['hrp'], $parts['data']);
    }

    /**
     * Check if HRP is valid
     *
     * @param string $hrp
     * @return bool
     */
    private static function isValidHrp(string $hrp): bool
    {
        if (strlen($hrp) < 1 || strlen($hrp) > 83) {
            return false;
        }

        for ($i = 0; $i < strlen($hrp); $i++) {
            $ord = ord($hrp[$i]);
            if ($ord < 33 || $ord > 126) {
                return false;
            }
        }

        return true;
    }

    /**
     * Simple witness version validation
     *
     * @param string $data
     * @return bool
     */
    public static function isValidWitnessVersion(string $data): bool
    {
        if (empty($data)) {
            return false;
        }

        $firstChar = $data[0];
        $witnessVersion = strpos(self::CHARSET, $firstChar);
        
        // Witness version should be 0-16
        return $witnessVersion !== false && $witnessVersion <= 16;
    }

    /**
     * Verify Bech32 checksum using polymod algorithm
     *
     * @param string $hrp
     * @param string $data
     * @return bool
     */
    private static function verifyChecksum(string $hrp, string $data): bool
    {
        // Convert data to array of integers
        $dataValues = [];
        for ($i = 0; $i < strlen($data); $i++) {
            $value = strpos(self::CHARSET, $data[$i]);
            if ($value === false) {
                return false;
            }
            $dataValues[] = $value;
        }

        // Expand HRP
        $hrpExpanded = [];
        for ($i = 0; $i < strlen($hrp); $i++) {
            $hrpExpanded[] = ord($hrp[$i]) >> 5;
        }
        $hrpExpanded[] = 0;
        for ($i = 0; $i < strlen($hrp); $i++) {
            $hrpExpanded[] = ord($hrp[$i]) & 31;
        }

        // Combine HRP and data
        $values = array_merge($hrpExpanded, $dataValues);

        // Calculate polymod
        return self::polymod($values) === 1;
    }

    /**
     * Bech32 polymod function
     *
     * @param array<int> $values
     * @return int
     */
    private static function polymod(array $values): int
    {
        $generator = [0x3b6a57b2, 0x26508e6d, 0x1ea119fa, 0x3d4233dd, 0x2a1462b3];
        $chk = 1;
        
        foreach ($values as $value) {
            $top = $chk >> 25;
            $chk = ($chk & 0x1ffffff) << 5 ^ $value;
            for ($i = 0; $i < 5; $i++) {
                $chk ^= (($top >> $i) & 1) ? $generator[$i] : 0;
            }
        }
        
        return $chk;
    }
}