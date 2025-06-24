<?php

require_once 'vendor/autoload.php';

use Multicoin\AddressValidator\WalletAddressValidator;
use Multicoin\AddressValidator\CurrencyRegistry;
use Multicoin\AddressValidator\CurrencyFactory;

// Initialize the validator
$currencyRegistry = CurrencyFactory::createRegistry();
$validator = new WalletAddressValidator($currencyRegistry);

// Comprehensive test addresses based on the provided examples
$testAddresses = [
    // SOL (Solana) - 44-character base58 strings
    ['currency' => 'SOL', 'network' => 'Solana', 'address' => '7x9bYvWqF4fZ3z8kWnW5fX8p3Z5z6wW9q2r3s4t5u6v7'],
    ['currency' => 'SOL', 'network' => 'Solana', 'address' => 'Gp2kWqN4bZ8vX9r3y5z7t6u8v9w2x3y4z5t6u7v8w9x'],
    ['currency' => 'SOL', 'network' => 'Solana', 'address' => 'Bz3mWqP5rZ8vX9r3y5z7t6u8v9w2x3y4z5t6u7v8w9x'],
    ['currency' => 'SOL', 'network' => 'Solana', 'address' => 'Jk4nWqR6tZ8vX9r3y5z7t6u8v9w2x3y4z5t6u7v8w9x'],
    ['currency' => 'SOL', 'network' => 'Solana', 'address' => 'Fm5pWqT7uZ8vX9r3y5z7t6u8v9w2x3y4z5t6u7v8w9x'],

    // USDT (Tether) - Ethereum Network (ERC-20)
    ['currency' => 'USDT', 'network' => 'Ethereum', 'address' => '0x1234567890abcdef1234567890abcdef12345678'],
    ['currency' => 'USDT', 'network' => 'Ethereum', 'address' => '0xabcdef1234567890abcdef1234567890abcdef12'],
    ['currency' => 'USDT', 'network' => 'Ethereum', 'address' => '0x7890abcdef1234567890abcdef1234567890abcd'],
    ['currency' => 'USDT', 'network' => 'Ethereum', 'address' => '0x4567890abcdef1234567890abcdef1234567890'],
    ['currency' => 'USDT', 'network' => 'Ethereum', 'address' => '0x0abcdef1234567890abcdef1234567890abcdef1'],

    // USDT - Polygon Network (MATIC)
    ['currency' => 'USDT', 'network' => 'Polygon', 'address' => '0x234567890abcdef1234567890abcdef123456789'],
    ['currency' => 'USDT', 'network' => 'Polygon', 'address' => '0xdef1234567890abcdef1234567890abcdef1234'],
    ['currency' => 'USDT', 'network' => 'Polygon', 'address' => '0x890abcdef1234567890abcdef1234567890abcde'],
    ['currency' => 'USDT', 'network' => 'Polygon', 'address' => '0x567890abcdef1234567890abcdef1234567890ab'],
    ['currency' => 'USDT', 'network' => 'Polygon', 'address' => '0x1abcdef1234567890abcdef1234567890abcdef12'],

    // USDT - Tron Network (TRC-20)
    ['currency' => 'USDT', 'network' => 'Tron', 'address' => 'TAbcDefGhijKlmnopQrStuVwXyZ123456789'],
    ['currency' => 'USDT', 'network' => 'Tron', 'address' => 'TPqRsTuVwXyZ123456789AbcDefGhijKlmno'],
    ['currency' => 'USDT', 'network' => 'Tron', 'address' => 'TWxYz123456789AbcDefGhijKlmnopQrStuV'],
    ['currency' => 'USDT', 'network' => 'Tron', 'address' => 'TKlmnopQrStuVwXyZ123456789AbcDefGhij'],
    ['currency' => 'USDT', 'network' => 'Tron', 'address' => 'TStuVwXyZ123456789AbcDefGhijKlmnopQr'],

    // USDT - Solana Network
    ['currency' => 'USDT', 'network' => 'Solana', 'address' => '8y2cXrQ6uA9wB3z7t8v9w2x3y4z5t6u7v8w9x2y3z4'],
    ['currency' => 'USDT', 'network' => 'Solana', 'address' => 'Hq3mXrP7tB9wC3z8u9v2x3y4z5t6u7v8w9x2y3z4t'],
    ['currency' => 'USDT', 'network' => 'Solana', 'address' => 'Cz4nXrR8uC9wD3z7t8v9w2x3y4z5t6u7v8w9x2y3'],
    ['currency' => 'USDT', 'network' => 'Solana', 'address' => 'Kk5pXrT9uE9wF3z7t8v9w2x3y4z5t6u7v8w9x2y3'],
    ['currency' => 'USDT', 'network' => 'Solana', 'address' => 'Gn6rXrU2tG9wH3z7t8v9w2x3y4z5t6u7v8w9x2y3'],

    // XRP (Ripple) - 25-35 character base58 strings starting with r
    ['currency' => 'XRP', 'network' => 'Ripple', 'address' => 'rAbcDefGhijKlmnopQrStuVwXyZ123456'],
    ['currency' => 'XRP', 'network' => 'Ripple', 'address' => 'rPqRsTuVwXyZ123456AbcDefGhijKlmno'],
    ['currency' => 'XRP', 'network' => 'Ripple', 'address' => 'rWxYz123456AbcDefGhijKlmnopQrStuV'],
    ['currency' => 'XRP', 'network' => 'Ripple', 'address' => 'rKlmnopQrStuVwXyZ123456AbcDefGhij'],
    ['currency' => 'XRP', 'network' => 'Ripple', 'address' => 'rStuVwXyZ123456AbcDefGhijKlmnopQr'],

    // LTC (Litecoin) - 26-35 character base58 strings starting with L or M
    ['currency' => 'LTC', 'network' => 'Litecoin', 'address' => 'LAbcDefGhijKlmnopQrStuVwXyZ123456'],
    ['currency' => 'LTC', 'network' => 'Litecoin', 'address' => 'LPqRsTuVwXyZ123456AbcDefGhijKlmno'],
    ['currency' => 'LTC', 'network' => 'Litecoin', 'address' => 'LWxYz123456AbcDefGhijKlmnopQrStuV'],
    ['currency' => 'LTC', 'network' => 'Litecoin', 'address' => 'MKlmnopQrStuVwXyZ123456AbcDefGhij'],
    ['currency' => 'LTC', 'network' => 'Litecoin', 'address' => 'MStuVwXyZ123456AbcDefGhijKlmnopQr'],

    // TRX (Tron) - 34-character base58 strings starting with T
    ['currency' => 'TRX', 'network' => 'Tron', 'address' => 'TDefGhijKlmnopQrStuVwXyZ123456789Abc'],
    ['currency' => 'TRX', 'network' => 'Tron', 'address' => 'TTuVwXyZ123456789AbcDefGhijKlmnopQrS'],
    ['currency' => 'TRX', 'network' => 'Tron', 'address' => 'TYz123456789AbcDefGhijKlmnopQrStuVwX'],
    ['currency' => 'TRX', 'network' => 'Tron', 'address' => 'TnopQrStuVwXyZ123456789AbcDefGhijKlm'],
    ['currency' => 'TRX', 'network' => 'Tron', 'address' => 'TVwXyZ123456789AbcDefGhijKlmnopQrStu'],

    // BTC (Bitcoin) - Various formats
    ['currency' => 'BTC', 'network' => 'Bitcoin', 'address' => '1AbcDefGhijKlmnopQrStuVwXyZ123456'],
    ['currency' => 'BTC', 'network' => 'Bitcoin', 'address' => '3PqRsTuVwXyZ123456AbcDefGhijKlmno'],
    ['currency' => 'BTC', 'network' => 'Bitcoin', 'address' => 'bc1qwxz123456789abcdefghijklmnopqrs'],
    ['currency' => 'BTC', 'network' => 'Bitcoin', 'address' => '1KlmnopQrStuVwXyZ123456AbcDefGhij'],
    ['currency' => 'BTC', 'network' => 'Bitcoin', 'address' => '3StuVwXyZ123456AbcDefGhijKlmnopQr'],

    // ETH (Ethereum) - 42-character hexadecimal strings starting with 0x
    ['currency' => 'ETH', 'network' => 'Ethereum', 'address' => '0x34567890abcdef1234567890abcdef1234567890'],
    ['currency' => 'ETH', 'network' => 'Ethereum', 'address' => '0x1234567890abcdef1234567890abcdef12345678'],
    ['currency' => 'ETH', 'network' => 'Ethereum', 'address' => '0xabcdef1234567890abcdef1234567890abcdef12'],
    ['currency' => 'ETH', 'network' => 'Ethereum', 'address' => '0x7890abcdef1234567890abcdef1234567890abcd'],
    ['currency' => 'ETH', 'network' => 'Ethereum', 'address' => '0x4567890abcdef1234567890abcdef1234567890'],

    // DOGE (Dogecoin) - 34-character base58 strings starting with D
    ['currency' => 'DOGE', 'network' => 'Dogecoin', 'address' => 'DAbcDefGhijKlmnopQrStuVwXyZ123456'],
    ['currency' => 'DOGE', 'network' => 'Dogecoin', 'address' => 'DPqRsTuVwXyZ123456AbcDefGhijKlmno'],
    ['currency' => 'DOGE', 'network' => 'Dogecoin', 'address' => 'DWxYz123456AbcDefGhijKlmnopQrStuV'],
    ['currency' => 'DOGE', 'network' => 'Dogecoin', 'address' => 'DKlmnopQrStuVwXyZ123456AbcDefGhij'],
    ['currency' => 'DOGE', 'network' => 'Dogecoin', 'address' => 'DStuVwXyZ123456AbcDefGhijKlmnopQr'],

    // ADA (Cardano) - 103-104 character base58 strings starting with addr1
    ['currency' => 'ADA', 'network' => 'Cardano', 'address' => 'addr1q8abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef'],
    ['currency' => 'ADA', 'network' => 'Cardano', 'address' => 'addr1q9ghijk1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef'],
    ['currency' => 'ADA', 'network' => 'Cardano', 'address' => 'addr1qxklmno1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef'],
    ['currency' => 'ADA', 'network' => 'Cardano', 'address' => 'addr1qypqrst1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef'],
    ['currency' => 'ADA', 'network' => 'Cardano', 'address' => 'addr1qwuvwx1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef1234567890abcdef'],

    // MATIC (Polygon) - Same as Ethereum format
    ['currency' => 'MATIC', 'network' => 'Polygon', 'address' => '0x67890abcdef1234567890abcdef1234567890abc'],
    ['currency' => 'MATIC', 'network' => 'Polygon', 'address' => '0xdef1234567890abcdef1234567890abcdef1234'],
    ['currency' => 'MATIC', 'network' => 'Polygon', 'address' => '0x890abcdef1234567890abcdef1234567890abcde'],
    ['currency' => 'MATIC', 'network' => 'Polygon', 'address' => '0x234567890abcdef1234567890abcdef123456789'],
    ['currency' => 'MATIC', 'network' => 'Polygon', 'address' => '0x1abcdef1234567890abcdef1234567890abcdef12'],

    // DOT (Polkadot) - 47-48 character base58 strings starting with 1
    ['currency' => 'DOT', 'network' => 'Polkadot', 'address' => '1AbcDefGhijKlmnopQrStuVwXyZ1234567890abcdef123456'],
    ['currency' => 'DOT', 'network' => 'Polkadot', 'address' => '1PqRsTuVwXyZ1234567890AbcDefGhijKlmnopqrstuv'],
    ['currency' => 'DOT', 'network' => 'Polkadot', 'address' => '1WxYz1234567890AbcDefGhijKlmnopQrStuVwxyzab'],
    ['currency' => 'DOT', 'network' => 'Polkadot', 'address' => '1KlmnopQrStuVwXyZ1234567890AbcDefGhijKlmnopq'],
    ['currency' => 'DOT', 'network' => 'Polkadot', 'address' => '1StuVwXyZ1234567890AbcDefGhijKlmnopQrStuVwx'],

    // USDC (USD Coin) - ERC-20 format
    ['currency' => 'USDC', 'network' => 'Ethereum', 'address' => '0x7890abcdef1234567890abcdef1234567890abcdef'],
    ['currency' => 'USDC', 'network' => 'Ethereum', 'address' => '0x4567890abcdef1234567890abcdef1234567890ab'],
    ['currency' => 'USDC', 'network' => 'Ethereum', 'address' => '0x1abcdef1234567890abcdef1234567890abcdef123'],
    ['currency' => 'USDC', 'network' => 'Ethereum', 'address' => '0xdef1234567890abcdef1234567890abcdef123456'],
    ['currency' => 'USDC', 'network' => 'Ethereum', 'address' => '0x234567890abcdef1234567890abcdef1234567890'],

    // DAI - ERC-20 format
    ['currency' => 'DAI', 'network' => 'Ethereum', 'address' => '0x0abcdef1234567890abcdef1234567890abcdef123'],
    ['currency' => 'DAI', 'network' => 'Ethereum', 'address' => '0x567890abcdef1234567890abcdef1234567890abc'],
    ['currency' => 'DAI', 'network' => 'Ethereum', 'address' => '0x890abcdef1234567890abcdef1234567890abcdef1'],
    ['currency' => 'DAI', 'network' => 'Ethereum', 'address' => '0x1234567890abcdef1234567890abcdef12345678a'],
    ['currency' => 'DAI', 'network' => 'Ethereum', 'address' => '0xabcdef1234567890abcdef1234567890abcdef123'],

    // SHIB (Shiba Inu) - ERC-20 format
    ['currency' => 'SHIB', 'network' => 'Ethereum', 'address' => '0x34567890abcdef1234567890abcdef1234567890ab'],
    ['currency' => 'SHIB', 'network' => 'Ethereum', 'address' => '0x7890abcdef1234567890abcdef1234567890abcdef'],
    ['currency' => 'SHIB', 'network' => 'Ethereum', 'address' => '0xdef1234567890abcdef1234567890abcdef1234567'],
    ['currency' => 'SHIB', 'network' => 'Ethereum', 'address' => '0x1abcdef1234567890abcdef1234567890abcdef1234'],
    ['currency' => 'SHIB', 'network' => 'Ethereum', 'address' => '0x4567890abcdef1234567890abcdef1234567890abc'],
];

// Currency mapping for the validator (based on CurrencyFactory definitions)
$currencyMap = [
    'USDT' => 'usdt', // Tether
    'LTC' => 'ltc',   // LiteCoin
    'TRX' => 'trx',   // Tron
    'SOL' => 'sol',   // Solana
    'XRP' => 'xrp',   // Ripple
    'ETH' => 'eth',   // Ethereum
    'USDC' => 'usdc', // USD Coin
    'BTC' => 'btc',   // Bitcoin
    'DOGE' => 'doge', // Dogecoin
    'ADA' => 'ada',   // Cardano
    'MATIC' => 'matic', // Polygon
    'DOT' => 'dot',   // Polkadot
    'DAI' => 'dai',   // Dai
    'SHIB' => 'shib', // Shiba Inu
];

echo "Testing comprehensive cryptocurrency addresses...\n";
echo str_repeat("=", 80) . "\n";

$validCount = 0;
$invalidCount = 0;
$unsupportedCount = 0;

foreach ($testAddresses as $index => $testData) {
    $currency = $testData['currency'];
    $network = $testData['network'];
    $address = $testData['address'];
    
    echo sprintf("%d. Currency: %s, Network: %s\n", $index + 1, $currency, $network);
    echo "   Address: $address\n";
    
    try {
        // Map currency to validator currency
        $validatorCurrency = $currencyMap[$currency] ?? strtolower($currency);
        
        // Check if currency is supported
        if (!$validator->isSupported($validatorCurrency)) {
            echo "   Result: UNSUPPORTED - Currency '$validatorCurrency' not supported by validator\n";
            $unsupportedCount++;
        } else {
            $isValid = $validator->validate($address, $validatorCurrency);
            
            if ($isValid) {
                echo "   Result: VALID ✓\n";
                $validCount++;
            } else {
                echo "   Result: INVALID ✗\n";
                $invalidCount++;
            }
        }
    } catch (Exception $e) {
        echo "   Result: ERROR - " . $e->getMessage() . "\n";
        $invalidCount++;
    }
    
    echo "\n";
}

echo str_repeat("=", 80) . "\n";
echo "Summary:\n";
echo "Valid addresses: $validCount\n";
echo "Invalid addresses: $invalidCount\n";
echo "Unsupported currencies: $unsupportedCount\n";
echo "Total addresses tested: " . count($testAddresses) . "\n";