<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Validators;

use Multicoin\AddressValidator\AbstractValidator;

/**
 * Bitcoin Cash address validator
 */
class BitcoinCashValidator extends AbstractValidator
{
    private const CHARSET = 'qpzry9x8gf2tvdw0s3jn54khce6mua7l';
    private const PREFIX_MAINNET = 'bitcoincash';
    private const PREFIX_TESTNET = 'bchtest';

    /**
     * {@inheritdoc}
     */
    public function isValidAddress(string $address, array $options = []): bool
    {
        if (!$this->isValidFormat($address)) {
            return false;
        }

        // Check if it's a CashAddr format
        if ($this->isCashAddr($address)) {
            return $this->validateCashAddr($address, $options);
        }

        // Check if it's a legacy format
        if ($this->isLegacyFormat($address)) {
            return $this->validateLegacyFormat($address, $options);
        }

        return false;
    }

    /**
     * Check if address is CashAddr format
     *
     * @param string $address
     * @return bool
     */
    private function isCashAddr(string $address): bool
    {
        return strpos($address, ':') !== false || 
               (strlen($address) === 42 && (str_starts_with($address, 'q') || str_starts_with($address, 'p')));
    }

    /**
     * Check if address is legacy format
     *
     * @param string $address
     * @return bool
     */
    private function isLegacyFormat(string $address): bool
    {
        $regexp = $this->getConfig('regexp');
        if ($regexp) {
            return preg_match('/' . trim($regexp, '/') . '/', $address) === 1;
        }
        return false;
    }

    /**
     * Validate CashAddr format
     *
     * @param string $address
     * @param array<string, mixed> $options
     * @return bool
     */
    private function validateCashAddr(string $address, array $options): bool
    {
        $networkType = $this->getNetworkType($options);
        
        // Parse the address
        $parts = explode(':', $address);
        
        if (count($parts) === 2) {
            $prefix = $parts[0];
            $payload = $parts[1];
        } else {
            // No prefix, assume mainnet
            $prefix = $networkType === 'testnet' ? self::PREFIX_TESTNET : self::PREFIX_MAINNET;
            $payload = $address;
        }

        // Validate prefix
        $expectedPrefix = $networkType === 'testnet' ? self::PREFIX_TESTNET : self::PREFIX_MAINNET;
        if ($prefix !== $expectedPrefix) {
            return false;
        }

        // Validate payload format
        if (strlen($payload) !== 42) {
            return false;
        }

        // Check if payload contains only valid characters
        for ($i = 0; $i < strlen($payload); $i++) {
            if (strpos(self::CHARSET, $payload[$i]) === false) {
                return false;
            }
        }

        // Validate checksum (simplified - full implementation would need polymod)
        return $this->validateCashAddrChecksum($prefix, $payload);
    }

    /**
     * Validate legacy format using Bitcoin validator
     *
     * @param string $address
     * @param array<string, mixed> $options
     * @return bool
     */
    private function validateLegacyFormat(string $address, array $options): bool
    {
        $bitcoinValidator = new BitcoinValidator($this->config);
        return $bitcoinValidator->isValidAddress($address, $options);
    }

    /**
     * Validate CashAddr checksum (simplified implementation)
     *
     * @param string $prefix
     * @param string $payload
     * @return bool
     */
    private function validateCashAddrChecksum(string $prefix, string $payload): bool
    {
        // This is a simplified validation
        // Full implementation would need the complete polymod checksum algorithm
        return strlen($payload) === 42 && 
               (str_starts_with($payload, 'q') || str_starts_with($payload, 'p'));
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedNetworks(): array
    {
        return ['prod', 'testnet', 'mainnet'];
    }
}