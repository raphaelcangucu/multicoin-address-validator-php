<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Contracts;

/**
 * Interface for currency configuration
 */
interface CurrencyInterface
{
    /**
     * Get the currency name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the currency symbol
     *
     * @return string
     */
    public function getSymbol(): string;

    /**
     * Get the validator instance for this currency
     *
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface;

    /**
     * Get currency-specific configuration
     *
     * @return array<string, mixed>
     */
    public function getConfig(): array;
}