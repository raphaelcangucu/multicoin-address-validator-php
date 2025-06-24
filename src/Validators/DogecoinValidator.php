<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Validators;

/**
 * Dogecoin address validator
 * Uses Bitcoin validator with Dogecoin-specific configuration
 */
class DogecoinValidator extends BitcoinValidator
{
    /**
     * {@inheritdoc}
     */
    public function getSupportedNetworks(): array
    {
        return ['prod', 'testnet'];
    }
}