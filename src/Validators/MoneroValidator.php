<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Validators;

use Multicoin\AddressValidator\AbstractValidator;
use Multicoin\AddressValidator\Utils\Base58;
use Multicoin\AddressValidator\Utils\CryptoUtils;

/**
 * Monero (XMR) address validator
 */
class MoneroValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function isValidAddress(string $address, array $options = []): bool
    {
        if (!$this->isValidFormat($address)) {
            return false;
        }

        $decoded = Base58::decode($address);
        if ($decoded === null) {
            return false;
        }

        // Monero addresses are 69 bytes when decoded
        if (strlen($decoded) !== 69) {
            return false;
        }

        // Extract components
        $networkByte = ord($decoded[0]);
        $publicSpendKey = substr($decoded, 1, 32);
        $publicViewKey = substr($decoded, 33, 32);
        $checksum = substr($decoded, 65, 4);

        // Validate network byte
        if (!$this->isValidNetworkByte($networkByte, $options)) {
            return false;
        }

        // Calculate and verify checksum
        $payload = substr($decoded, 0, 65);
        $expectedChecksum = $this->calculateKeccakChecksum($payload);

        return $checksum === $expectedChecksum;
    }

    /**
     * Validate network byte
     *
     * @param int $networkByte
     * @param array<string, mixed> $options
     * @return bool
     */
    private function isValidNetworkByte(int $networkByte, array $options): bool
    {
        $networkType = $this->getNetworkType($options);
        
        $addressTypes = $this->getConfig('addressTypes', []);
        $iAddressTypes = $this->getConfig('iAddressTypes', []);
        
        $validTypes = [];
        
        if (isset($addressTypes[$networkType])) {
            $validTypes = array_merge($validTypes, $addressTypes[$networkType]);
        }
        
        if (isset($iAddressTypes[$networkType])) {
            $validTypes = array_merge($validTypes, $iAddressTypes[$networkType]);
        }

        // Convert hex strings to integers
        $validTypeInts = array_map('hexdec', $validTypes);
        
        return in_array($networkByte, $validTypeInts, true);
    }

    /**
     * Calculate Keccak checksum for Monero
     *
     * @param string $payload
     * @return string
     */
    private function calculateKeccakChecksum(string $payload): string
    {
        $hash = hash('sha3-256', $payload, true);
        return substr($hash, 0, 4);
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedNetworks(): array
    {
        return ['prod', 'testnet', 'stagenet'];
    }
}