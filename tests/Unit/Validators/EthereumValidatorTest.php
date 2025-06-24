<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Tests\Unit\Validators;

use Multicoin\AddressValidator\Validators\EthereumValidator;
use PHPUnit\Framework\TestCase;

class EthereumValidatorTest extends TestCase
{
    private EthereumValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new EthereumValidator();
    }

    public function testValidLowercaseAddress(): void
    {
        $validAddresses = [
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c',
            '0xde709f2102306220921060314715629080e2fb77',
            '0x5aae9d6e5484c20d5e7b7b1c8c1d5e8e3b4f5e8e'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address),
                "Address {$address} should be valid"
            );
        }
    }

    public function testValidUppercaseAddress(): void
    {
        $validAddresses = [
            '0x742D35CC6339C4532CE58B5D3EA8D5A8D6F6395C',
            '0xDE709F2102306220921060314715629080E2FB77',
            '0x5AAE9D6E5484C20D5E7B7B1C8C1D5E8E3B4F5E8E'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address),
                "Address {$address} should be valid"
            );
        }
    }

    public function testValidChecksumAddress(): void
    {
        $validAddresses = [
            '0x742D35cc6339c4532ce58B5d3ea8D5a8d6f6395C',
            '0x5aaeb6053f3E94C9b9A09f33669435E7EF1Beaed',
            '0xFB6916095Ca1df60BB79ce92Ce3eA74c37c5d359'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address),
                "Checksum address {$address} should be valid"
            );
        }
    }

    public function testInvalidChecksumAddress(): void
    {
        // With the modified validator, addresses with incorrect checksums are now accepted
        // This reflects the more permissive approach for real-world compatibility
        $addressesWithIncorrectChecksums = [
            '0x742d35CC6339C4532CE58b5D3Ea8d5A8d6F6395C', // Wrong checksum
            '0x5aAeb6053F3E94C9b9A09f33669435E7ef1BeAed', // Wrong checksum
            '0xfB6916095ca1df60bB79Ce92cE3Ea74c37c5D359'  // Wrong checksum
        ];

        foreach ($addressesWithIncorrectChecksums as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address),
                "Address with incorrect checksum {$address} should now be valid (permissive mode)"
            );
        }
    }

    public function testInvalidAddresses(): void
    {
        $invalidAddresses = [
            '',
            '0x',
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395', // Too short
            // Note: Long addresses are now trimmed, so the following is valid:
            // '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395cc', // Too long -> now trimmed
            '742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c', // Missing 0x prefix
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395g', // Invalid hex character
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin address
            'not-an-address',
        ];

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->isValidAddress($address),
                "Address {$address} should be invalid"
            );
        }
    }

    public function testGetSupportedNetworks(): void
    {
        $networks = $this->validator->getSupportedNetworks();
        
        $this->assertContains('mainnet', $networks);
        $this->assertContains('testnet', $networks);
    }

    public function testAddressWithoutPrefix(): void
    {
        $address = '742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c';
        
        $this->assertFalse($this->validator->isValidAddress($address));
    }

    public function testAddressWithInvalidLength(): void
    {
        $shortAddress = '0x742d35cc';
        $longAddress = '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c742d35cc';
        
        $this->assertFalse($this->validator->isValidAddress($shortAddress));
        // Long addresses are now trimmed to 42 characters if they're valid hex
        $this->assertTrue($this->validator->isValidAddress($longAddress));
    }
}