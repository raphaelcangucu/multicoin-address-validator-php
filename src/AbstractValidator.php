<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator;

use Multicoin\AddressValidator\Contracts\ValidatorInterface;

/**
 * Abstract base class for address validators
 */
abstract class AbstractValidator implements ValidatorInterface
{
    protected const DEFAULT_NETWORK = 'prod';

    /**
     * @var array<string, mixed>
     */
    protected array $config;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Get configuration value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getConfig(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Get network type from options
     *
     * @param array<string, mixed> $options
     * @return string
     */
    protected function getNetworkType(array $options): string
    {
        return $options['networkType'] ?? self::DEFAULT_NETWORK;
    }

    /**
     * Validate basic address format
     *
     * @param string $address
     * @return bool
     */
    protected function isValidFormat(string $address): bool
    {
        return !empty($address) && is_string($address);
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedNetworks(): array
    {
        return ['prod', 'testnet'];
    }
}