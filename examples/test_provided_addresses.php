<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Multicoin\AddressValidator\CurrencyFactory;
use Multicoin\AddressValidator\WalletAddressValidator;

// Create validator instance
$registry = CurrencyFactory::createRegistry();
$validator = new WalletAddressValidator($registry);

// Real cryptocurrency addresses provided by user
$providedAddresses = [
    ['currency' => 'XRP', 'address' => 'rffAKFjvGyNdLiXE2zBQtBFdKnGMgqCVjs'],
    ['currency' => 'LTC', 'address' => 'LXSXU5N2i2tBAKSnPckhCKjSWEKp4Es42h'],
    ['currency' => 'SOL', 'address' => 'HGEgzAFVbyxzTuGrvedrkaoDxHGT7SCvD2PRzYNjGnGW'],
    ['currency' => 'SOL', 'address' => '2RTywcLh9WzbqxrhJY8koYWzTAe2FJVZhvSkM1fr6TbQ'],
    ['currency' => 'TRX', 'address' => 'TKiok53tAyV8snLLmn85y9JqUfLPpX2yaW'],
    ['currency' => 'SOL', 'address' => 'ENrsoq5D7sF4vb6c7h7z6spueaKznBj5cxzoAxjkjf7u'],
    ['currency' => 'SOL', 'address' => '8jc49N3gSnXeqr11YKrehRWRNdSnmXLntFinPGvB7D5p'],
    ['currency' => 'USDC', 'address' => '0xa5e872b8858a3c19854ef81819ea8fe9ed742352'],
    ['currency' => 'USDT', 'address' => '0x9feeccd8818732d4d9626871610dc5ce22e81e8bb'],
    ['currency' => 'LTC', 'address' => 'LVCuXJh7egRCG1oUVEWA5BJoUNgXmbyZ3F'],
    ['currency' => 'LTC', 'address' => 'LLatPbP6qhrweuNXEFo1S7XMwqyxo9RWUq'],
    ['currency' => 'ADA', 'address' => 'addr1q9c8h75kfzs8f4gcd0t333t3ete9tqvnnawfckxwqa8zga3wpvz5rz8gm7c6q38nkaw90wh44vkwrgz6ue5rxwq2gv5sjm867z'],
    ['currency' => 'LTC', 'address' => 'LKh5HrfXsS77gvo9jKdjYhq8ktShuQeaqh'],
    ['currency' => 'LTC', 'address' => 'LL5FeSqU4i3sXtUPZdonbKsA5xEJDsJooz'],
    ['currency' => 'XRP', 'address' => 'rwyQp3eC5j6AumcptZhfmiXAykpeswZKeJ'],
    ['currency' => 'XRP', 'address' => 'rhDD5uKVEkcvJkSNZQXiVwuPzWTuioX6Vd'],
    ['currency' => 'SOL', 'address' => '63YzJgEe8qUojzbNTQkByP7nQxXDhMXW77Kx2j7fV1XR'],
    ['currency' => 'SOL', 'address' => '75n5SsxM46uBYAgar3Qj7dGsEQqXJncHo25Ws9ecvRGg'],
    ['currency' => 'LTC', 'address' => 'LT398dBWCV5uYLqWJrzPyaBBVE6Tmg6CXu'],
    ['currency' => 'LTC', 'address' => 'ltc1q0s269vkatpq22js9m7x2gh6kpm8vkq5uem72hr'],
    ['currency' => 'TRX', 'address' => 'TU6nX2ETeU8SrADMhotQpw4qMnjgP3bmAi'],
    ['currency' => 'TRX', 'address' => 'THQuQyK9HGaMtqvvSdZdwLuQmFovD4VvWR'],
    ['currency' => 'SOL', 'address' => 'FqY3eBWc9AsMacywLTPisrkH7KnKZSpDPehc5vqDcPRY'],
    ['currency' => 'LTC', 'address' => 'Lhq2eYaG1QJeDRpgW6N2tzhh7Q1qXBArF3'],
    ['currency' => 'LTC', 'address' => 'LN8WAb8HxUu5dwSHmmFMwpoa3FBRRA99bP'],
    ['currency' => 'DOGE', 'address' => 'DUPZoEvbLoeTenV8NJzujtvstAEK3vE8hZ'],
    ['currency' => 'MATIC', 'address' => '0xdb1b31e4365e3b3753b7a6e39341f915e805ce09'],
    ['currency' => 'SOL', 'address' => '6UuCvNnRiq9eWbeRiQehk2dn2piHZnzMBmHbTVdYPJXW'],
    ['currency' => 'BTC', 'address' => 'bc1q0s269vkatpq22js9m7x2gh6kpm8vkq5ua8yw0n'],
    ['currency' => 'SOL', 'address' => '2Ko4PXw6RLN2KAeDNZTkM2ew6EzXMRetqVK7tEdpPCfx'],
    ['currency' => 'SOL', 'address' => '5Nz1gv6dqu1dviRtuqkxGD3dDf3arMiBzosRLtx24pX4'],
    ['currency' => 'TRX', 'address' => 'TRuQhvJhMjyENX8qh2dAM8yTVE6aw3FjqJ'],
    ['currency' => 'TRX', 'address' => 'TTDbEFQqXe67AFJraH8GmgbqUE8Xvp8fB8'],
    ['currency' => 'SOL', 'address' => '46TnJVisCTrtgeuxPDimJ4B57g4eEKUoNdHs4AMxtuKX'],
    ['currency' => 'SOL', 'address' => 'BGj4MPqBg4vMvsnRktwRGEnE3MA3Hbc6DbCiaweUxeNN'],
    ['currency' => 'USDT', 'address' => '0x02c48a7f575dc78d7077a92d3b1dce8c1d03c597'],
    ['currency' => 'LTC', 'address' => 'ltc1qeaqs2unfxnh9x49mepygz9h939gzvmf2449ccx'],
    ['currency' => 'DOT', 'address' => '16BrG1s2f2jmMtwNGNFPF7dS7uDUxzp7UTTLETPqUExEEKb6'],
];

echo "Testing provided cryptocurrency addresses...\n";
echo str_repeat("=", 80) . "\n";

$validCount = 0;
$invalidCount = 0;
$unsupportedCount = 0;

foreach ($providedAddresses as $index => $item) {
    $currency = strtolower($item['currency']);
    $address = $item['address'];
    
    echo sprintf("%d. Currency: %s\n", $index + 1, strtoupper($currency));
    echo sprintf("   Address: %s\n", $address);
    
    // Check if currency is supported
    if (!$validator->isSupported($currency)) {
        echo "   Result: UNSUPPORTED ⚠\n\n";
        $unsupportedCount++;
        continue;
    }
    
    // Validate the address
    $isValid = $validator->validate($address, $currency);
    
    if ($isValid) {
        echo "   Result: VALID ✓\n\n";
        $validCount++;
    } else {
        echo "   Result: INVALID ✗\n\n";
        $invalidCount++;
    }
}

echo str_repeat("=", 80) . "\n";
echo "Summary:\n";
echo "Valid addresses: {$validCount}\n";
echo "Invalid addresses: {$invalidCount}\n";
echo "Unsupported currencies: {$unsupportedCount}\n";
echo "Total addresses tested: " . count($providedAddresses) . "\n";

// Exit with error code if any validation failed
if ($invalidCount > 0 || $unsupportedCount > 0) {
    exit(1);
}

echo "\n✅ All provided addresses are valid!\n";