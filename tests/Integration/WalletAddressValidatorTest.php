<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Tests\Integration;

use Multicoin\AddressValidator\CurrencyFactory;
use Multicoin\AddressValidator\Exceptions\UnsupportedCurrencyException;
use Multicoin\AddressValidator\WalletAddressValidator;
use PHPUnit\Framework\TestCase;

class WalletAddressValidatorTest extends TestCase
{
    private WalletAddressValidator $validator;

    protected function setUp(): void
    {
        $registry = CurrencyFactory::createRegistry();
        $this->validator = new WalletAddressValidator($registry);
    }

    public function testValidateBitcoinAddresses(): void
    {
        $validAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2',
            '3J98t1WpEZ73CNmQviecrnyiWrnqRhWNLy',
            'bc1qw508d6qejxtdg4y5r3zarvary0c5xw7kv8f3t4'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'btc'),
                "Bitcoin address {$address} should be valid"
            );
            
            $this->assertTrue(
                $this->validator->validate($address, 'bitcoin'),
                "Bitcoin address {$address} should be valid with full name"
            );
        }
    }

    public function testValidateEthereumAddresses(): void
    {
        $validAddresses = [
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c',
            '0x5aaeb6053f3E94C9b9A09f33669435E7EF1Beaed',
            '0xDE709F2102306220921060314715629080E2FB77'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'eth'),
                "Ethereum address {$address} should be valid"
            );
            
            $this->assertTrue(
                $this->validator->validate($address, 'ethereum'),
                "Ethereum address {$address} should be valid with full name"
            );
        }
    }

    public function testValidateRippleAddresses(): void
    {
        $validAddresses = [
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w',
            'rU6K7V3Po4snVhBBaU29sesqs2qTQJWDw1'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'xrp'),
                "Ripple address {$address} should be valid"
            );
        }
    }

    public function testValidateSolanaAddresses(): void
    {
        $validAddresses = [
            '11111111111111111111111111111111',
            'SysvarC1ock11111111111111111111111111111111',
            'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'sol'),
                "Solana address {$address} should be valid"
            );
        }
    }

    public function testValidateMultipleCurrencies(): void
    {
        $testCases = [
            ['1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', 'btc', true],
            ['0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c', 'eth', true],
            ['rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w', 'xrp', true],
            ['11111111111111111111111111111111', 'sol', true],
            ['LM2WMpR1Rp6j3Sa59cMXMs1SPzj9eXpGc1', 'ltc', true],
            
            // Cross-currency validation should fail
            ['1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', 'eth', false],
            ['0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c', 'btc', false],
            ['invalid-address', 'btc', false],
        ];

        foreach ($testCases as [$address, $currency, $expected]) {
            $result = $this->validator->validate($address, $currency);
            $this->assertEquals(
                $expected,
                $result,
                "Address {$address} for currency {$currency} should " . ($expected ? 'be valid' : 'be invalid')
            );
        }
    }

    public function testUnsupportedCurrency(): void
    {
        $this->expectException(UnsupportedCurrencyException::class);
        $this->expectExceptionMessage('Unsupported currency: unsupported');
        
        $this->validator->validate('1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', 'unsupported');
    }

    public function testDefaultCurrency(): void
    {
        $bitcoinAddress = '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2';
        
        // Should default to Bitcoin
        $this->assertTrue($this->validator->validate($bitcoinAddress));
    }

    public function testGetCurrencies(): void
    {
        $currencies = $this->validator->getCurrencies();
        
        $this->assertIsArray($currencies);
        $this->assertNotEmpty($currencies);
        
        $currencySymbols = array_column($currencies, 'symbol');
        $this->assertContains('btc', $currencySymbols);
        $this->assertContains('eth', $currencySymbols);
        $this->assertContains('xrp', $currencySymbols);
        $this->assertContains('sol', $currencySymbols);
    }

    public function testFindCurrency(): void
    {
        $bitcoin = $this->validator->findCurrency('btc');
        $this->assertNotNull($bitcoin);
        $this->assertEquals('Bitcoin', $bitcoin['name']);
        $this->assertEquals('btc', $bitcoin['symbol']);

        $ethereum = $this->validator->findCurrency('ethereum');
        $this->assertNotNull($ethereum);
        $this->assertEquals('Ethereum', $ethereum['name']);
        $this->assertEquals('eth', $ethereum['symbol']);

        $unknown = $this->validator->findCurrency('unknown');
        $this->assertNull($unknown);
    }

    public function testIsSupported(): void
    {
        $this->assertTrue($this->validator->isSupported('btc'));
        $this->assertTrue($this->validator->isSupported('bitcoin'));
        $this->assertTrue($this->validator->isSupported('eth'));
        $this->assertTrue($this->validator->isSupported('ethereum'));
        
        $this->assertFalse($this->validator->isSupported('unknown'));
        $this->assertFalse($this->validator->isSupported(''));
    }

    public function testValidateWithNetworkType(): void
    {
        // Test Bitcoin testnet address
        $testnetAddress = 'mipcBbFg9gMiCh81Kj8tqqdgoZub1ZJRfn';
        
        $this->assertTrue(
            $this->validator->validate($testnetAddress, 'btc', ['networkType' => 'testnet'])
        );
        
        // Same address should fail on mainnet
        $this->assertFalse(
            $this->validator->validate($testnetAddress, 'btc', ['networkType' => 'prod'])
        );
    }

    public function testCaseInsensitiveCurrencyLookup(): void
    {
        $address = '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2';
        
        $this->assertTrue($this->validator->validate($address, 'BTC'));
        $this->assertTrue($this->validator->validate($address, 'Bitcoin'));
        $this->assertTrue($this->validator->validate($address, 'BITCOIN'));
        $this->assertTrue($this->validator->validate($address, 'btc'));
    }

    public function testERC20TokenAddresses(): void
    {
        $ethereumAddress = '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c';
        
        // ERC-20 tokens use Ethereum addresses
        $erc20Tokens = ['usdc', 'usdt', 'link', 'uni'];
        
        foreach ($erc20Tokens as $token) {
            $this->assertTrue(
                $this->validator->validate($ethereumAddress, $token),
                "ERC-20 token {$token} should accept Ethereum addresses"
            );
        }
    }
}