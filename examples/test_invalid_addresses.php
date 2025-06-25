<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Multicoin\AddressValidator\CurrencyFactory;
use Multicoin\AddressValidator\WalletAddressValidator;

// Create validator instance
$registry = CurrencyFactory::createRegistry();
$validator = new WalletAddressValidator($registry);

// Invalid addresses that should be rejected by the validator
$invalidAddresses = [
    // General invalid formats
    ['currency' => 'BTC', 'address' => '', 'reason' => 'Empty string'],
    ['currency' => 'ETH', 'address' => '0x', 'reason' => 'Only prefix'],
    ['currency' => 'BTC', 'address' => 'not-an-address', 'reason' => 'Invalid format'],
    
    // Bitcoin (BTC) invalid addresses
    ['currency' => 'BTC', 'address' => '1BvBMSEYstWetqTFn5Au4m4GFg7xJaN', 'reason' => 'Too short'],
    ['currency' => 'BTC', 'address' => '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2EXTRA', 'reason' => 'Too long'],
    ['currency' => 'BTC', 'address' => '0BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', 'reason' => 'Invalid version byte'],
    ['currency' => 'BTC', 'address' => '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN0', 'reason' => 'Invalid checksum'],
    ['currency' => 'BTC', 'address' => 'bc1qw508d6qejxtdg4y5r3zarvary0c5xw7kv8f3t4z', 'reason' => 'Invalid bech32'],

    // Ethereum (ETH) invalid addresses
    ['currency' => 'ETH', 'address' => '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395', 'reason' => 'Too short (39 chars)'],
    ['currency' => 'ETH', 'address' => '742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c', 'reason' => 'Missing 0x prefix'],
    ['currency' => 'ETH', 'address' => '0x742d35gc6339c4532ce58b5d3ea8d5a8d6f6395c', 'reason' => 'Invalid hex character (g)'],
    ['currency' => 'ETH', 'address' => '0xGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG', 'reason' => 'All invalid hex characters'],
    
    // Cardano (ADA) invalid addresses
    ['currency' => 'ADA', 'address' => 'addr1invalid', 'reason' => 'Invalid bech32'],
    ['currency' => 'ADA', 'address' => 'stake1uyhhjkjhgktytrsdftyuiohgfcvbnm', 'reason' => 'Wrong address type'],
    ['currency' => 'ADA', 'address' => 'DdzFFzCqrhsP3b6xgZMD4gLgR2c8eiD8YjnZ1yzv2QSPf7M8Q4eRpStMdGpz3L', 'reason' => 'Legacy format not supported'],

    // Solana (SOL) invalid addresses
    ['currency' => 'SOL', 'address' => 'HN7cABqLq46Es1jh92dQQi5kHMKvH4', 'reason' => 'Too short'],
    ['currency' => 'SOL', 'address' => 'HN7cABqLq46Es1jh92dQQi5kHMKvH4EeMEJQo4AmwPPJTOOLONG', 'reason' => 'Too long'],
    ['currency' => 'SOL', 'address' => 'HN7cABqLq46Es1jh92dQQi5kHMKvH4EeMEJQo4AmwPP0', 'reason' => 'Invalid base58 character (0)'],
    ['currency' => 'SOL', 'address' => 'HN7cABqLq46Es1jh92dQQi5kHMKvH4EeMEJQo4AmwPPI', 'reason' => 'Invalid base58 character (I)'],

    // Ripple (XRP) invalid addresses
    ['currency' => 'XRP', 'address' => 'rShort', 'reason' => 'Too short'],
    ['currency' => 'XRP', 'address' => 'sUocf1ixiK2kEHjw9KDvZUWaEUFUkm3d7', 'reason' => 'Invalid prefix (s instead of r)'],
    ['currency' => 'XRP', 'address' => 'rUocf1ixiK2kEHjw9KDvZUWaEUFUkm3d0', 'reason' => 'Invalid base58 character (0)'],
    ['currency' => 'XRP', 'address' => 'rUocf1ixiK2kEHjw9KDvZUWaEUFUkm3dI', 'reason' => 'Invalid base58 character (I)'],

    // Litecoin (LTC) invalid addresses
    ['currency' => 'LTC', 'address' => 'LM2WMpR1Rp6j3Sa59cMXMs1SPzj9eXpG', 'reason' => 'Too short'],
    ['currency' => 'LTC', 'address' => 'BM2WMpR1Rp6j3Sa59cMXMs1SPzj9eXpGc1', 'reason' => 'Invalid version byte (B)'],
    ['currency' => 'LTC', 'address' => 'LM2WMpR1Rp6j3Sa59cMXMs1SPzj9eXpG0', 'reason' => 'Invalid base58 character (0)'],
    ['currency' => 'LTC', 'address' => 'ltc1qw508d6qejxtdg4y5r3zarvary0c5xw7', 'reason' => 'Too short bech32'],

    // Bitcoin Cash (BCH) invalid addresses
    ['currency' => 'BCH', 'address' => 'qpm2qsznhks23z7629mms6s4cwef74vcw', 'reason' => 'Too short'],
    ['currency' => 'BCH', 'address' => 'Qpm2qsznhks23z7629mms6s4cwef74vcwvy22gdx6a', 'reason' => 'Invalid prefix (Q instead of q)'],
    ['currency' => 'BCH', 'address' => 'qpm2qsznhks23z7629mms6s4cwef74vcwvy22gdx6aEXTRA', 'reason' => 'Too long'],
    ['currency' => 'BCH', 'address' => 'bitcoin:qpm2qsznhks23z7629mms6s4cwef74vcwvy22gdx6a', 'reason' => 'Invalid prefix (bitcoin:)'],

    // Monero (XMR) invalid addresses
    ['currency' => 'XMR', 'address' => '4AdUndXHHZ6cfufTMvppY6JwXNouMBzSkbLYfpAV5Usx3skxNgYeYTRJ5BL3e7ckmpce1f7fL1RsJR9HqM9o6z', 'reason' => 'Too short'],
    ['currency' => 'XMR', 'address' => '3AdUndXHHZ6cfufTMvppY6JwXNouMBzSkbLYfpAV5Usx3skxNgYeYTRJ5BL3e7ckmpce1f7fL1RsJR9HqM9o6z12345', 'reason' => 'Invalid version byte (3)'],
    ['currency' => 'XMR', 'address' => '4AdUndXHHZ6cfufTMvppY6JwXNouMBzSkbLYfpAV5Usx3skxNgYeYTRJ5BL3e7ckmpce1f7fL1RsJR9HqM9o6z123450', 'reason' => 'Invalid character (0)'],

    // Tron (TRX) invalid addresses
    ['currency' => 'TRX', 'address' => 'TLyqzVGLV1srkB7dToTAEqgDSfPtXRJZ', 'reason' => 'Too short'],
    ['currency' => 'TRX', 'address' => 'BLyqzVGLV1srkB7dToTAEqgDSfPtXRJZYH', 'reason' => 'Invalid prefix (B instead of T)'],
    ['currency' => 'TRX', 'address' => 'TLyqzVGLV1srkB7dToTAEqgDSfPtXRJZ0H', 'reason' => 'Invalid character (0)'],
    ['currency' => 'TRX', 'address' => 'TLyqzVGLV1srkB7dToTAEqgDSfPtXRJZYHTOOLONG', 'reason' => 'Too long'],

    // Polkadot (DOT) invalid addresses
    ['currency' => 'DOT', 'address' => '16ZL8yLyXv3V3L3z9ofR1ovFLziyXaN1DPq4yffMAZ9c', 'reason' => 'Too short'],
    ['currency' => 'DOT', 'address' => '06ZL8yLyXv3V3L3z9ofR1ovFLziyXaN1DPq4yffMAZ9czzBD', 'reason' => 'Invalid character (0)'],
    ['currency' => 'DOT', 'address' => '16ZL8yLyXv3V3L3z9ofR1ovFLziyXaN1DPq4yffMAZ9czzBDEXTRA', 'reason' => 'Too long'],

    // Dogecoin (DOGE) invalid addresses
    ['currency' => 'DOGE', 'address' => 'DH5yaieqoZN36fDVciNyRueRGvGLR3m', 'reason' => 'Too short'],
    ['currency' => 'DOGE', 'address' => 'AH5yaieqoZN36fDVciNyRueRGvGLR3mr7L', 'reason' => 'Invalid version byte (A)'],
    ['currency' => 'DOGE', 'address' => 'DH5yaieqoZN36fDVciNyRueRGvGLR3mr0L', 'reason' => 'Invalid character (0)'],
    ['currency' => 'DOGE', 'address' => 'DH5yaieqoZN36fDVciNyRueRGvGLR3mr7LEXTRA', 'reason' => 'Too long'],

    // Cross-currency confusion
    ['currency' => 'BTC', 'address' => '0x742d35cc6339c4532ce58b5d3ea8d5a8d6f6395c', 'reason' => 'Ethereum address as Bitcoin'],
    ['currency' => 'ETH', 'address' => '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', 'reason' => 'Bitcoin address as Ethereum'],
    ['currency' => 'SOL', 'address' => 'rUocf1ixiK2kEHjw9KDvZUWaEUFUkm3d7', 'reason' => 'Ripple address as Solana'],
    ['currency' => 'XRP', 'address' => 'HN7cABqLq46Es1jh92dQQi5kHMKvH4EeMEJQo4AmwPPJ', 'reason' => 'Solana address as Ripple'],
    ['currency' => 'LTC', 'address' => 'TLyqzVGLV1srkB7dToTAEqgDSfPtXRJZYH', 'reason' => 'Tron address as Litecoin'],

    // ERC-20 tokens with Bitcoin addresses
    ['currency' => 'USDT', 'address' => '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', 'reason' => 'Bitcoin address for USDT'],
    ['currency' => 'USDC', 'address' => 'DH5yaieqoZN36fDVciNyRueRGvGLR3mr7L', 'reason' => 'Dogecoin address for USDC'],
    ['currency' => 'DAI', 'address' => 'HN7cABqLq46Es1jh92dQQi5kHMKvH4EeMEJQo4AmwPPJ', 'reason' => 'Solana address for DAI'],

    // Edge cases
    ['currency' => 'BTC', 'address' => '                                         ', 'reason' => 'Whitespace only'],
    ['currency' => 'ETH', 'address' => '0X742D35CC6339C4532CE58B5D3EA8D5A8D6F6395C', 'reason' => 'Uppercase 0X prefix'],
    ['currency' => 'SOL', 'address' => '🚀🚀🚀🚀🚀🚀🚀🚀🚀🚀🚀🚀🚀🚀🚀🚀🚀🚀🚀🚀', 'reason' => 'Emoji characters'],
];

echo "Testing invalid cryptocurrency addresses (should be rejected)...\n";
echo str_repeat("=", 90) . "\n";

$correctlyRejectedCount = 0;
$incorrectlyAcceptedCount = 0;
$totalCount = count($invalidAddresses);

foreach ($invalidAddresses as $index => $test) {
    $isValid = $validator->validate($test['address'], $test['currency']);
    $status = $isValid ? '❌ INCORRECTLY ACCEPTED' : '✅ CORRECTLY REJECTED';
    
    echo sprintf("%3d. %-6s | %-20s | %s\n", 
        $index + 1,
        $test['currency'],
        substr($test['address'] ?: '(empty)', 0, 20),
        $status
    );
    echo sprintf("     Reason: %s\n", $test['reason']);
    
    if (!$isValid) {
        $correctlyRejectedCount++;
    } else {
        $incorrectlyAcceptedCount++;
        echo sprintf("     ⚠️  Address: %s\n", $test['address']);
    }
    echo "\n";
}

echo str_repeat("=", 90) . "\n";
echo sprintf("Summary:\n");
echo sprintf("Correctly rejected: %d\n", $correctlyRejectedCount);
echo sprintf("Incorrectly accepted: %d\n", $incorrectlyAcceptedCount);
echo sprintf("Total invalid addresses tested: %d\n", $totalCount);
echo sprintf("Rejection accuracy: %.2f%%\n", ($correctlyRejectedCount / $totalCount) * 100);

if ($correctlyRejectedCount === $totalCount) {
    echo "\n✅ Perfect! All invalid addresses were correctly rejected!\n";
    echo "The validator demonstrates excellent security by rejecting malformed addresses.\n";
} else {
    echo "\n⚠️  Some invalid addresses were incorrectly accepted.\n";
    echo "Review the results above to identify validation rules that may need strengthening.\n";
}