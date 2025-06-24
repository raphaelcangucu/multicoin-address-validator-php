<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator;

use Multicoin\AddressValidator\Contracts\CurrencyInterface;
use Multicoin\AddressValidator\Contracts\CurrencyRegistryInterface;

/**
 * Currency registry implementation
 */
class CurrencyRegistry implements CurrencyRegistryInterface
{
    /**
     * @var array<string, CurrencyInterface>
     */
    private array $currencies = [];

    /**
     * {@inheritdoc}
     */
    public function register(CurrencyInterface $currency): void
    {
        $name = strtolower($currency->getName());
        $symbol = strtolower($currency->getSymbol());
        
        $this->currencies[$name] = $currency;
        $this->currencies[$symbol] = $currency;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $nameOrSymbol): ?CurrencyInterface
    {
        $key = strtolower($nameOrSymbol);
        return $this->currencies[$key] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        $unique = [];
        $seen = [];
        
        foreach ($this->currencies as $currency) {
            $id = $currency->getName() . '|' . $currency->getSymbol();
            if (!isset($seen[$id])) {
                $unique[] = $currency;
                $seen[$id] = true;
            }
        }
        
        return $unique;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $nameOrSymbol): bool
    {
        $key = strtolower($nameOrSymbol);
        return isset($this->currencies[$key]);
    }
}