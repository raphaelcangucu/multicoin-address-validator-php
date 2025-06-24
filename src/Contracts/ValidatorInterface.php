<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Contracts;

/**
 * Interface for cryptocurrency address validators
 */
interface ValidatorInterface
{
    /**
     * Validate a cryptocurrency address
     *
     * @param string $address The address to validate
     * @param array<string, mixed> $options Additional validation options
     * @return bool True if the address is valid, false otherwise
     */
    public function isValidAddress(string $address, array $options = []): bool;

    /**
     * Get the supported network types for this validator
     *
     * @return array<string> Array of supported network types (e.g., ['prod', 'testnet'])
     */
    public function getSupportedNetworks(): array;
}