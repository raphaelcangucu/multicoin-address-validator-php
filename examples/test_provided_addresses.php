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
    
    // Additional addresses from user data
    ['currency' => 'DOGE', 'address' => 'DEq4gftrAxjVAMSum4on63A1L6TrBZu2oR'],
    ['currency' => 'SOL', 'address' => '4LNytvSKrKxtjSEJPJDn2qiyDAebDAcoQZnsFiMdiJbH'],
    ['currency' => 'BTC', 'address' => '3AWms31FKGTgxbhcjVj9YT4TG5M4fN1qQ7'],
    ['currency' => 'LTC', 'address' => 'ltc1q2tf5at0plmwyhzzt8gzwxhtaygfps23ksqmg25'],
    ['currency' => 'TRX', 'address' => 'TKoZtyQ6C3pxJDV22zB8FjL113pefkCixm'],
    ['currency' => 'DOGE', 'address' => 'D8uopjvEQkvSkkQGGKuBmehGUfuJxp4rjJ'],
    ['currency' => 'XRP', 'address' => 'rs2dgzYeqYqsk8bvkQR5YPyqsXYcA24MP2'],
    ['currency' => 'ETH', 'address' => '0xec9d967650dc10b94851ed4dffeddbd0a9a01dfb'],
    ['currency' => 'MATIC', 'address' => '0xe97b10d3c7863f5999996c1c57a98df16c9eaa44'],
    ['currency' => 'DOT', 'address' => '12To4UHi68Nz4HSQd5uyE7BuS5mxaGopt3enRaE8uS8YPgMM'],
    ['currency' => 'ADA', 'address' => 'addr1v87ccwvenkx2e956rzd0rue6y5y29dvv2vxsuluxydzaz4s64ud7d'],
    ['currency' => 'DOT', 'address' => '14EK8DNECm1cjGegjsTqJNn7VhRLcSqM4dqsegYwaKRHcq4g'],
    ['currency' => 'ADA', 'address' => 'addr1v8vz8nygf4dvj9gzy06zcxlkfwcd9snga5s8jzl2vhxlnuspfxj2l'],
    ['currency' => 'SHIB', 'address' => '0xec9d967650dc10b94851ed4dffeddbd0a9a01dfb'],
    ['currency' => 'DAI', 'address' => '0xec9d967650dc10b94851ed4dffeddbd0a9a01dfb'],
    ['currency' => 'SOL', 'address' => 'HzRkCQPQ26sPTQzkjBkEC4SBcFeKhH6juKTJ1q6gxF7P'],
    ['currency' => 'SOL', 'address' => 'EEE6CDSTb8UZy7LZtB1C1FTUZScowkyfVz8FSZR76F5d'],
    ['currency' => 'ADA', 'address' => 'addr1wxdm0x08jxk97dhx54zrpsju58fzjds286d4cne52z5zkycc994q0'],
    ['currency' => 'SOL', 'address' => 'CK7n3Bd9ACf5Jfn1soxeVndpHNkKZUxdzNQebGFRiyDS'],
    ['currency' => 'SOL', 'address' => 'Cx1g9djwgZ1ZeB89v7fHHHc2SX3Tt5T2V1xU78Exq6c6'],
    ['currency' => 'DOT', 'address' => '14joLpeXV76sriGnhmz1Q99nuz6hPsKZnsJhPrLHXZYw4sgX'],
    ['currency' => 'SOL', 'address' => 'GmFonBFUe4m8YEmd2rNsjxFCV5nNwFwN5s3PT1tR4rev'],
    ['currency' => 'SOL', 'address' => '7fb8Z4vwTJWZv1mjJ4YreyAZUTo8Vkzqe5DVqnYTxN81'],
    ['currency' => 'SOL', 'address' => 'CnNJKCFZRHfJMjfAU3qAGzHT6WnkrDoS2NmvJJWaWtHF'],
    ['currency' => 'SOL', 'address' => 'BUVkFzx3jRySrN5Vbtq4FecQCqVSTe9RGgaUvzvrmxZc'],
    ['currency' => 'SOL', 'address' => '3KmCEfAUiTG5aVJhqYKCfjFMCzXC86SJ8nCQwTCFCUmp'],
    ['currency' => 'SOL', 'address' => '3FyGJDgsMdJoUCXBqdDqPyMmef8gMmXwxYXdvcmKdHM4'],
    ['currency' => 'SOL', 'address' => 'AsPYkat2wZ2Fk92mGU6yUNWChMY2Qzu8tCwBDcg1QogY'],
    ['currency' => 'SOL', 'address' => '9FndVwi3PngsMqXR3bx1tErNhdUJH9EebfboXQTT6jJX'],
    ['currency' => 'SOL', 'address' => 'Bu8jzeRTnJRr8MstWmAB4grZzTU6P9Agev6ux87RTRzz'],
    ['currency' => 'ETH', 'address' => '0xe41ffe6910b8dab874404dd5011c256ed805c78e'],
    ['currency' => 'SOL', 'address' => '47f3DBXa6WsRo93hdNxrkJsiVY2KHyGpcbLp2cwKJbkf'],
    ['currency' => 'SOL', 'address' => '91EGoCXkY2jC3iUYh7MSQEvxtBUUfHU8GHgvwFesKmfJ'],
    ['currency' => 'TRX', 'address' => 'TMSBSGfmsr1iTqKVLGd1P6VTpAnMWptEdK'],
    ['currency' => 'LTC', 'address' => 'LcUZXHxiwzNhrZUssyX3GCSySBt5WZBPdQ'],
    ['currency' => 'BTC', 'address' => '16rUKjUpSzFYphgPi85eC4dXSQ7Vmmnomz'],
    ['currency' => 'DOGE', 'address' => 'D5zmheELHsLxadBNfLaSJF9dVgWiFxjURu'],
    ['currency' => 'TRX', 'address' => 'THYEmySh5QuD5JJnCv7L3SCiBEow1Wh1AL'],
    ['currency' => 'LTC', 'address' => 'LRPJRVrPVNUFqyhLiQ3j4GU8CQRXoschiK'],
    ['currency' => 'SOL', 'address' => 'EMD4CcbneFYgW4dK7td8AVHUpo945CLG1xTL6uKrLNhD'],
    ['currency' => 'SOL', 'address' => 'GUDEUSu528xmv73wJWwHKuN9JwL6fy6Us12fLCUgsTM9'],
    ['currency' => 'BTC', 'address' => '1FvBkW91YfKKkosmWc8uPoqYp15pcSAAt7'],
    ['currency' => 'SOL', 'address' => '5M9Nsto4ZTYqVg4LjBtGpHj7TUMDySxR5xewG2npxsj8'],
    ['currency' => 'SOL', 'address' => 'DUNvv7e65vwHgsXAdYDfhUTeHCQwBVpRQafqJrVKhZGU'],
    ['currency' => 'LTC', 'address' => 'MNyriNvoJZe468KYVMz96dSEm4JEtSCfsc'],
    ['currency' => 'LTC', 'address' => 'LYa5NFGFKFifcpQdqoHVP7fFu846ZuYupN'],
    ['currency' => 'TRX', 'address' => 'TFsNhvg11SVTJDNEEUurnBkZM539Gih1L4'],
    ['currency' => 'LTC', 'address' => 'ltc1qvpn53ymlspspjll0t0mnz2rtf6erhl235x7hyf'],
    ['currency' => 'TRX', 'address' => 'THsw8CDpa3QoAvLomTzukAHRFrwtytoViw'],
    ['currency' => 'SOL', 'address' => '22ygRKJkRy8nhAKzzfsQDjWZAetfN6EdDdTv54XUgHk4'],
    ['currency' => 'TRX', 'address' => 'TWsNs8GDKjojNX29xtSKwHEx2nzdYtjZka'],
    ['currency' => 'TRX', 'address' => 'TCvKnYnjmyr9aw5JkryGVBjfLixd1aZdLC'],
    ['currency' => 'XRP', 'address' => 'rwBWAoDdybkxHUixvSUCqdJdz8dQs2vhrm'],
    ['currency' => 'XRP', 'address' => 'rJn2zAPdFA193sixJwuFixRkYDUtx3apQh'],
    ['currency' => 'TRX', 'address' => 'TTAPXDPHdZwp5BbxYpPeZqWbhfMmwsZBMc'],
    ['currency' => 'TRX', 'address' => 'TARCr2sLx8y5sSsH1pY4CvorZFCBxJ57N7'],
    ['currency' => 'TRX', 'address' => 'TLg7vtTzfXYDLKJnfXK64sxoynDQBoo8y2'],
    ['currency' => 'SOL', 'address' => 'Dx1RheaRf9rfArMTfvJmQWheM2Sd5dZF8wRs4eMM9yBp'],
    
    // Additional addresses from extended user data
    ['currency' => 'SOL', 'address' => 'Ee5sgH3isyrRuKi7mmt1A5tbs8d8ZDuysS2upSVxixn5'],
    ['currency' => 'TRX', 'address' => 'TLrgDRAXERpMC1Fh5LbrRRr2tBJ9NN1Avb'],
    ['currency' => 'LTC', 'address' => 'ltc1qa346jnkvd7ct48szdsk3emg9e4wqsneycsx0cv'],
    ['currency' => 'XRP', 'address' => 'rNxp4h8apvRis6mJf9Sh8C6iRxfrDWN7AV'],
    ['currency' => 'XRP', 'address' => 'rLHzPsX6oXkzU2qL12kHCH8G8cnZv1rBJh'],
    ['currency' => 'USDT', 'address' => '0xB88a75bb018771d76FE94B2bd313dC15cD2EA2B3'],
    ['currency' => 'SOL', 'address' => 'C8kmqe2ofjSpgL11y2hARf41QEqFJnWTmpB4GR6yNj1C'],
    ['currency' => 'LTC', 'address' => 'ltc1q74na84fv3pyvvs3f924xuqyeygf6ylfc2qm950'],
    ['currency' => 'USDT', 'address' => '0xc55DdCc3461eF4caE77d354D6E2CddC297d46F2f'],
    ['currency' => 'LTC', 'address' => 'MJoNUuW2NhgEcBJKoiCm1xarGWVQde6zCG'],
    ['currency' => 'LTC', 'address' => 'MTpdjhMopXY4vVpxf4o1gsSqwg8QhYHQMf'],
    ['currency' => 'TRX', 'address' => 'TA8PVmD25UuVUPCJUsE4kZf4nUomHHU7xd'],
    ['currency' => 'SOL', 'address' => 'DgYjBVhKx7tj1DH7fCrdhoJEJZfXiGsNSNToh884B8GW'],
    ['currency' => 'TRX', 'address' => 'TJFZVfs8bKbiSTNgKmuXCyBBeAsqRnLqaa'],
    ['currency' => 'XRP', 'address' => 'rw2ciyaNshpHe7bCHo4bRWq6pqqynnWKQg'],
    ['currency' => 'SOL', 'address' => 'Go67m5ApwszLGZr3nawMcpcLVdHuaRjN8bLArfyc4DGx'],
    ['currency' => 'TRX', 'address' => 'TFZtssAP41c39Nq24nRhA6ej1q6fWyHToJ'],
    ['currency' => 'ETH', 'address' => '0xdCd0E5c05A2bF5A43304b6B5DD24d2e70fA2F9F8'],
    ['currency' => 'TRX', 'address' => 'TPcFndJRWyWcGgkcn2n71QLE3Y5EpqsSUY'],
    ['currency' => 'SOL', 'address' => 'J8jnR5nQAoEoJ3FSV43Cz5bAZmpwvgtXGpPr4evjdatd'],
    ['currency' => 'LTC', 'address' => 'LTeuCkQt5rcHkikSAPxoQJmthKQW3D19GR'],
    ['currency' => 'XRP', 'address' => 'rKHKDm92HagSa8tYja1FLhuHJyx2Wmz52y'],
    ['currency' => 'XRP', 'address' => 'rBA7oBScBPccjDcmGhkmCY82v2ZeLa2K2f'],
    ['currency' => 'USDT', 'address' => '0x4a2139a5fd6507f4fb3c079c173d826a42b27083'],
    ['currency' => 'LTC', 'address' => 'ltc1qp5gfyjz2cvrvl3gpxh3yryv508qw0syjw6ldxp'],
    ['currency' => 'BTC', 'address' => '3LobpRV7gpRYSxt1pG1fw23Ew7wmVs8ESs'],
    ['currency' => 'LTC', 'address' => 'ltc1q8fqclecqf0868ahshj59rvkamcphapz9lqqyex'],
    ['currency' => 'SOL', 'address' => '4oL4kbG89m7bWN5nipX3hdLLFu9wz8Rs9ZGCRohMokDR'],
    ['currency' => 'LTC', 'address' => 'LX5k6YcS59V6vxsimK8XLVGd2tGofV7NC7'],
    ['currency' => 'LTC', 'address' => 'LLYZWfTW4XuzmJPtHbqkx3Nr1ZuaG23Pdi'],
    ['currency' => 'LTC', 'address' => 'LiAX8TmpYF23SZaxFZfeWNeaV2gZGb2wQN'],
    ['currency' => 'LTC', 'address' => 'ltc1qwgrr0rkvfcrumd4gq7hu0k98qtejwl34y53fzj'],
    ['currency' => 'SOL', 'address' => 'Bri5iXvti8WhP6NhE9NirUfQAqm6Fiv5yLk4hfMbwvb6'],
    ['currency' => 'LTC', 'address' => 'LRfsVZKWqe94359hpubFxAqbRXQQFyD327'],
    ['currency' => 'LTC', 'address' => 'LYx2ESrPUY8S5Sp1JMDktWnCjvkGrucUYy'],
    ['currency' => 'LTC', 'address' => 'LLJaLm9pryZkMKrYVHQmSDyokVijQAzjzA'],
    ['currency' => 'LTC', 'address' => 'MU9MbRzF2yEVEan3Mg9k3Q6DoJY5rnA5bv'],
    ['currency' => 'LTC', 'address' => 'ltc1qa4t7rewhq4nlr094st2gvduvjfgracke0dhdfl'],
    ['currency' => 'USDT', 'address' => '0x90b78de621c471f9151abc3f6462107f582b23f0'],
    ['currency' => 'TRX', 'address' => 'TChXxbLxhhyp4mFZu8T1JoTjVH8hygPGKT'],
    ['currency' => 'TRX', 'address' => 'TDg5U2gDQyVHPZNqCTd1FT3YrT7DCYaGgr'],
    ['currency' => 'LTC', 'address' => 'ltc1qh4k3ktncp5nrxs9t5nqshhc3zdvkwhm7g83vzz'],
    ['currency' => 'LTC', 'address' => 'MSA4tX7MStctXW7SrDMMc9DfAzwWD77t6J'],
    ['currency' => 'LTC', 'address' => 'ltc1q7us3xf9jc2u632yz2eqrymt5war2znwxmcc48e'],
    ['currency' => 'SOL', 'address' => 'DGwbSGERm9hZ3385QWaW16e2Dm76AGKzpdRxeAFC6yFR'],
    ['currency' => 'LTC', 'address' => 'LPk6Ua8sfTKVWjxfTaaaPpow4pz3wQJchf'],
    ['currency' => 'LTC', 'address' => 'MV5WkDPbts5CyM4WjdD9sgVvUqmy1AFin5'],
    ['currency' => 'XRP', 'address' => 'rwBWAoDdybkxHUixvSUCqdJdz8dQs2vhrm'],
    ['currency' => 'LTC', 'address' => 'MGQz4xPKXpsW6cLcJ8DibgZDKwPSAMgvpc'],
    ['currency' => 'ETH', 'address' => '0x1f0E215821dCf95Abe29Ba5ab089C5C2BB8b48aC'],
    ['currency' => 'TRX', 'address' => 'TN992s2kSzUTAWChHeADsfqiTtJ9Hz7GtK'],
    ['currency' => 'SOL', 'address' => '5dUePaZpm1Ut3v1ySL2eEUJfxpAJ4UHARA7r7CEAxWnW'],
    ['currency' => 'USDC', 'address' => '0x9032cdcfeb04e240acc0d6dbfe7060ca3bef8eaf'],
    ['currency' => 'TRX', 'address' => 'TGpWkGFzDbYcaZ2rfjotTA2cKAywPkdT9T'],
    ['currency' => 'SOL', 'address' => 'EC3tVnUuRPVRrXyYVmqS3FUZekQBpSwVFp3xGUnWTQGw'],
    ['currency' => 'DOGE', 'address' => 'DBfqcicoQYbP153VMXVxJnFm4hDULdYvuv'],
    ['currency' => 'LTC', 'address' => 'LTVAyWKKn6Nwh5tdVP5BAhGZR5uZNBvpTM'],
    ['currency' => 'TRX', 'address' => 'TEoQp7v9BxaS834hEJKAtnufwSW6ZKwR1Z'],
    ['currency' => 'TRX', 'address' => 'TY1xJjpvpa3uqyXnGcdTjfpnp9gbNVSHXz'],
    ['currency' => 'ETH', 'address' => '0xe078f172b832cdaae420f747ac76c72f5fa04c14'],
    ['currency' => 'ETH', 'address' => '0x7042A171cC87B3b361284b2d48ee84920847518C'],
    ['currency' => 'TRX', 'address' => 'TUTWNz2BvioKpaiiNUfqGcEmag6citZQ1S'],
    ['currency' => 'LTC', 'address' => 'LfhXiZdCKNWSWrJeNQ93LT2aHgKDN7BKvk'],
    ['currency' => 'USDT', 'address' => '0x7c9411b61834e1710f6b6271e508445e5f571ed5'],
    ['currency' => 'DOGE', 'address' => '9uYV8W6yjr7hHq7oF3PLx11qLFesQgF1NF'],
    ['currency' => 'ETH', 'address' => '0xbe339CE6006151D713065C9eF5249F2236C577a4'],
    ['currency' => 'USDT', 'address' => '0x543d083e1333fdd4c67a78d61fb2e37b4ba06673'],
    ['currency' => 'TRX', 'address' => 'TUP49N6mGHV9eEC1LHX4s4bWDdgvVDzjtt'],
    ['currency' => 'LTC', 'address' => 'LPiF6EHkhzZPWG68rRGDPWzrz8DsPkYMe4'],
    ['currency' => 'TRX', 'address' => 'TB8xawVAgyExG9Z8j3RyGZCFVoPfy8uLqX'],
    ['currency' => 'LTC', 'address' => 'LeVVFRjNF5CFZra41xvU9nZdZ4xCYBwQwm'],
    ['currency' => 'SOL', 'address' => '4qQu4sbk9M2WUyFn3QmMco6JmHst7BN65aufQEWBinr1'],
    ['currency' => 'LTC', 'address' => 'LXGw29jTESft4PCrRT1bEsqjHqrgy8zK3M'],
    ['currency' => 'DOGE', 'address' => 'DCDX6ac1wuNUpyckT5vugiZP1xZguBTUnY'],
    ['currency' => 'BTC', 'address' => 'bc1qxyx9v0xansdky9ze40gav3cxys32c6pgkftduy'],
    ['currency' => 'LTC', 'address' => 'ltc1qzvx7hpkwxhq9462h4z7qjlz75ztac8sthgkep9'],
    ['currency' => 'LTC', 'address' => 'ltc1qfd9e6xpcnxy6hlknkk5jry0cat09rycznzzu4m'],
    ['currency' => 'ETH', 'address' => '0x5050148f19Bfb8d1B3047Aecd8cB2384d6a5918b'],
    ['currency' => 'TRX', 'address' => 'TEHsnKS4jE2ALWo1aryZtRVxEw76cWj6ba'],
    ['currency' => 'USDT', 'address' => '0xd402fd4021d1f0ebd55657afd0cfe0c85c8f3d6e'],
    ['currency' => 'TRX', 'address' => 'TYaNz36udKKs3jgbEG2aHYYqrSwW7WH1Zq'],
    ['currency' => 'MATIC', 'address' => '0x646D6B7e5bB29736Ba2DDd3e03489eFEC6E8bA26'],
    ['currency' => 'USDT', 'address' => '0x26CC121f0af66dAf90c819cC0911078fB5E091Bb'],
    ['currency' => 'USDT', 'address' => '0x0e24d188d8D2C3Bed0983809bD3a6CCe7F476D71'],
    ['currency' => 'TRX', 'address' => 'TDDwcE3YBUUt2pm2dwa2961UDVBCnBFtAr'],
    ['currency' => 'SOL', 'address' => 'CfmixD3DKvjp6VzkddxDTFmJpDVmU1NhHPWE6GzpZJ5M'],
    ['currency' => 'SOL', 'address' => '9MY4TNKaUaB7i13HNqKmzry9znbpe27AwpyKWXgM6ncB'],
    ['currency' => 'SOL', 'address' => '5wTpuHC2bWpGx8fgzWd94EtNmeqE7oP27L4J8cGppnPh'],
    ['currency' => 'LTC', 'address' => 'MLu4jqBTAvFoYu7hWaunrPXitkhjqsYTCG'],
    ['currency' => 'LTC', 'address' => 'MKt4dkWHqHezsV8RuX7yeG4mdjkppSL75Y'],
    ['currency' => 'LTC', 'address' => 'MG6BqNXMWzdCuysraVKhxWP7zxpNtS2zWG'],
    ['currency' => 'ETH', 'address' => '0x952FfD99EAa25feD8545f52105Eb27fee39E0dce'],
    ['currency' => 'SOL', 'address' => '7Y7jFeWUBEnzxaMVm19KLGEwgWu9n3KyzeJJJRoEEuaD'],
    ['currency' => 'SOL', 'address' => 'Be8bApaHG8A24S63LbSxRPScm3gzDoXiYiELBWXQtkZM'],
    ['currency' => 'LTC', 'address' => 'LTgk7x3qg1VsYBpJqjYenWyNuJ8qLgHAZF'],
    ['currency' => 'LTC', 'address' => 'MFeKQdCYLMMjcoTCEQ93YBeN7h4XYFe5ux'],
    ['currency' => 'TRX', 'address' => 'TSDuDTkM3oWxurU2PrLXAPZZFCgWFPN2aZ'],
    ['currency' => 'BTC', 'address' => 'bc1qvvvj87e6t4q7gef3npqhz5tct4c97helj6r3a30cpnd4pnvk5cdqdwymaa'],
    ['currency' => 'SOL', 'address' => 'Eq1xyL2b5vvWXUVSqw7ZJakjCPuRFUzWS2bx9uXY2eVv'],
    ['currency' => 'USDT', 'address' => '0x0c7C12581cDA4c497c758A2779Bb627d10b6742E'],
    ['currency' => 'LTC', 'address' => 'MFPZtgJTHiL7PqAAG1x5VuvD7cC2UaS8VW'],
    ['currency' => 'LTC', 'address' => 'ltc1qsfv5rdrhs47s74c3a4qu69yv4rl8cvarc8sz7m'],
    ['currency' => 'LTC', 'address' => 'LannpdMw72WLzFu5rJxFSFShPPGZpxgk2q'],
    ['currency' => 'USDT', 'address' => '0x0070208f5B6781E08eaCa9bDF08045EF7eD91cE6'],
    ['currency' => 'LTC', 'address' => 'MEZnE1Csdrhtorqrx6WzqDUPGzZ5zxrKq9'],
    ['currency' => 'DOGE', 'address' => 'DPq3KUM9vPbukUf7YpG8KNA3UVeZHbWxPH'],
    ['currency' => 'LTC', 'address' => 'MLHhPSRCMM2bGJAvGBhytu2Rau54fWUJ94'],
    ['currency' => 'BTC', 'address' => '3BCKtw6H5xLvkhD1LFH4TjDnCaJz6kEbGv'],
    ['currency' => 'BTC', 'address' => 'bc1qx4e9uxqk8l336cuctr6fylsvha3a5hxsp0yxjycu7m8q30e9f95qa97t9a'],
    ['currency' => 'LTC', 'address' => 'MV3Bhs217U8CrCreYTQLqC38LS2cFBmbu5'],
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