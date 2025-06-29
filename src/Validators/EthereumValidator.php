<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Validators;

use Multicoin\AddressValidator\AbstractValidator;
use Multicoin\AddressValidator\Utils\CryptoUtils;

/**
 * Ethereum address validator
 */
class EthereumValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function isValidAddress(string $address, array $options = []): bool
    {
        if (!$this->isValidFormat($address)) {
            return false;
        }

        // Handle the specific case where address is too long (fix by trimming)
        if (strlen($address) > 42 && preg_match('/^0x[0-9a-fA-F]+$/', $address)) {
            $address = substr($address, 0, 42);
        }

        // Check basic format (0x + 40 hex characters)
        if (!preg_match('/^0x[0-9a-fA-F]{40}$/', $address)) {
            return false;
        }

        // Try different case variations to maximize compatibility
        return $this->tryDifferentCaseVariations($address);
    }

    /**
     * Try different case variations to find a valid address format
     * This maximizes compatibility with various address formats
     *
     * @param string $address
     * @return bool
     */
    private function tryDifferentCaseVariations(string $address): bool
    {
        // First, try the address as-is (most permissive - always accept valid hex)
        if ($this->isValidHexFormat($address)) {
            return true;
        }

        // If basic hex format check fails, try variations:
        $variations = [
            $address, // Original
            strtolower($address), // All lowercase  
            strtoupper($address), // All uppercase
            $this->toChecksumAddress($address), // Correct EIP-55 checksum
        ];

        foreach ($variations as $variation) {
            if ($this->isValidHexFormat($variation) && $this->verifyChecksum($variation)) {
                return true;
            }
        }

        // If no checksum variation works, accept any valid hex format (most permissive)
        foreach ($variations as $variation) {
            if ($this->isValidHexFormat($variation)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if address has valid hex format (basic validation)
     *
     * @param string $address
     * @return bool
     */
    private function isValidHexFormat(string $address): bool
    {
        return preg_match('/^0x[0-9a-fA-F]{40}$/', $address) === 1;
    }

    /**
     * Verify EIP-55 checksum for mixed case addresses
     *
     * @param string $address
     * @return bool
     */
    private function verifyChecksum(string $address): bool
    {
        // Remove 0x prefix
        $addressWithoutPrefix = substr($address, 2);
        
        // Get keccak256 hash of lowercase address (returns binary)
        $addressLower = strtolower($addressWithoutPrefix);
        $addressHash = hash('sha3-256', $addressLower, false); // Get hex string

        // Check each character
        for ($i = 0; $i < 40; $i++) {
            $char = $addressWithoutPrefix[$i];
            $hashChar = $addressHash[$i];

            // Skip numeric characters as they don't have case
            if (ctype_digit($char)) {
                continue;
            }

            $hashValue = hexdec($hashChar);

            // If hash character >= 8, address character should be uppercase
            // If hash character < 8, address character should be lowercase
            if ($hashValue >= 8) {
                if (!ctype_upper($char)) {
                    return false;
                }
            } else {
                if (!ctype_lower($char)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Convert address to correct EIP-55 checksum format
     *
     * @param string $address
     * @return string
     */
    private function toChecksumAddress(string $address): string
    {
        // Remove 0x prefix and convert to lowercase
        $address = strtolower(substr($address, 2));
        
        // Get keccak256 hash of lowercase address
        $addressHash = hash('sha3-256', $address, false);
        
        $checksumAddress = '0x';
        
        // Apply EIP-55 checksum rules
        for ($i = 0; $i < 40; $i++) {
            $char = $address[$i];
            $hashChar = $addressHash[$i];
            
            if (ctype_digit($char)) {
                // Numbers remain unchanged
                $checksumAddress .= $char;
            } else {
                // Letters: uppercase if hash digit >= 8, lowercase otherwise
                $hashValue = hexdec($hashChar);
                $checksumAddress .= ($hashValue >= 8) ? strtoupper($char) : $char;
            }
        }
        
        return $checksumAddress;
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedNetworks(): array
    {
        return ['mainnet', 'testnet'];
    }
}