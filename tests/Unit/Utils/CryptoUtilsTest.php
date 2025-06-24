<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Tests\Unit\Utils;

use Multicoin\AddressValidator\Utils\CryptoUtils;
use PHPUnit\Framework\TestCase;

class CryptoUtilsTest extends TestCase
{
    public function testSha256Checksum(): void
    {
        $data = '0014751e76e8199196d454941c45d1b3a323f1433bd6';
        $result = CryptoUtils::sha256Checksum($data);
        
        $this->assertIsString($result);
        $this->assertEquals(8, strlen($result));
        $this->assertTrue(ctype_xdigit($result));
    }

    public function testBlake2b256(): void
    {
        $data = '0014751e76e8199196d454941c45d1b3a323f1433bd6';
        $result = CryptoUtils::blake2b256($data);
        
        $this->assertIsString($result);
        $this->assertEquals(64, strlen($result)); // 32 bytes = 64 hex chars
        $this->assertTrue(ctype_xdigit($result));
    }

    public function testKeccak256(): void
    {
        $data = 'hello world';
        $result = CryptoUtils::keccak256($data);
        
        $this->assertIsString($result);
        $this->assertEquals(64, strlen($result)); // 32 bytes = 64 hex chars
        $this->assertTrue(ctype_xdigit($result));
    }

    public function testToHex(): void
    {
        $binary = "\x00\x14\x75\x1e\x76\xe8";
        $result = CryptoUtils::toHex($binary);
        
        $this->assertEquals('0014751E76E8', $result);
    }

    public function testIsValidHexWithValidHex(): void
    {
        $this->assertTrue(CryptoUtils::isValidHex('deadbeef'));
        $this->assertTrue(CryptoUtils::isValidHex('DEADBEEF'));
        $this->assertTrue(CryptoUtils::isValidHex('123456789ABCDEF'));
    }

    public function testIsValidHexWithInvalidHex(): void
    {
        $this->assertFalse(CryptoUtils::isValidHex('xyz'));
        $this->assertFalse(CryptoUtils::isValidHex('deadbeeg'));
        $this->assertFalse(CryptoUtils::isValidHex(''));
    }

    public function testBlake256Checksum(): void
    {
        $data = '0014751e76e8199196d454941c45d1b3a323f1433bd6';
        $result = CryptoUtils::blake256Checksum($data);
        
        $this->assertIsString($result);
        $this->assertEquals(8, strlen($result));
        $this->assertTrue(ctype_xdigit($result));
    }

    public function testKeccak256Checksum(): void
    {
        $data = '0014751e76e8199196d454941c45d1b3a323f1433bd6';
        $result = CryptoUtils::keccak256Checksum($data);
        
        $this->assertIsString($result);
        $this->assertEquals(8, strlen($result));
        $this->assertTrue(ctype_xdigit($result));
    }
}