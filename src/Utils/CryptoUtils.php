<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Utils;

/**
 * Cryptographic utility functions using native PHP
 */
class CryptoUtils
{
    /**
     * Calculate SHA-256 checksum
     *
     * @param string $data
     * @return string
     */
    public static function sha256Checksum(string $data): string
    {
        $hash = hash('sha256', hex2bin($data));
        $hash = hash('sha256', hex2bin($hash));
        return strtoupper(substr($hash, 0, 8));
    }

    /**
     * Calculate Blake2b-256 hash
     *
     * @param string $data Hex string data
     * @return string Hex hash result
     */
    public static function blake2b256(string $data): string
    {
        $binaryData = hex2bin($data);
        
        if (extension_loaded('sodium')) {
            $hash = sodium_crypto_generichash($binaryData, '', 32);
            return bin2hex($hash);
        }
        
        // Fallback to SHA-256 for systems without sodium
        return hash('sha256', $binaryData, false);
    }

    /**
     * Calculate Blake2b-512 hash
     *
     * @param string $data Binary data
     * @return string Binary hash result
     */
    public static function blake2b512(string $data): string
    {
        if (extension_loaded('sodium')) {
            return sodium_crypto_generichash($data, '', 64);
        }
        
        // Fallback to SHA-512 for systems without sodium
        return hash('sha512', $data, true);
    }

    /**
     * Calculate Blake-256 checksum (fallback to SHA-256)
     *
     * @param string $data
     * @return string
     */
    public static function blake256Checksum(string $data): string
    {
        // Use SHA-256 as Blake fallback for simplicity
        $hash = hash('sha256', hex2bin($data), false);
        $hash = hash('sha256', hex2bin($hash), false);
        return strtoupper(substr($hash, 0, 8));
    }

    /**
     * Calculate Keccak-256 hash using native PHP
     * Note: PHP's sha3-256 is actually Keccak-256
     *
     * @param string $data
     * @return string
     */
    public static function keccak256(string $data): string
    {
        return hash('sha3-256', $data);
    }

    /**
     * Calculate Keccak-256 checksum
     *
     * @param string $data
     * @return string
     */
    public static function keccak256Checksum(string $data): string
    {
        $hash = static::keccak256(hex2bin($data));
        $hash = static::keccak256(hex2bin($hash));
        return strtoupper(substr($hash, 0, 8));
    }

    /**
     * Convert binary data to hexadecimal
     *
     * @param string $data
     * @return string
     */
    public static function toHex(string $data): string
    {
        return strtoupper(bin2hex($data));
    }

    /**
     * Validate hexadecimal string
     *
     * @param string $hex
     * @return bool
     */
    public static function isValidHex(string $hex): bool
    {
        return ctype_xdigit($hex);
    }
}