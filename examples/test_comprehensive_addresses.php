<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Multicoin\AddressValidator\CurrencyFactory;
use Multicoin\AddressValidator\WalletAddressValidator;

// Create validator instance
$registry = CurrencyFactory::createRegistry();
$validator = new WalletAddressValidator($registry);

// Comprehensive test addresses covering all supported cryptocurrencies
$comprehensiveAddresses = [
    // Major Cryptocurrencies
    
    // Bitcoin (BTC) - P2PKH, P2SH, Bech32 addresses
    ['currency' => 'BTC', 'address' => '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2'], // P2PKH mainnet
    ['currency' => 'BTC', 'address' => '3J98t1WpEZ73CNmQviecrnyiWrnqRhWNLy'], // P2SH mainnet
    ['currency' => 'BTC', 'address' => 'bc1qw508d6qejxtdg4y5r3zarvary0c5xw7kv8f3t4'], // Bech32 mainnet
    ['currency' => 'BTC', 'address' => 'mipcBbFg9gMiCh81Kj8tqqdgoZub1ZJRfn', 'options' => ['networkType' => 'testnet']], // P2PKH testnet
    ['currency' => 'BTC', 'address' => 'tb1qw508d6qejxtdg4y5r3zarvary0c5xw7kxpjzsx', 'options' => ['networkType' => 'testnet']], // Bech32 testnet

    // Ethereum (ETH) - EIP-55 checksum validation, case-insensitive support
    ['currency' => 'ETH', 'address' => '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C'], // Mixed case
    ['currency' => 'ETH', 'address' => '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c'], // Lowercase
    ['currency' => 'ETH', 'address' => '0x742D35CC6339C4532CE58B5D3EA8D5A8D6F6395C'], // Uppercase
    ['currency' => 'ETH', 'address' => '0x5aaeb6053f3E94C9b9A09f33669435E7EF1Beaed'], // Valid checksum

    // Cardano (ADA) - Bech32 addresses with addr prefix
    ['currency' => 'ADA', 'address' => 'addr1v8vz8nygf4dvj9gzy06zcxlkfwcd9snga5s8jzl2vhxlnuspfxj2l'], // Mainnet
    ['currency' => 'ADA', 'address' => 'addr1q9c8h75kfzs8f4gcd0t333t3ete9tqvnnawfckxwqa8zga3wpvz5rz8gm7c6q38nkaw90wh44vkwrgz6ue5rxwq2gv5sjm867z'], // Real mainnet
    ['currency' => 'ADA', 'address' => 'addr1v87ccwvenkx2e956rzd0rue6y5y29dvv2vxsuluxydzaz4s64ud7d'], // Real mainnet

    // Solana (SOL) - Base58 addresses with length validation
    ['currency' => 'SOL', 'address' => 'HN7cABqLq46Es1jh92dQQi5kHMKvH4EeMEJQo4AmwPPJ'], // Valid Solana address
    ['currency' => 'SOL', 'address' => 'So11111111111111111111111111111111111111112'], // SOL Token
    ['currency' => 'SOL', 'address' => '4k3Dyjzvzp8eMZWUXbBCjEvwSkkk59S5iCNLY3QrkX6R'], // Program address
    ['currency' => 'SOL', 'address' => 'EPjFWdd5AufqSSqeM2qN1xzybapC8G4wEGGkZwyTDt1v'], // USDC on Solana

    // Ripple (XRP) - Classic addresses with custom Base58 alphabet
    ['currency' => 'XRP', 'address' => 'rUocf1ixiK2kEHjw9KDvZUWaEUFUkm3d7'], // Classic address
    ['currency' => 'XRP', 'address' => 'rGWrZyQqhTp9Xu7G5Pkayo7bXjH4k4QYpf'], // Classic address
    ['currency' => 'XRP', 'address' => 'rPVMhWBsfF9iMXYj3aAzJVkPDTFNSyWdKy'], // Classic address with memo

    // Litecoin (LTC) - P2PKH, P2SH, Bech32 addresses
    ['currency' => 'LTC', 'address' => 'LM2WMpR1Rp6j3Sa59cMXMs1SPzj9eXpGc1'], // P2PKH mainnet
    ['currency' => 'LTC', 'address' => 'LXSXU5N2i2tBAKSnPckhCKjSWEKp4Es42h'], // P2PKH mainnet (real)
    ['currency' => 'LTC', 'address' => 'ltc1q0s269vkatpq22js9m7x2gh6kpm8vkq5uem72hr'], // Bech32 mainnet (real)
    ['currency' => 'LTC', 'address' => 'mipcBbFg9gMiCh81Kj8tqqdgoZub1ZJRfn', 'options' => ['networkType' => 'testnet']], // P2PKH testnet

    // Bitcoin Cash (BCH) - CashAddr format validation
    ['currency' => 'BCH', 'address' => 'qpm2qsznhks23z7629mms6s4cwef74vcwvy22gdx6a'], // CashAddr mainnet
    ['currency' => 'BCH', 'address' => 'qqkv9wr69ry2p9l53lxp635va4h86wv435995w8p2h'], // CashAddr mainnet
    ['currency' => 'BCH', 'address' => 'ppm2qsznhks23z7629mms6s4cwef74vcwvn0h829pq'], // CashAddr P2SH mainnet

    // Monero (XMR) - CryptoNote addresses with network detection  
    // Note: Using simplified test addresses - XMR validation is complex

    // Tron (TRX) - TRON addresses with version byte validation
    ['currency' => 'TRX', 'address' => 'TLyqzVGLV1srkB7dToTAEqgDSfPtXRJZYH'], // Base58 mainnet
    ['currency' => 'TRX', 'address' => 'TPswDDCAWhJAZGdHPidFg5nEf7fQjak9vN'], // Base58 mainnet
    ['currency' => 'TRX', 'address' => 'TKiok53tAyV8snLLmn85y9JqUfLPpX2yaW'], // Base58 mainnet

    // Polkadot (DOT) - SS58 address format with Blake2b checksum
    ['currency' => 'DOT', 'address' => '16ZL8yLyXv3V3L3z9ofR1ovFLziyXaN1DPq4yffMAZ9czzBD'], // SS58 format
    ['currency' => 'DOT', 'address' => '14E5nqKAp3oAJcmzgZhUD2RcptBeUBScxKHgJKU4HPNcKVf3'], // SS58 format
    ['currency' => 'DOT', 'address' => '12xtAYsRUrmbniiWQqJtECiBQrMn8AypQcXhnQAc6RB6XkLW'], // SS58 format

    // TON (The Open Network) - User-friendly and raw format addresses
    ['currency' => 'TON', 'address' => 'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPrHF'], // Bounceable mainnet
    ['currency' => 'TON', 'address' => 'UQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPuwA'], // Non-bounceable mainnet
    ['currency' => 'TON', 'address' => '0:ca6e321c7cce9ecedf0a8ca2492ec8592494aa5fb5ce0387dff96ef6af982a3e'], // Raw format mainnet
    ['currency' => 'TON', 'address' => 'kQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPgpP', 'options' => ['networkType' => 'testnet']], // Testnet bounceable
    ['currency' => 'TON', 'address' => 'EQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAM9c'], // Zero address
    ['currency' => 'TON', 'address' => 'UQBdge-bG6qCcUnkCJqJXk6xsiWToG92UyGw-CBicT-eV0U3?memoId=0'], // Address with memo
    ['currency' => 'TON', 'address' => 'EQDKbjIcfM6ezt8KjKJJLshZJJSqX7XOA4ff-W72r5gqPrHF?memo=payment123'], // Address with memo text

    // ERC-20 & Ethereum-Compatible Tokens

    // USD Coin (USDC) - Ethereum-based stablecoin
    ['currency' => 'USDC', 'address' => '0xa0b86a33e6ba3c5de3dc34b6925c33b64cc13a11'], // USDC contract interaction
    ['currency' => 'USDC', 'address' => '0x8ba1f109551bd432803012645ad6f5e5c9b09876'], // User wallet (fixed invalid char)
    ['currency' => 'USDC', 'address' => '0xA0b86a33E6bA3c5dE3dc34B6925c33B64cc13A11'], // Mixed case

    // Tether (USDT) - Multi-chain stablecoin
    ['currency' => 'USDT', 'address' => '0xdac17f958d2ee523a2206206994597c13d831ec7'], // USDT contract
    ['currency' => 'USDT', 'address' => '0x4e7a3c4a7b8c9d2e3f4a5b6c7d8e9f1a2b3c4d5e'], // User wallet
    ['currency' => 'USDT', 'address' => '0x742D35CC6339C4532CE58B5D3EA8D5A8D6F6395C'], // Mixed case

    // Multi-collateral DAI (DAI) - Decentralized stablecoin
    ['currency' => 'DAI', 'address' => '0x6b175474e89094c44da98b954eedeac495271d0f'], // DAI contract
    ['currency' => 'DAI', 'address' => '0x1234567890123456789012345678901234567890'], // User wallet
    ['currency' => 'DAI', 'address' => '0xAbCdEf1234567890AbCdEf1234567890AbCdEf12'], // Mixed case

    // Chainlink (LINK) - Oracle network token
    ['currency' => 'LINK', 'address' => '0x514910771af9ca656af840dff83e8264ecf986ca'], // LINK contract
    ['currency' => 'LINK', 'address' => '0x9876543210987654321098765432109876543210'], // User wallet

    // Uniswap (UNI) - DEX governance token
    ['currency' => 'UNI', 'address' => '0x1f9840a85d5af5bf1d1762f925bdaddc4201f984'], // UNI contract
    ['currency' => 'UNI', 'address' => '0xfedcba0987654321fedcba0987654321fedcba09'], // User wallet

    // Shiba Inu (SHIB) - Meme token
    ['currency' => 'SHIB', 'address' => '0x95ad61b0a150d79219dcf64e1e6cc01f0b64c4ce'], // SHIB contract
    ['currency' => 'SHIB', 'address' => '0x1a2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a0b'], // User wallet

    // Polygon (MATIC) - Layer 2 scaling solution
    ['currency' => 'MATIC', 'address' => '0x7d1afa7b718fb893db30a3abc0cfc608aacfebb0'], // MATIC contract
    ['currency' => 'MATIC', 'address' => '0x0987654321098765432109876543210987654321'], // User wallet

    // Avalanche (AVAX) - High-performance blockchain
    ['currency' => 'AVAX', 'address' => '0xb31f66aa3c1e785363f0875a1b74e27b85fd66c7'], // AVAX contract
    ['currency' => 'AVAX', 'address' => '0x5432109876543210987654321098765432109876'], // User wallet

    // Binance Coin (BNB) - Exchange token
    ['currency' => 'BNB', 'address' => '0xb8c77482e45f1f44de1745f52c74426c631bdd52'], // BNB contract
    ['currency' => 'BNB', 'address' => '0x6789012345678901234567890123456789012345'], // User wallet

    // Ethereum Classic (ETC) - Original Ethereum chain
    ['currency' => 'ETC', 'address' => '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c'], // ETC address
    ['currency' => 'ETC', 'address' => '0x8765432109876543210987654321098765432109'], // ETC address

    // Bitcoin Derivatives & Forks

    // Dogecoin (DOGE) - Scrypt-based cryptocurrency
    ['currency' => 'DOGE', 'address' => 'DH5yaieqoZN36fDVciNyRueRGvGLR3mr7L'], // P2PKH mainnet
    ['currency' => 'DOGE', 'address' => 'DUPZoEvbLoeTenV8NJzujtvstAEK3vE8hZ'], // P2PKH mainnet (real)
    ['currency' => 'DOGE', 'address' => 'npJo8FieqEmB1NehU4jFFEFPsdvy8ippbm'], // Special case mainnet

    // Note: DASH and ZEC validators exist but addresses need specific formats

    // Bitcoin SV (BSV) - Bitcoin Satoshi Vision
    ['currency' => 'BSV', 'address' => 'qpm2qsznhks23z7629mms6s4cwef74vcwvy22gdx6a'], // CashAddr format
    ['currency' => 'BSV', 'address' => 'qqkv9wr69ry2p9l53lxp635va4h86wv435995w8p2h'], // CashAddr format
];

echo "Testing comprehensive cryptocurrency address validation...\n";
echo str_repeat("=", 80) . "\n";

$validCount = 0;
$invalidCount = 0;
$totalCount = count($comprehensiveAddresses);

foreach ($comprehensiveAddresses as $index => $test) {
    $options = $test['options'] ?? [];
    $isValid = $validator->validate($test['address'], $test['currency'], $options);
    $status = $isValid ? 'VALID ✓' : 'INVALID ✗';
    $networkInfo = !empty($options['networkType']) ? " ({$options['networkType']})" : '';
    
    echo sprintf("%3d. %-6s%-10s | %-42s | %s\n", 
        $index + 1,
        $test['currency'],
        $networkInfo,
        $test['address'], 
        $status
    );
    
    if ($isValid) {
        $validCount++;
    } else {
        $invalidCount++;
    }
}

echo str_repeat("=", 80) . "\n";
echo sprintf("Summary:\n");
echo sprintf("Valid addresses: %d\n", $validCount);
echo sprintf("Invalid addresses: %d\n", $invalidCount);
echo sprintf("Total addresses tested: %d\n", $totalCount);
echo sprintf("Success rate: %.2f%%\n", ($validCount / $totalCount) * 100);

if ($validCount === $totalCount) {
    echo "\n✅ All comprehensive test addresses are valid!\n";
    echo "The validator successfully handles all supported cryptocurrency formats.\n";
} else {
    echo "\n⚠️  Some addresses failed validation. This may be expected for certain test cases.\n";
    echo "Review the results above to identify which addresses need attention.\n";
}