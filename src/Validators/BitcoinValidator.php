<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Validators;

use Multicoin\AddressValidator\AbstractValidator;
use Multicoin\AddressValidator\Utils\Base58;
use Multicoin\AddressValidator\Utils\Bech32;
use Multicoin\AddressValidator\Utils\CryptoUtils;

/**
 * Bitcoin address validator
 */
class BitcoinValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function isValidAddress(string $address, array $options = []): bool
    {
        if (!$this->isValidFormat($address)) {
            return false;
        }

        return $this->isValidP2PKHOrP2SH($address, $options) || 
               $this->isValidSegwit($address, $options);
    }

    /**
     * Validate P2PKH and P2SH addresses
     *
     * @param string $address
     * @param array<string, mixed> $options
     * @return bool
     */
    private function isValidP2PKHOrP2SH(string $address, array $options): bool
    {
        $networkType = $this->getNetworkType($options);
        $addressTypes = $this->getAddressTypes($networkType);
        
        if (empty($addressTypes)) {
            return false;
        }

        $addressType = $this->getAddressType($address, $options);
        
        return $addressType !== null && in_array($addressType, $addressTypes, true);
    }

    /**
     * Validate Segwit (Bech32) addresses
     *
     * @param string $address
     * @param array<string, mixed> $options
     * @return bool
     */
    private function isValidSegwit(string $address, array $options): bool
    {
        $networkType = $this->getNetworkType($options);
        $bech32Hrp = $this->getBech32Hrp($networkType);
        
        if (empty($bech32Hrp)) {
            return false;
        }

        // Use strict Segwit validation only
        return Bech32::isValidSegwitAddress($address, $bech32Hrp);
    }

    /**
     * Get address type from address
     *
     * @param string $address
     * @param array<string, mixed> $options
     * @return string|null
     */
    private function getAddressType(string $address, array $options): ?string
    {
        $decoded = Base58::decode($address);
        if ($decoded === null) {
            return null;
        }

        // Check length - standard Bitcoin-style addresses should be 25 bytes
        if (strlen($decoded) !== 25) {
            return null;
        }

        // All Bitcoin-derived currencies use the same double SHA-256 checksum algorithm
        // Validate checksum strictly for Bitcoin, Dogecoin, Litecoin, etc.
        if (!Base58::validateBitcoinChecksum($address)) {
            return null;
        }

        // Check regex if provided
        if (isset($options['regex'])) {
            if (!preg_match($options['regex'], $address)) {
                return null;
            }
        }

        // Get the version byte (first byte) - convert to lowercase for consistency
        return strtolower(CryptoUtils::toHex(substr($decoded, 0, 1)));
    }

    /**
     * Calculate checksum based on hash function
     *
     * @param string $hashFunction
     * @param string $payload
     * @return string
     */
    private function getChecksum(string $hashFunction, string $payload): string
    {
        return match ($hashFunction) {
            'blake256keccak256' => $this->getBlakeKeccakChecksum($payload),
            'blake256' => CryptoUtils::blake256Checksum($payload),
            'keccak256' => CryptoUtils::keccak256Checksum($payload),
            'sha256' => CryptoUtils::sha256Checksum($payload),
            default => CryptoUtils::sha256Checksum($payload),
        };
    }

    /**
     * Get Blake-Keccak combined checksum
     *
     * @param string $payload
     * @return string
     */
    private function getBlakeKeccakChecksum(string $payload): string
    {
        $blake = CryptoUtils::blake2b256($payload);
        return CryptoUtils::keccak256Checksum($blake);
    }

    /**
     * Get address types for network
     *
     * @param string $networkType
     * @return array<string>
     */
    private function getAddressTypes(string $networkType): array
    {
        $addressTypes = $this->getConfig('addressTypes', []);
        
        if (isset($addressTypes[$networkType])) {
            return $addressTypes[$networkType];
        }
        
        if ($networkType === 'prod' || $networkType === 'testnet') {
            return [];
        }
        
        // Return combined prod and testnet if network type is not specific
        return array_merge(
            $addressTypes['prod'] ?? [],
            $addressTypes['testnet'] ?? []
        );
    }

    /**
     * Get Bech32 HRP for network
     *
     * @param string $networkType
     * @return array<string>
     */
    private function getBech32Hrp(string $networkType): array
    {
        $bech32Hrp = $this->getConfig('bech32Hrp', []);
        
        if (isset($bech32Hrp[$networkType])) {
            return $bech32Hrp[$networkType];
        }
        
        return [];
    }
}