<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Utils;

/**
 * Simple and robust Base58 implementation using native PHP with bcmath
 */
class Base58
{
    private const ALPHABET = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
    private const BASE = '58';

    /**
     * Decode a Base58 encoded string
     *
     * @param string $input
     * @return string|null Binary data or null if invalid
     */
    public static function decode(string $input): ?string
    {
        if (empty($input)) {
            return null;
        }

        // Count leading '1's (represent leading zeros)
        $leadingZeros = 0;
        for ($i = 0; $i < strlen($input) && $input[$i] === '1'; $i++) {
            $leadingZeros++;
        }

        // Convert from base58 to decimal using bcmath
        $decimal = '0';
        for ($i = 0; $i < strlen($input); $i++) {
            $char = $input[$i];
            $charIndex = strpos(self::ALPHABET, $char);
            
            if ($charIndex === false) {
                return null; // Invalid character
            }

            $decimal = bcadd(bcmul($decimal, self::BASE), (string)$charIndex);
        }

        // Convert decimal to binary
        $binary = '';
        while (bccomp($decimal, '0') > 0) {
            $remainder = bcmod($decimal, '256');
            $binary = chr((int)$remainder) . $binary;
            $decimal = bcdiv($decimal, '256');
        }

        // Add leading zeros
        $result = str_repeat("\x00", $leadingZeros) . $binary;

        return $result;
    }

    /**
     * Encode binary data to Base58
     *
     * @param string $input Binary data
     * @return string Base58 encoded string
     */
    public static function encode(string $input): string
    {
        if (empty($input)) {
            return '';
        }

        // Count leading zeros
        $leadingZeros = 0;
        for ($i = 0; $i < strlen($input) && $input[$i] === "\x00"; $i++) {
            $leadingZeros++;
        }

        // Convert binary to decimal
        $decimal = '0';
        for ($i = 0; $i < strlen($input); $i++) {
            $decimal = bcadd(bcmul($decimal, '256'), (string)ord($input[$i]));
        }

        // Convert decimal to base58
        $result = '';
        while (bccomp($decimal, '0') > 0) {
            $remainder = bcmod($decimal, self::BASE);
            $result = self::ALPHABET[(int)$remainder] . $result;
            $decimal = bcdiv($decimal, self::BASE);
        }

        // Add leading '1's for leading zeros
        $result = str_repeat('1', $leadingZeros) . $result;

        return $result;
    }

    /**
     * Validate if string is valid Base58
     *
     * @param string $input
     * @return bool
     */
    public static function isValid(string $input): bool
    {
        if (empty($input)) {
            return false;
        }

        for ($i = 0; $i < strlen($input); $i++) {
            if (strpos(self::ALPHABET, $input[$i]) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate Bitcoin address checksum
     *
     * @param string $address
     * @return bool
     */
    public static function validateBitcoinChecksum(string $address): bool
    {
        $decoded = self::decode($address);
        if ($decoded === null || strlen($decoded) !== 25) {
            return false;
        }

        $payload = substr($decoded, 0, 21);
        $checksum = substr($decoded, 21, 4);

        $hash = hash('sha256', $payload, true);
        $hash = hash('sha256', $hash, true);
        $expectedChecksum = substr($hash, 0, 4);

        return $checksum === $expectedChecksum;
    }
}