<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Tests\Unit\Validators;

use Multicoin\AddressValidator\Validators\BitcoinValidator;
use PHPUnit\Framework\TestCase;

class BitcoinValidatorTest extends TestCase
{
    private BitcoinValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new BitcoinValidator([
            'addressTypes' => [
                'prod' => ['00', '05'],
                'testnet' => ['6f', 'c4', '3c', '26']
            ],
            'bech32Hrp' => [
                'prod' => ['bc'],
                'testnet' => ['tb']
            ]
        ]);
    }

    public function testValidMainnetP2PKHAddress(): void
    {
        $validAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2',
            '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
            '12higDjoCCNXSA95xZMWUdPvXNmkAduhWv'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address, ['networkType' => 'prod']),
                "Address {$address} should be valid"
            );
        }
    }

    public function testValidMainnetP2SHAddress(): void
    {
        $validAddresses = [
            '3J98t1WpEZ73CNmQviecrnyiWrnqRhWNLy',
            '34xp4vRoCGJym3xR7yCVPFHoCNxv4Twseo'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address, ['networkType' => 'prod']),
                "Address {$address} should be valid"
            );
        }
    }

    public function testValidTestnetAddress(): void
    {
        $validAddresses = [
            'mipcBbFg9gMiCh81Kj8tqqdgoZub1ZJRfn',
            'mzBc4XEFSdzCDcTxAgf6EZXgsZWpztRhef',
            '2MzQwSSnBHWHqSAqtTVQ6v47XtaisrJa1Vc'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address, ['networkType' => 'testnet']),
                "Testnet address {$address} should be valid"
            );
        }
    }

    public function testValidBech32Address(): void
    {
        $validAddresses = [
            'bc1qrh2gtnewena8x2dpg6qq92znqrz896knrkf0m9', // User-provided valid address
            'bc1qrp33g0q5c5txsp9arysrx4k6zdkfs4nce4xj0gdcccefvpysxf3qccfmv3'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address, ['networkType' => 'prod']),
                "Bech32 address {$address} should be valid"
            );
        }
    }

    public function testValidTestnetBech32Address(): void
    {
        $validAddresses = [
            'tb1qw508d6qejxtdg4y5r3zarvary0c5xw7kxpjzsx',
            'tb1qrp33g0q5c5txsp9arysrx4k6zdkfs4nce4xj0gdcccefvpysxf3q0sl5k7'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address, ['networkType' => 'testnet']),
                "Testnet Bech32 address {$address} should be valid"
            );
        }
    }

    public function testInvalidAddresses(): void
    {
        $invalidAddresses = [
            '',
            '1',
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN3', // Invalid checksum
            'bc1qw508d6qejxtdg4y5r3zarvary0c5xw7kv8f3t5', // Invalid Bech32
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum address
            'not-an-address',
            '1234567890',
        ];

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->isValidAddress($address, ['networkType' => 'prod']),
                "Address {$address} should be invalid"
            );
        }
    }

    public function testEmptyAddress(): void
    {
        $this->assertFalse($this->validator->isValidAddress(''));
    }

    public function testGetSupportedNetworks(): void
    {
        $networks = $this->validator->getSupportedNetworks();
        
        $this->assertContains('prod', $networks);
        $this->assertContains('testnet', $networks);
    }

    public function testAddressWithWrongNetwork(): void
    {
        // Mainnet address tested on testnet
        $mainnetAddress = '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2';
        
        $this->assertFalse(
            $this->validator->isValidAddress($mainnetAddress, ['networkType' => 'testnet'])
        );
    }
}