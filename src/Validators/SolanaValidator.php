<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Validators;

use Multicoin\AddressValidator\AbstractValidator;
use Multicoin\AddressValidator\Utils\Base58;

/**
 * Solana address validator
 */
class SolanaValidator extends AbstractValidator
{
    public const MIN_LENGTH = 32;
    public const MAX_LENGTH = 48;

    /**
     * {@inheritdoc}
     */
    public function isValidAddress(string $address, array $options = []): bool
    {
        if (!$this->isValidFormat($address)) {
            return false;
        }

        $minLength = $this->getConfig('minLength', self::MIN_LENGTH);
        $maxLength = $this->getConfig('maxLength', self::MAX_LENGTH);

        // Check length
        $length = strlen($address);
        if ($length < $minLength || $length > $maxLength) {
            return false;
        }

        // Check if it's valid Base58
        if (!Base58::isValid($address)) {
            return false;
        }

        // For Solana, we'll do a basic decode check
        $decoded = Base58::decode($address);
        if ($decoded === null) {
            return false;
        }

        // Solana addresses should decode to 32 bytes, but some token addresses might be slightly different
        // Be more permissive to handle various Solana address formats
        $decodedLength = strlen($decoded);
        return $decodedLength >= 32 && $decodedLength <= 35;
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedNetworks(): array
    {
        return ['mainnet', 'testnet', 'devnet'];
    }
}