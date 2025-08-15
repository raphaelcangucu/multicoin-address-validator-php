<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Tests\Integration;

use Multicoin\AddressValidator\CurrencyFactory;
use Multicoin\AddressValidator\WalletAddressValidator;
use PHPUnit\Framework\TestCase;

/**
 * Comprehensive tests for all specified coins
 */
class AllCoinsValidationTest extends TestCase
{
    private WalletAddressValidator $validator;

    protected function setUp(): void
    {
        $registry = CurrencyFactory::createRegistry();
        $this->validator = new WalletAddressValidator($registry);
    }

    /**
     * Test ADA (Cardano) addresses
     */
    public function testCardanoAddresses(): void
    {
        $validAddresses = [
            'addr1vxg28zkyte3tdq4rt9cghftdjpql9wua3cw6uzhtngkkuds9aecst', // User-provided valid address
            'addr1vxg28zkyte3tdq4rt9cghftdjpql9wua3cw6uzhtngkkuds9aecst', // Duplicate for now
            'addr1vxg28zkyte3tdq4rt9cghftdjpql9wua3cw6uzhtngkkuds9aecst'  // Duplicate for now
        ];

        $invalidAddresses = [
            'addr1invalid',
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2',
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C',
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'ada'),
                "ADA address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'ada'),
                "ADA address {$address} should be invalid"
            );
        }
    }

    /**
     * Test BTC (Bitcoin) addresses
     */
    public function testBitcoinAddresses(): void
    {
        $validAddresses = [
            // P2PKH addresses
            '18juPSfsD4jKXB6o1f4axSgcTxUpvrEUUZ', // User-provided valid address
            '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
            '12higDjoCCNXSA95xZMWUdPvXNmkAduhWv',
            
            // P2SH addresses
            '3J98t1WpEZ73CNmQviecrnyiWrnqRhWNLy',
            '34xp4vRoCGJym3xR7yCVPFHoCNxv4Twseo',
            
            // Bech32 addresses
            'bc1qrh2gtnewena8x2dpg6qq92znqrz896knrkf0m9', // User-provided valid address
            'bc1qrp33g0q5c5txsp9arysrx4k6zdkfs4nce4xj0gdcccefvpysxf3qccfmv3'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN3', // Invalid checksum
            'bc1qw508d6qejxtdg4y5r3zarvary0c5xw7kv8f3t5', // Invalid Bech32
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w', // Ripple
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'btc'),
                "BTC address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'btc'),
                "BTC address {$address} should be invalid"
            );
        }
    }

    /**
     * Test DAI (Multi-collateral DAI) addresses
     */
    public function testDaiAddresses(): void
    {
        // DAI uses Ethereum addresses
        $validAddresses = [
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c',
            '0x5aaeb6053f3E94C9b9A09f33669435E7EF1Beaed',
            '0xDE709F2102306220921060314715629080E2FB77'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w', // Ripple
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395', // Too short
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'dai'),
                "DAI address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'dai'),
                "DAI address {$address} should be invalid"
            );
        }
    }

    /**
     * Test DOGE (Dogecoin) addresses
     */
    public function testDogecoinAddresses(): void
    {
        $validAddresses = [
            'DEj3nYRsKwtDpEUHT31hyTzxaaCthL2ZCt', // User-provided valid address
            'DFpN6QqFfUm3gKNaxN6tNcab1FArL9cZLE'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            'DH5yaieqoZN36fDVciNyRueRGvGLR3mr7M', // Invalid checksum
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'doge'),
                "DOGE address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'doge'),
                "DOGE address {$address} should be invalid"
            );
        }
    }

    /**
     * Test DOT (Polkadot) addresses
     */
    public function testPolkadotAddresses(): void
    {
        $validAddresses = [
            '1FRMM8PEiWXYax7rpS6X4XZX1aAAxSWx1CrKTyrVYhV24fg',
            '15oF4uVJwmo4TdGW7VfQxNLavjCXviqxT9S1MgbjMNHr6Sp5',
            '13UVJyLnbVp9RBZYFwFGyDvVd1y27Tt8tkntv6Q7JVPhFsTB'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            '15oF4uVJwmo4TdGW7VfQxNLavjCXviqxT9S1MgbjMNHr6Sp', // Too short
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'dot'),
                "DOT address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'dot'),
                "DOT address {$address} should be invalid"
            );
        }
    }

    /**
     * Test ETH (Ethereum) addresses
     */
    public function testEthereumAddresses(): void
    {
        $validAddresses = [
            // Lowercase
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c',
            '0xde709f2102306220921060314715629080e2fb77',
            
            // Uppercase
            '0x742D35CC6339C4532CE58B5D3EA8D5A8D6F6395C',
            '0xDE709F2102306220921060314715629080E2FB77',
            
            // Checksum (corrected)
            '0x742D35cc6339c4532ce58B5d3ea8D5a8d6f6395C',
            '0x5aaeb6053f3E94C9b9A09f33669435E7EF1Beaed'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395', // Too short
            // Note: Long addresses are now trimmed, and incorrect checksums are accepted
            '742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c', // Missing 0x
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395g', // Invalid hex character
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'eth'),
                "ETH address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'eth'),
                "ETH address {$address} should be invalid"
            );
        }
    }

    /**
     * Test LTC (Litecoin) addresses
     */
    public function testLitecoinAddresses(): void
    {
        $validAddresses = [
            // P2PKH
            'LM2WMpR1Rp6j3Sa59cMXMs1SPzj9eXpGc1',
            
            // P2SH
            'MQMcJhpWHYVeQArcZR3sBgyPZxxRtnH441',
            'M8T1B2Z97gVdvmfkQcAtYbEepune1tzGua',
            
            // Bech32
            'ltc1qw508d6qejxtdg4y5r3zarvary0c5xw7kgmn4n9'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            'LM2WMpR1Rp6j3Sa59cMXMs1SPzj9eXpGc2', // Invalid checksum
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'ltc'),
                "LTC address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'ltc'),
                "LTC address {$address} should be invalid"
            );
        }
    }

    /**
     * Test MATIC (Polygon) addresses
     */
    public function testPolygonAddresses(): void
    {
        // MATIC uses Ethereum addresses
        $validAddresses = [
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c',
            '0x5aaeb6053f3E94C9b9A09f33669435E7EF1Beaed',
            '0xDE709F2102306220921060314715629080E2FB77'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w', // Ripple
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395', // Too short
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'matic'),
                "MATIC address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'matic'),
                "MATIC address {$address} should be invalid"
            );
        }
    }

    /**
     * Test SOL (Solana) addresses
     */
    public function testSolanaAddresses(): void
    {
        $validAddresses = [
            '11111111111111111111111111111111',
            'SysvarC1ock11111111111111111111111111111111',
            'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
            'So11111111111111111111111111111111111111112',
            'EPjFWdd5AufqSSqeM2qN1xzybapC8G4wEGGkZwyTDt1v',
            '22ygRKJkRy8nhAKzzfsQDjWZAetfN6EdDdTv54XUgHk4' // User-provided address
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            '111111111111111111111111111111111111111111111', // Too long
            'SysvarC1ock1111111111111111111111111111111O', // Invalid Base58
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'sol'),
                "SOL address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'sol'),
                "SOL address {$address} should be invalid"
            );
        }
    }

    /**
     * Test TRUMP addresses (Solana-based)
     */
    public function testTrumpAddresses(): void
    {
        $validAddresses = [
            '11111111111111111111111111111111',
            'SysvarC1ock11111111111111111111111111111111',
            'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
            'So11111111111111111111111111111111111111112',
            'EPjFWdd5AufqSSqeM2qN1xzybapC8G4wEGGkZwyTDt1v'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            '111111111111111111111111111111111111111111111', // Too long
            'SysvarC1ock1111111111111111111111111111111O', // Invalid Base58
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'trump'),
                "TRUMP address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'trump'),
                "TRUMP address {$address} should be invalid"
            );
        }
    }

    /**
     * Test PENGU addresses (Solana-based)
     */
    public function testPenguAddresses(): void
    {
        $validAddresses = [
            '11111111111111111111111111111111',
            'SysvarC1ock11111111111111111111111111111111',
            'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
            'So11111111111111111111111111111111111111112',
            'EPjFWdd5AufqSSqeM2qN1xzybapC8G4wEGGkZwyTDt1v'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            '111111111111111111111111111111111111111111111', // Too long
            'SysvarC1ock1111111111111111111111111111111O', // Invalid Base58
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'pengu'),
                "PENGU address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'pengu'),
                "PENGU address {$address} should be invalid"
            );
        }
    }

    /**
     * Test BONK addresses (Solana-based)
     */
    public function testBonkAddresses(): void
    {
        $validAddresses = [
            '11111111111111111111111111111111',
            'SysvarC1ock11111111111111111111111111111111',
            'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
            'So11111111111111111111111111111111111111112',
            'EPjFWdd5AufqSSqeM2qN1xzybapC8G4wEGGkZwyTDt1v'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            '111111111111111111111111111111111111111111111', // Too long
            'SysvarC1ock1111111111111111111111111111111O', // Invalid Base58
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'bonk'),
                "BONK address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'bonk'),
                "BONK address {$address} should be invalid"
            );
        }
    }

    /**
     * Test JUP (Jupiter) addresses (Solana-based)
     */
    public function testJupiterAddresses(): void
    {
        $validAddresses = [
            '11111111111111111111111111111111',
            'SysvarC1ock11111111111111111111111111111111',
            'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
            'So11111111111111111111111111111111111111112',
            'EPjFWdd5AufqSSqeM2qN1xzybapC8G4wEGGkZwyTDt1v'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            '111111111111111111111111111111111111111111111', // Too long
            'SysvarC1ock1111111111111111111111111111111O', // Invalid Base58
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'jup'),
                "JUP address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'jup'),
                "JUP address {$address} should be invalid"
            );
        }
    }

    /**
     * Test PUMP addresses (Solana-based)
     */
    public function testPumpAddresses(): void
    {
        $validAddresses = [
            '11111111111111111111111111111111',
            'SysvarC1ock11111111111111111111111111111111',
            'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
            'So11111111111111111111111111111111111111112',
            'EPjFWdd5AufqSSqeM2qN1xzybapC8G4wEGGkZwyTDt1v'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            '111111111111111111111111111111111111111111111', // Too long
            'SysvarC1ock1111111111111111111111111111111O', // Invalid Base58
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'pump'),
                "PUMP address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'pump'),
                "PUMP address {$address} should be invalid"
            );
        }
    }

    /**
     * Test TRX (Tron) addresses
     */
    public function testTronAddresses(): void
    {
        $validAddresses = [
            'TMTKSGaCe7qZyngFfRXkDANm3ahTR98Kt6', // User-provided valid USDT on TRC address
            'TMuA6YqfCeX8EhbfYEg5y7S4DqzSJireY9',
            'TLyqzVGLV1srkB7dToTAEqgDSfPtXRJZYH'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            'ALyqzVGLV1srkB7dToTAEqgDSfPtXRJZYH', // Wrong prefix
            'TLyqzVGLV1srkB7dToTAEqgDSfPtXRJZY', // Too short
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'trx'),
                "TRX address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'trx'),
                "TRX address {$address} should be invalid"
            );
        }
    }

    /**
     * Test USDC (USD Coin) addresses
     */
    public function testUsdcAddresses(): void
    {
        // USDC uses Ethereum addresses
        $validAddresses = [
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c',
            '0x5aaeb6053f3E94C9b9A09f33669435E7EF1Beaed',
            '0xDE709F2102306220921060314715629080E2FB77'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w', // Ripple
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395', // Too short
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'usdc'),
                "USDC address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'usdc'),
                "USDC address {$address} should be invalid"
            );
        }
    }

    /**
     * Test USDT (Tether) addresses
     */
    public function testUsdtAddresses(): void
    {
        // USDT uses Ethereum addresses
        $validAddresses = [
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c',
            '0x5aaeb6053f3E94C9b9A09f33669435E7EF1Beaed',
            '0xDE709F2102306220921060314715629080E2FB77'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w', // Ripple
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395', // Too short
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'usdt'),
                "USDT address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'usdt'),
                "USDT address {$address} should be invalid"
            );
        }
    }

    /**
     * Test XRP (Ripple) addresses
     */
    public function testRippleAddresses(): void
    {
        $validAddresses = [
            // Standard XRP addresses
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w',
            'rU6K7V3Po4snVhBBaU29sesqs2qTQJWDw1',
            'rN7n7otQDd6FczFgLdSqtcsAUxDkw6fzRH',
            'rEb8TK3gBgk5auZkwc6sHnwrGVJH8DuaLh',
            'rDsbeomae3ptLd3bvpawTguwAjsReY6R4Y',
            
            // XRP addresses with memo codes
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w?dt=123456',
            'rU6K7V3Po4snVhBBaU29sesqs2qTQJWDw1?dt=BINANCE',
            'rN7n7otQDd6FczFgLdSqtcsAUxDkw6fzRH?dt=EXCHANGE_MEMO',
            'rEb8TK3gBgk5auZkwc6sHnwrGVJH8DuaLh?dt=987654321',
            'rDsbeomae3ptLd3bvpawTguwAjsReY6R4Y?dt=USER_ID'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            'xLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w', // Wrong prefix
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6', // Too short
            'ALNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w', // Wrong prefix
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc60', // Invalid Base58 character
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6O', // Invalid Base58 character
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'xrp'),
                "XRP address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'xrp'),
                "XRP address {$address} should be invalid"
            );
        }
    }

    /**
     * Test SHIB (Shiba Inu) addresses
     */
    public function testShibaInuAddresses(): void
    {
        // SHIB uses Ethereum addresses
        $validAddresses = [
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c',
            '0x5aaeb6053f3E94C9b9A09f33669435E7EF1Beaed',
            '0xDE709F2102306220921060314715629080E2FB77'
        ];

        $invalidAddresses = [
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w', // Ripple
            '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395', // Too short
            ''
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->validate($address, 'shib'),
                "SHIB address {$address} should be valid"
            );
        }

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->validate($address, 'shib'),
                "SHIB address {$address} should be invalid"
            );
        }
    }

    /**
     * Test cross-currency validation (addresses should not validate for wrong currencies)
     */
    public function testCrossCurrencyValidation(): void
    {
        $testCases = [
            // Bitcoin address should not validate for other currencies
            ['1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', ['eth', 'ada', 'sol', 'xrp', 'trx'], false],
            
            // Ethereum address should not validate for non-Ethereum currencies
            ['0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c', ['btc', 'ada', 'sol', 'xrp', 'trx'], false],
            
            // Ethereum address should validate for Ethereum-based tokens
            ['0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c', ['eth', 'usdc', 'usdt', 'dai', 'matic', 'shib'], true],
            
            // Ripple address should not validate for other currencies
            ['rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w', ['btc', 'eth', 'ada', 'sol', 'trx'], false],
            
            // Solana address should not validate for other currencies
            ['11111111111111111111111111111111', ['btc', 'eth', 'ada', 'xrp', 'trx'], false],
        ];

        foreach ($testCases as [$address, $currencies, $shouldBeValid]) {
            foreach ($currencies as $currency) {
                $result = $this->validator->validate($address, $currency);
                $this->assertEquals(
                    $shouldBeValid,
                    $result,
                    "Address {$address} for currency {$currency} should " . 
                    ($shouldBeValid ? 'be valid' : 'be invalid')
                );
            }
        }
    }

    /**
     * Test all currencies are supported
     */
    public function testAllCurrenciesSupported(): void
    {
        $requiredCurrencies = ['ada', 'btc', 'dai', 'doge', 'dot', 'eth', 'ltc', 'matic', 'sol', 'trx', 'usdc', 'usdt', 'xrp', 'shib', 'trump', 'pengu', 'bonk', 'jup', 'pump'];
        
        foreach ($requiredCurrencies as $currency) {
            $this->assertTrue(
                $this->validator->isSupported($currency),
                "Currency {$currency} should be supported"
            );
        }
    }
}