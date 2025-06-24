<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Tests\Unit\Utils;

use Multicoin\AddressValidator\Utils\Base58;
use PHPUnit\Framework\TestCase;

class Base58Test extends TestCase
{
    public function testDecodeValidInput(): void
    {
        $input = '3yZe7d';
        $result = Base58::decode($input);
        
        $this->assertIsString($result);
        $this->assertNotNull($result);
    }

    public function testDecodeInvalidInput(): void
    {
        $input = '0OIl'; // Contains invalid characters
        $result = Base58::decode($input);
        
        $this->assertNull($result);
    }

    public function testDecodeEmptyString(): void
    {
        $result = Base58::decode('');
        
        $this->assertNull($result);
    }

    public function testEncodeDecodeRoundTrip(): void
    {
        $original = 'hello world';
        $encoded = Base58::encode($original);
        $decoded = Base58::decode($encoded);
        
        $this->assertEquals($original, $decoded);
    }

    public function testIsValidWithValidString(): void
    {
        $validBase58 = 'StV1DL6CwTryKyV';
        
        $this->assertTrue(Base58::isValid($validBase58));
    }

    public function testIsValidWithInvalidString(): void
    {
        $invalidBase58 = 'StV1DL6CwTryKyV0'; // Contains '0' which is invalid
        
        $this->assertFalse(Base58::isValid($invalidBase58));
    }

    public function testIsValidWithEmptyString(): void
    {
        $this->assertFalse(Base58::isValid(''));
    }

    public function testEncodeEmptyString(): void
    {
        $result = Base58::encode('');
        
        $this->assertEquals('', $result);
    }

    public function testDecodeWithLeadingZeros(): void
    {
        $input = '11234'; // Leading '1's represent zeros
        $result = Base58::decode($input);
        
        $this->assertNotNull($result);
        $this->assertEquals("\x00\x00", substr($result, 0, 2));
    }
}