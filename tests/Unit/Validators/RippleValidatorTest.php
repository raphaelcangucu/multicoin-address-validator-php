<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Tests\Unit\Validators;

use Multicoin\AddressValidator\Validators\RippleValidator;
use PHPUnit\Framework\TestCase;

class RippleValidatorTest extends TestCase
{
    private RippleValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new RippleValidator();
    }

    public function testValidMainnetAddresses(): void
    {
        $validAddresses = [
            // Real XRP mainnet addresses
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w',
            'rU6K7V3Po4snVhBBaU29sesqs2qTQJWDw1',
            'rN7n7otQDd6FczFgLdSqtcsAUxDkw6fzRH',
            'rEb8TK3gBgk5auZkwc6sHnwrGVJH8DuaLh',
            'rDsbeomae3ptLd3bvpawTguwAjsReY6R4Y',
            'rPEPPER7kfTD9w2To4CQk6UCfuHM9c6GDY',
            'rK1EZ6GGVc61BZjEQJTQBqfPeKsQxE3z3T',
            'rw2ciyaNshpHe7bCHo4bRWq6pqqynnWKQg',
            'rJPRjm2m7WW1SG8HFzjw3XT8F2FZ9nE2SH'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address),
                "XRP address {$address} should be valid"
            );
        }
    }

    public function testValidAddressesWithMemo(): void
    {
        $validAddressesWithMemo = [
            // XRP addresses with memo codes
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w?dt=123456',
            'rU6K7V3Po4snVhBBaU29sesqs2qTQJWDw1?dt=MEMO123',
            'rN7n7otQDd6FczFgLdSqtcsAUxDkw6fzRH?dt=EXCHANGE_DEPOSIT',
            'rEb8TK3gBgk5auZkwc6sHnwrGVJH8DuaLh?dt=USER_ID_12345',
            'rDsbeomae3ptLd3bvpawTguwAjsReY6R4Y?dt=987654321',
            'rPEPPER7kfTD9w2To4CQk6UCfuHM9c6GDY?dt=BINANCE_MEMO',
            'rK1EZ6GGVc61BZjEQJTQBqfPeKsQxE3z3T?dt=COINBASE_DEPOSIT',
            'rw2ciyaNshpHe7bCHo4bRWq6pqqynnWKQg?dt=1001',
            'rJPRjm2m7WW1SG8HFzjw3XT8F2FZ9nE2SH?dt=KRAKEN_USER_123'
        ];

        foreach ($validAddressesWithMemo as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address),
                "XRP address with memo {$address} should be valid"
            );
        }
    }

    public function testValidTestnetAddresses(): void
    {
        $validAddresses = [
            // Real XRP testnet addresses (same format as mainnet)
            'rHb9CJAWyB4rj91VRWn96DkukG4bwdtyTh',
            'rQfVnfZKx8Lb1xEbZ4NzqfVMv9Fuz7vWLg',
            'rBHfYHMhMG8LyCCiCXD82QfX3WLPzJMGHM'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address, ['networkType' => 'testnet']),
                "XRP testnet address {$address} should be valid"
            );
        }
    }

    public function testInvalidAddresses(): void
    {
        $invalidAddresses = [
            '',
            'r', // Too short
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            'addr1qx2fxv2umyhttkxyxp8x0dlpdt3k6cwng5pxj3jhsydzer3jcu5d8ps7zex2k2xt3uqxgjqnnj83ws8lhrn648jjxtwq2ytjqp', // Cardano
            'xLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w', // Wrong prefix
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6', // Too short
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6wX', // Too long
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc60', // Invalid character '0'
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6O', // Invalid character 'O'
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6I', // Invalid character 'I'
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6l', // Invalid character 'l'
            'aLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w', // Wrong first character
            'RLNEPOKEEBJZE2QS6X52YVPZPZ8TD4DC6W', // Wrong case
        ];

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->isValidAddress($address),
                "Address {$address} should be invalid"
            );
        }
    }

    public function testAddressFormat(): void
    {
        // Test address format constraints
        $this->assertFalse($this->validator->isValidAddress('r123')); // Too short
        $this->assertFalse($this->validator->isValidAddress('r' . str_repeat('1', 40))); // Too long
        $this->assertTrue($this->validator->isValidAddress('r' . str_repeat('1', 24))); // Minimum valid length
        $this->assertTrue($this->validator->isValidAddress('r' . str_repeat('1', 33))); // Maximum valid length
    }

    public function testGetSupportedNetworks(): void
    {
        $networks = $this->validator->getSupportedNetworks();
        
        $this->assertContains('mainnet', $networks);
        $this->assertContains('testnet', $networks);
    }

    public function testRippleAlphabetValidation(): void
    {
        // Valid Ripple alphabet characters
        $validChars = 'rpshnaf39wBUDNEGHJKLM4PQRST7VWXYZ2bcdeCg65jkm8oFqi1tuvAxyz';
        $validAddress = 'r' . str_repeat('p', 24);
        $this->assertTrue($this->validator->isValidAddress($validAddress));

        // Invalid characters not in Ripple alphabet
        $invalidChars = ['0', 'O', 'I', 'l'];
        foreach ($invalidChars as $char) {
            $invalidAddress = 'r' . str_repeat('p', 23) . $char;
            $this->assertFalse(
                $this->validator->isValidAddress($invalidAddress),
                "Address with invalid character '{$char}' should be invalid"
            );
        }
    }
}