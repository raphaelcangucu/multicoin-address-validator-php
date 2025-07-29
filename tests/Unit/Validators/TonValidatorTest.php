<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Tests\Unit\Validators;

use Multicoin\AddressValidator\Validators\TonValidator;
use PHPUnit\Framework\TestCase;

class TonValidatorTest extends TestCase
{
    private TonValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new TonValidator();
    }

    public function testValidTonUserFriendlyAddresses(): void
    {
        $validAddresses = [
            // Known valid addresses with correct CRC16 checksums
            'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPrHF', // Bounceable mainnet
            'UQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPuwA', // Non-bounceable mainnet
            'EQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAM9c', // Zero address (bounceable)
            'EQCxE6mUtQJKFnGfaROTKOt1lZbDiiX1kCixRv7Nw2Id_sDs', // Real TON address
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address),
                "Address {$address} should be valid"
            );
        }
    }

    public function testValidTonRawAddresses(): void
    {
        $validRawAddresses = [
            '0:ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3e',
            '-1:3333333333333333333333333333333333333333333333333333333333333333',
            '0:0000000000000000000000000000000000000000000000000000000000000000',
            '-1:fcb91a3a3816d0f7b8c2c76108b8bd2c5e03204971b137b5c0fc4ee0ce20d4b6',
            '0:34b7d4c558b42db4bb67a4b24baedee2b71ccf5b03e4c19df75e1b7a4eb84b6b',
        ];

        foreach ($validRawAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address),
                "Raw address {$address} should be valid"
            );
        }
    }

    public function testInvalidTonAddresses(): void
    {
        $invalidAddresses = [
            // Empty and malformed
            '',
            '   ',
            'invalid',
            
            // Wrong length
            'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqP', // Too short (47 chars)
            'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPrHFX', // Too long (51 chars)
            
            // Invalid characters in user-friendly format
            'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqP#HF', // Contains #
            'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqP@HF', // Contains @
            'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqP$HF', // Contains $
            
            // Invalid base64
            'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
            '============================================',
            
            // Invalid raw format
            '2:ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3e', // Invalid workchain
            '-2:ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3e', // Invalid workchain
            '0:ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3g', // Invalid hex (g)
            '0:ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3', // Too short hex
            '0:ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3ee', // Too long hex
            'ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3e', // Missing workchain
            '0ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3e', // Missing colon
            
            // Other cryptocurrency addresses
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            'So11111111111111111111111111111111111111112', // Solana
            'rDNa8YVjQafgVcVasdWmNhfgSLB9qqwUqXNMp3VSksJ5Z5', // Ripple
        ];

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->isValidAddress($address),
                "Address {$address} should be invalid"
            );
        }
    }

    public function testNetworkTypeValidation(): void
    {
        // Mainnet address should be valid for prod network
        $mainnetAddress = 'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPrHF';
        $this->assertTrue($this->validator->isValidAddress($mainnetAddress, ['networkType' => 'prod']));
        
        // Testnet address should be invalid for prod network
        $testnetAddress = 'kQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPgpP';
        $this->assertFalse($this->validator->isValidAddress($testnetAddress, ['networkType' => 'prod']));
        
        // Testnet address should be valid for testnet network
        $this->assertTrue($this->validator->isValidAddress($testnetAddress, ['networkType' => 'testnet']));
        
        // Mainnet address should be invalid for testnet network
        $this->assertFalse($this->validator->isValidAddress($mainnetAddress, ['networkType' => 'testnet']));
    }

    public function testBounceableAndNonBounceableAddresses(): void
    {
        // Bounceable address (starts with E for mainnet)
        $bounceableAddress = 'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPrHF';
        $this->assertTrue($this->validator->isValidAddress($bounceableAddress));
        
        // Non-bounceable address (starts with U for mainnet)
        $nonBounceableAddress = 'UQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPuwA'; 
        $this->assertTrue($this->validator->isValidAddress($nonBounceableAddress));
    }

    public function testAddressLengthBoundaries(): void
    {
        // Test minimum length (48 characters) - should be valid
        $minLengthAddress = 'EQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAM9c';
        $this->assertTrue($this->validator->isValidAddress($minLengthAddress));
        $this->assertEquals(48, strlen($minLengthAddress));
        
        // Test maximum length (50 characters) - should be valid  
        $maxLengthAddress = 'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPrHF==';
        $this->assertTrue($this->validator->isValidAddress($maxLengthAddress));
        $this->assertEquals(50, strlen($maxLengthAddress));
        
        // Test below minimum (47 characters) - should be invalid
        $belowMinAddress = 'EQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAM9';
        $this->assertFalse($this->validator->isValidAddress($belowMinAddress));
        
        // Test above maximum (51 characters) - should be invalid
        $aboveMaxAddress = 'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPrHF===';
        $this->assertFalse($this->validator->isValidAddress($aboveMaxAddress));
    }

    public function testRawAddressWorkchainValidation(): void
    {
        // Valid workchains: -1 (masterchain) and 0 (basechain)
        $this->assertTrue($this->validator->isValidAddress('-1:ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3e'));
        $this->assertTrue($this->validator->isValidAddress('0:ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3e'));
        
        // Invalid workchains
        $this->assertFalse($this->validator->isValidAddress('1:ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3e'));
        $this->assertFalse($this->validator->isValidAddress('-2:ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3e'));
        $this->assertFalse($this->validator->isValidAddress('2:ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3e'));
        $this->assertFalse($this->validator->isValidAddress('999:ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3e'));
    }

    public function testBase64UrlSafeCharacters(): void
    {
        // Address with URL-safe characters (- and _)
        $urlSafeAddress = 'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPrHF';
        $this->assertTrue($this->validator->isValidAddress($urlSafeAddress));
        
        // This test is mainly to show that the validator handles base64url format
        // In practice, TON addresses use base64url encoding primarily
        $this->assertIsString($urlSafeAddress);
    }

    public function testGetSupportedNetworks(): void
    {
        $networks = $this->validator->getSupportedNetworks();
        
        $this->assertContains('prod', $networks);
        $this->assertContains('testnet', $networks);
        $this->assertCount(2, $networks);
    }

    public function testEmptyAndWhitespaceAddresses(): void
    {
        $this->assertFalse($this->validator->isValidAddress(''));
        $this->assertFalse($this->validator->isValidAddress('   '));
        $this->assertFalse($this->validator->isValidAddress("\t"));
        $this->assertFalse($this->validator->isValidAddress("\n"));
    }

    public function testCrcValidation(): void
    {
        // Valid address with correct CRC
        $validAddress = 'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPrHF';
        $this->assertTrue($this->validator->isValidAddress($validAddress));
        
        // Invalid address with wrong CRC (modified last character)
        $invalidCrcAddress = 'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPrHG';
        $this->assertFalse($this->validator->isValidAddress($invalidCrcAddress));
        
        // Invalid address with wrong CRC (modified middle characters)
        $invalidCrcAddress2 = 'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPrXX';
        $this->assertFalse($this->validator->isValidAddress($invalidCrcAddress2));
    }

    public function testNullByteAddress(): void
    {
        // Address with null bytes - should be invalid
        $addressWithNull = "EQDKbjIcfM6ezt8KjKJJLshZJJSqX\0XOA4ff-W72r5gqPrHF";
        $this->assertFalse($this->validator->isValidAddress($addressWithNull));
    }

    public function testCaseSensitivity(): void
    {
        // TON addresses are case-sensitive
        $originalAddress = 'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPrHF';
        $lowercaseAddress = strtolower($originalAddress);
        $uppercaseAddress = strtoupper($originalAddress);
        
        $this->assertTrue($this->validator->isValidAddress($originalAddress));
        
        // Lowercase and uppercase versions should be invalid due to CRC mismatch
        $this->assertFalse($this->validator->isValidAddress($lowercaseAddress));
        $this->assertFalse($this->validator->isValidAddress($uppercaseAddress));
    }
}