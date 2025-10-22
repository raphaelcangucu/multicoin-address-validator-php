<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator;

use Multicoin\AddressValidator\Contracts\CurrencyRegistryInterface;
use Multicoin\AddressValidator\Validators\BitcoinValidator;
use Multicoin\AddressValidator\Validators\BitcoinCashValidator;
use Multicoin\AddressValidator\Validators\CardanoValidator;
use Multicoin\AddressValidator\Validators\DogecoinValidator;
use Multicoin\AddressValidator\Validators\EthereumValidator;
use Multicoin\AddressValidator\Validators\LitecoinValidator;
use Multicoin\AddressValidator\Validators\MoneroValidator;
use Multicoin\AddressValidator\Validators\PolkadotValidator;
use Multicoin\AddressValidator\Validators\RippleValidator;
use Multicoin\AddressValidator\Validators\SolanaValidator;
use Multicoin\AddressValidator\Validators\TronValidator;
use Multicoin\AddressValidator\Validators\TonValidator;

/**
 * Factory for creating and registering currencies
 */
class CurrencyFactory
{
    /**
     * Create a registry with all supported currencies
     *
     * @return CurrencyRegistryInterface
     */
    public static function createRegistry(): CurrencyRegistryInterface
    {
        $registry = new CurrencyRegistry();
        
        self::registerCurrencies($registry);
        
        return $registry;
    }

    /**
     * Register all supported currencies
     *
     * @param CurrencyRegistryInterface $registry
     * @return void
     */
    private static function registerCurrencies(CurrencyRegistryInterface $registry): void
    {
        $currencies = self::getCurrencyDefinitions();
        
        foreach ($currencies as $definition) {
            $validator = new $definition['validatorClass']($definition['config']);
            $currency = new Currency(
                $definition['name'],
                $definition['symbol'],
                $validator,
                $definition['config']
            );
            
            $registry->register($currency);
        }
    }

    /**
     * Get currency definitions
     *
     * @return array<array{name: string, symbol: string, validatorClass: class-string, config: array<string, mixed>}>
     */
    private static function getCurrencyDefinitions(): array
    {
        return [
            // Bitcoin and derivatives
            [
                'name' => 'Bitcoin',
                'symbol' => 'btc',
                'validatorClass' => BitcoinValidator::class,
                'config' => [
                    'addressTypes' => ['prod' => ['00', '05'], 'testnet' => ['6f', 'c4', '3c', '26']],
                    'bech32Hrp' => ['prod' => ['bc'], 'testnet' => ['tb']],
                ]
            ],
            [
                'name' => 'BitcoinCash',
                'symbol' => 'bch',
                'validatorClass' => BitcoinCashValidator::class,
                'config' => [
                    'regexp' => '^[qQpP]{1}[0-9a-zA-Z]{41}$',
                    'addressTypes' => ['prod' => ['00', '05'], 'testnet' => ['6f', 'c4']],
                ]
            ],
            [
                'name' => 'LiteCoin',
                'symbol' => 'ltc',
                'validatorClass' => LitecoinValidator::class,
                'config' => [
                    'addressTypes' => ['prod' => ['30', '32'], 'testnet' => ['6f', 'c4', '3a']],
                    'bech32Hrp' => ['prod' => ['ltc'], 'testnet' => ['tltc']],
                ]
            ],
            [
                'name' => 'DogeCoin',
                'symbol' => 'doge',
                'validatorClass' => DogecoinValidator::class,
                'config' => [
                    'addressTypes' => ['prod' => ['1e', '16', '71'], 'testnet' => ['71', 'c4']],
                ]
            ],

            // Ethereum and ERC-20 tokens
            [
                'name' => 'Ethereum',
                'symbol' => 'eth',
                'validatorClass' => EthereumValidator::class,
                'config' => []
            ],
            [
                'name' => 'EthereumClassic',
                'symbol' => 'etc',
                'validatorClass' => EthereumValidator::class,
                'config' => []
            ],
            [
                'name' => 'Binance',
                'symbol' => 'bnb',
                'validatorClass' => EthereumValidator::class,
                'config' => []
            ],
            [
                'name' => 'Polygon',
                'symbol' => 'matic',
                'validatorClass' => EthereumValidator::class,
                'config' => []
            ],
            [
                'name' => 'Polygon',
                'symbol' => 'pol',
                'validatorClass' => EthereumValidator::class,
                'config' => []
            ],
            [
                'name' => 'Multi-collateral DAI',
                'symbol' => 'dai',
                'validatorClass' => EthereumValidator::class,
                'config' => []
            ],
            [
                'name' => 'Shiba Inu',
                'symbol' => 'shib',
                'validatorClass' => EthereumValidator::class,
                'config' => []
            ],
            [
                'name' => 'Avalanche',
                'symbol' => 'avax',
                'validatorClass' => EthereumValidator::class,
                'config' => []
            ],
            [
                'name' => 'Chainlink',
                'symbol' => 'link',
                'validatorClass' => EthereumValidator::class,
                'config' => []
            ],
            [
                'name' => 'Uniswap',
                'symbol' => 'uni',
                'validatorClass' => EthereumValidator::class,
                'config' => []
            ],
            [
                'name' => 'USD Coin',
                'symbol' => 'usdc',
                'validatorClass' => EthereumValidator::class,
                'config' => []
            ],
            [
                'name' => 'Tether',
                'symbol' => 'usdt',
                'validatorClass' => EthereumValidator::class,
                'config' => []
            ],

            // Other major cryptocurrencies
            [
                'name' => 'Ripple',
                'symbol' => 'xrp',
                'validatorClass' => RippleValidator::class,
                'config' => []
            ],
            [
                'name' => 'Cardano',
                'symbol' => 'ada',
                'validatorClass' => CardanoValidator::class,
                'config' => [
                    'bech32Hrp' => ['prod' => ['addr'], 'testnet' => ['addr_test']],
                ]
            ],
            [
                'name' => 'Solana',
                'symbol' => 'sol',
                'validatorClass' => SolanaValidator::class,
                'config' => [
                    'maxLength' => SolanaValidator::MAX_LENGTH,
                    'minLength' => SolanaValidator::MIN_LENGTH,
                ]
            ],
            [
                'name' => 'TRUMP',
                'symbol' => 'trump',
                'validatorClass' => SolanaValidator::class,
                'config' => [
                    'maxLength' => SolanaValidator::MAX_LENGTH,
                    'minLength' => SolanaValidator::MIN_LENGTH,
                ]
            ],
            [
                'name' => 'PENGU',
                'symbol' => 'pengu',
                'validatorClass' => SolanaValidator::class,
                'config' => [
                    'maxLength' => SolanaValidator::MAX_LENGTH,
                    'minLength' => SolanaValidator::MIN_LENGTH,
                ]
            ],
            [
                'name' => 'BONK',
                'symbol' => 'bonk',
                'validatorClass' => SolanaValidator::class,
                'config' => [
                    'maxLength' => SolanaValidator::MAX_LENGTH,
                    'minLength' => SolanaValidator::MIN_LENGTH,
                ]
            ],
            [
                'name' => 'Jupiter',
                'symbol' => 'jup',
                'validatorClass' => SolanaValidator::class,
                'config' => [
                    'maxLength' => SolanaValidator::MAX_LENGTH,
                    'minLength' => SolanaValidator::MIN_LENGTH,
                ]
            ],
            [
                'name' => 'PUMP',
                'symbol' => 'pump',
                'validatorClass' => SolanaValidator::class,
                'config' => [
                    'maxLength' => SolanaValidator::MAX_LENGTH,
                    'minLength' => SolanaValidator::MIN_LENGTH,
                ]
            ],
            [
                'name' => 'Monero',
                'symbol' => 'xmr',
                'validatorClass' => MoneroValidator::class,
                'config' => [
                    'addressTypes' => ['prod' => ['18', '42'], 'testnet' => ['53', '63'], 'stagenet' => ['24']],
                    'iAddressTypes' => ['prod' => ['19'], 'testnet' => ['54'], 'stagenet' => ['25']],
                ]
            ],
            [
                'name' => 'Tron',
                'symbol' => 'trx',
                'validatorClass' => TronValidator::class,
                'config' => []
            ],
            [
                'name' => 'Polkadot',
                'symbol' => 'dot',
                'validatorClass' => PolkadotValidator::class,
                'config' => []
            ],
            [
                'name' => 'TON',
                'symbol' => 'ton',
                'validatorClass' => TonValidator::class,
                'config' => []
            ],

            // Additional Bitcoin variants
            [
                'name' => 'Bitcoin SV',
                'symbol' => 'bsv',
                'validatorClass' => BitcoinCashValidator::class,
                'config' => [
                    'regexp' => '^[qQ]{1}[0-9a-zA-Z]{41}$',
                    'addressTypes' => ['prod' => ['00', '05'], 'testnet' => ['6f', 'c4']],
                ]
            ],
            [
                'name' => 'Dash',
                'symbol' => 'dash',
                'validatorClass' => BitcoinValidator::class,
                'config' => [
                    'addressTypes' => ['prod' => ['4c', '10'], 'testnet' => ['8c', '13']],
                ]
            ],
            [
                'name' => 'ZCash',
                'symbol' => 'zec',
                'validatorClass' => BitcoinValidator::class,
                'config' => [
                    'expectedLength' => 26,
                    'addressTypes' => ['prod' => ['1cb8', '1cbd'], 'testnet' => ['1d25', '1cba']],
                ]
            ],
        ];
    }
}