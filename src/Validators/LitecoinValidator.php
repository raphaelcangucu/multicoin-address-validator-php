<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Validators;

/**
 * Litecoin address validator
 * Uses Bitcoin validator with Litecoin-specific configuration
 */
class LitecoinValidator extends BitcoinValidator
{
    /**
     * {@inheritdoc}
     */
    public function getSupportedNetworks(): array
    {
        return ['prod', 'testnet'];
    }
}