<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator;

use Multicoin\AddressValidator\Contracts\CurrencyInterface;
use Multicoin\AddressValidator\Contracts\ValidatorInterface;

/**
 * Currency configuration implementation
 */
class Currency implements CurrencyInterface
{
    /**
     * @param string $name
     * @param string $symbol
     * @param ValidatorInterface $validator
     * @param array<string, mixed> $config
     */
    public function __construct(
        private readonly string $name,
        private readonly string $symbol,
        private readonly ValidatorInterface $validator,
        private readonly array $config = []
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}