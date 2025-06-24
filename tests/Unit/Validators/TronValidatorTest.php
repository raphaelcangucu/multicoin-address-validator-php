<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Tests\Unit\Validators;

use Multicoin\AddressValidator\Validators\TronValidator;
use PHPUnit\Framework\TestCase;

class TronValidatorTest extends TestCase
{
    private TronValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new TronValidator();
    }

    public function testValidMainnetAddresses(): void
    {
        $validAddresses = [
            // Real TRON mainnet addresses  
            'TMTKSGaCe7qZyngFfRXkDANm3ahTR98Kt6', // User-provided valid USDT on TRC address
            'TMuA6YqfCeX8EhbfYEg5y7S4DqzSJireY9',
            'TLyqzVGLV1srkB7dToTAEqgDSfPtXRJZYH',
            'TUJm5LjC92KrYz2zn3Pth59Y8L2e8VX7vK',
            'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t', // USDT on TRON
            'TEKaAy6gpjorWoaCgSP5aTgxMJjuddgXFP',
            'TFQhBBjV4LHkFrZxhbqg8SrZAXxNqF4Qcx',
            'TG3XXyExBkPp9nzdajDZsozEu4BkaSJozs', // BitTorrent Token
            'TLBaRhANQoJFTqre9Nf1mjuwNWjCJeYqUL',
            'TKzxdSv2FZKQrEqkKVgp5DcwEXBEKMg2Ax'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address),
                "TRON address {$address} should be valid"
            );
        }
    }

    public function testValidTestnetAddresses(): void
    {
        $validAddresses = [
            // TRON testnet addresses (same format, but different network)
            'TXYZaB2cyDuaRwtANXjzQHKo1Qb2cDeFgh',
            'TTestNetAddressForValidation123456',
            'T9yD14Nj9j7xAB4dbGeiX9h8unkKHxuWwb'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address, ['networkType' => 'testnet']),
                "TRON testnet address {$address} should be valid"
            );
        }
    }

    public function testInvalidAddresses(): void
    {
        $invalidAddresses = [
            '',
            'T', // Too short
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w', // Ripple
            'ALyqzVGLV1srkB7dToTAEqgDSfPtXRJZYH', // Wrong prefix (A instead of T)
            'BLyqzVGLV1srkB7dToTAEqgDSfPtXRJZYH', // Wrong prefix (B instead of T)
            'TLyqzVGLV1srkB7dToTAEqgDSfPtXRJZY', // Too short (33 chars)
            'TLyqzVGLV1srkB7dToTAEqgDSfPtXRJZYHX', // Too long (35 chars)
            'TLyqzVGLV1srkB7dToTAEqgDSfPtXRJZY0', // Invalid character '0'
            'TLyqzVGLV1srkB7dToTAEqgDSfPtXRJZYO', // Invalid character 'O'
            'TLyqzVGLV1srkB7dToTAEqgDSfPtXRJZYI', // Invalid character 'I'
            'TLyqzVGLV1srkB7dToTAEqgDSfPtXRJZYl', // Invalid character 'l'
            'tLyqzVGLV1srkB7dToTAEqgDSfPtXRJZYH', // Lowercase 't'
        ];

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->isValidAddress($address),
                "Address {$address} should be invalid"
            );
        }
    }

    public function testAddressLength(): void
    {
        // Test exact length requirement (34 characters)
        $correctLength = 'T' . str_repeat('1', 33);
        $this->assertTrue($this->validator->isValidAddress($correctLength));

        $tooShort = 'T' . str_repeat('1', 32);
        $this->assertFalse($this->validator->isValidAddress($tooShort));

        $tooLong = 'T' . str_repeat('1', 34);
        $this->assertFalse($this->validator->isValidAddress($tooLong));
    }

    public function testAddressPrefix(): void
    {
        // Only addresses starting with 'T' should be valid
        $validPrefix = 'T' . str_repeat('1', 33);
        $this->assertTrue($this->validator->isValidAddress($validPrefix));

        $invalidPrefixes = ['A', 'B', 'C', 'R', 'X', '1', '3', 'L', 'M'];
        foreach ($invalidPrefixes as $prefix) {
            $address = $prefix . str_repeat('1', 33);
            $this->assertFalse(
                $this->validator->isValidAddress($address),
                "Address with prefix '{$prefix}' should be invalid"
            );
        }
    }

    public function testGetSupportedNetworks(): void
    {
        $networks = $this->validator->getSupportedNetworks();
        
        $this->assertContains('mainnet', $networks);
        $this->assertContains('testnet', $networks);
    }

    public function testBase58Validation(): void
    {
        // Test that only valid Base58 characters are accepted
        $validBase58Chars = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $invalidBase58Chars = ['0', 'O', 'I', 'l'];

        foreach ($invalidBase58Chars as $char) {
            $address = 'T' . str_repeat('1', 32) . $char;
            $this->assertFalse(
                $this->validator->isValidAddress($address),
                "Address containing invalid Base58 character '{$char}' should be invalid"
            );
        }
    }
}