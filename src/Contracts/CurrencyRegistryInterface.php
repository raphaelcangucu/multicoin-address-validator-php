<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Contracts;

/**
 * Interface for currency registry
 */
interface CurrencyRegistryInterface
{
    /**
     * Register a currency
     *
     * @param CurrencyInterface $currency
     * @return void
     */
    public function register(CurrencyInterface $currency): void;

    /**
     * Get a currency by name or symbol
     *
     * @param string $nameOrSymbol
     * @return CurrencyInterface|null
     */
    public function get(string $nameOrSymbol): ?CurrencyInterface;

    /**
     * Get all registered currencies
     *
     * @return array<CurrencyInterface>
     */
    public function getAll(): array;

    /**
     * Check if a currency is registered
     *
     * @param string $nameOrSymbol
     * @return bool
     */
    public function has(string $nameOrSymbol): bool;
}