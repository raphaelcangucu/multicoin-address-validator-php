<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Tests\Unit\Validators;

use Multicoin\AddressValidator\Validators\SolanaValidator;
use PHPUnit\Framework\TestCase;

class SolanaValidatorTest extends TestCase
{
    private SolanaValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new SolanaValidator([
            'maxLength' => SolanaValidator::MAX_LENGTH,
            'minLength' => SolanaValidator::MIN_LENGTH
        ]);
    }

    public function testValidSolanaAddresses(): void
    {
        $validAddresses = [
            '11111111111111111111111111111111',
            'SysvarC1ock11111111111111111111111111111111',
            'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
            'So11111111111111111111111111111111111111112',
            'EPjFWdd5AufqSSqeM2qN1xzybapC8G4wEGGkZwyTDt1v'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address),
                "Address {$address} should be valid"
            );
        }
    }

    public function testInvalidSolanaAddresses(): void
    {
        $invalidAddresses = [
            '',
            '1111111111111111111111111111111', // Too short (31 chars)
            '111111111111111111111111111111111111111111111', // Too long
            'SysvarC1ock1111111111111111111111111111111O', // Contains 'O' (invalid Base58)
            'SysvarC1ock1111111111111111111111111111111l', // Contains 'l' (invalid Base58)
            'SysvarC1ock11111111111111111111111111111110', // Contains '0' (invalid Base58)
            'SysvarC1ock1111111111111111111111111111111I', // Contains 'I' (invalid Base58)
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum address
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin address
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
        // Test minimum length boundary (System Program ID)
        $minLengthAddress = '11111111111111111111111111111111';
        $this->assertTrue($this->validator->isValidAddress($minLengthAddress));

        // Test maximum length boundary (using real Solana address)
        $maxLengthAddress = 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA';
        $this->assertTrue($this->validator->isValidAddress($maxLengthAddress));

        // Test too short
        $tooShortAddress = str_repeat('1', SolanaValidator::MIN_LENGTH - 1);
        $this->assertFalse($this->validator->isValidAddress($tooShortAddress));

        // Test too long
        $tooLongAddress = str_repeat('1', SolanaValidator::MAX_LENGTH + 1);
        $this->assertFalse($this->validator->isValidAddress($tooLongAddress));
    }

    public function testGetSupportedNetworks(): void
    {
        $networks = $this->validator->getSupportedNetworks();
        
        $this->assertContains('mainnet', $networks);
        $this->assertContains('testnet', $networks);
        $this->assertContains('devnet', $networks);
    }

    public function testEmptyAddress(): void
    {
        $this->assertFalse($this->validator->isValidAddress(''));
    }

    public function testBase58InvalidCharacters(): void
    {
        $invalidCharacters = ['0', 'O', 'I', 'l'];
        
        foreach ($invalidCharacters as $char) {
            $address = '1111111111111111111111111111111111111111' . $char . '11';
            $this->assertFalse(
                $this->validator->isValidAddress($address),
                "Address containing '{$char}' should be invalid"
            );
        }
    }

    public function testValidBase58Characters(): void
    {
        // Use a real Solana address that contains various valid Base58 characters
        $validAddress = 'So11111111111111111111111111111111111111112';
        
        $this->assertTrue($this->validator->isValidAddress($validAddress));
    }
}