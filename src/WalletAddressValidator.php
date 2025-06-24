<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator;

use Multicoin\AddressValidator\Contracts\CurrencyRegistryInterface;
use Multicoin\AddressValidator\Exceptions\UnsupportedCurrencyException;

/**
 * Main wallet address validator class
 */
class WalletAddressValidator
{
    private const DEFAULT_CURRENCY = 'bitcoin';

    public function __construct(
        private readonly CurrencyRegistryInterface $currencyRegistry
    ) {
    }

    /**
     * Validate a wallet address
     *
     * @param string $address The address to validate
     * @param string|null $currencyNameOrSymbol Currency name or symbol (default: bitcoin)
     * @param array<string, mixed> $options Validation options
     * @return bool True if the address is valid, false otherwise
     * @throws UnsupportedCurrencyException If the currency is not supported
     */
    public function validate(
        string $address,
        ?string $currencyNameOrSymbol = null,
        array $options = []
    ): bool {
        $currencyNameOrSymbol = $currencyNameOrSymbol ?? self::DEFAULT_CURRENCY;
        $currency = $this->currencyRegistry->get($currencyNameOrSymbol);

        if ($currency === null) {
            throw new UnsupportedCurrencyException(
                "Unsupported currency: {$currencyNameOrSymbol}"
            );
        }

        $validator = $currency->getValidator();
        $config = $currency->getConfig();
        
        // Merge currency config with validation options
        $validationOptions = array_merge($config, $options);

        return $validator->isValidAddress($address, $validationOptions);
    }

    /**
     * Get all supported currencies
     *
     * @return array<array{name: string, symbol: string}> Array of currency info
     */
    public function getCurrencies(): array
    {
        $currencies = [];
        foreach ($this->currencyRegistry->getAll() as $currency) {
            $currencies[] = [
                'name' => $currency->getName(),
                'symbol' => $currency->getSymbol(),
            ];
        }

        return $currencies;
    }

    /**
     * Find a currency by name or symbol
     *
     * @param string $nameOrSymbol
     * @return array{name: string, symbol: string}|null
     */
    public function findCurrency(string $nameOrSymbol): ?array
    {
        $currency = $this->currencyRegistry->get($nameOrSymbol);
        
        if ($currency === null) {
            return null;
        }

        return [
            'name' => $currency->getName(),
            'symbol' => $currency->getSymbol(),
        ];
    }

    /**
     * Check if a currency is supported
     *
     * @param string $nameOrSymbol
     * @return bool
     */
    public function isSupported(string $nameOrSymbol): bool
    {
        return $this->currencyRegistry->has($nameOrSymbol);
    }
}