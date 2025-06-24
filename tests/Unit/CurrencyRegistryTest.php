<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Tests\Unit;

use Multicoin\AddressValidator\Currency;
use Multicoin\AddressValidator\CurrencyRegistry;
use Multicoin\AddressValidator\Validators\BitcoinValidator;
use Multicoin\AddressValidator\Validators\EthereumValidator;
use PHPUnit\Framework\TestCase;

class CurrencyRegistryTest extends TestCase
{
    private CurrencyRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new CurrencyRegistry();
    }

    public function testRegisterAndGetCurrency(): void
    {
        $validator = new BitcoinValidator();
        $currency = new Currency('Bitcoin', 'btc', $validator);
        
        $this->registry->register($currency);
        
        $retrievedByName = $this->registry->get('bitcoin');
        $retrievedBySymbol = $this->registry->get('btc');
        
        $this->assertSame($currency, $retrievedByName);
        $this->assertSame($currency, $retrievedBySymbol);
    }

    public function testGetNonExistentCurrency(): void
    {
        $result = $this->registry->get('nonexistent');
        
        $this->assertNull($result);
    }

    public function testCaseInsensitiveLookup(): void
    {
        $validator = new BitcoinValidator();
        $currency = new Currency('Bitcoin', 'btc', $validator);
        
        $this->registry->register($currency);
        
        $this->assertSame($currency, $this->registry->get('BITCOIN'));
        $this->assertSame($currency, $this->registry->get('BTC'));
        $this->assertSame($currency, $this->registry->get('Bitcoin'));
        $this->assertSame($currency, $this->registry->get('btc'));
    }

    public function testHasCurrency(): void
    {
        $validator = new BitcoinValidator();
        $currency = new Currency('Bitcoin', 'btc', $validator);
        
        $this->registry->register($currency);
        
        $this->assertTrue($this->registry->has('bitcoin'));
        $this->assertTrue($this->registry->has('btc'));
        $this->assertTrue($this->registry->has('BITCOIN'));
        $this->assertTrue($this->registry->has('BTC'));
        
        $this->assertFalse($this->registry->has('ethereum'));
        $this->assertFalse($this->registry->has('eth'));
    }

    public function testGetAllCurrencies(): void
    {
        $bitcoinValidator = new BitcoinValidator();
        $ethereumValidator = new EthereumValidator();
        
        $bitcoin = new Currency('Bitcoin', 'btc', $bitcoinValidator);
        $ethereum = new Currency('Ethereum', 'eth', $ethereumValidator);
        
        $this->registry->register($bitcoin);
        $this->registry->register($ethereum);
        
        $allCurrencies = $this->registry->getAll();
        
        $this->assertCount(2, $allCurrencies);
        $this->assertContains($bitcoin, $allCurrencies);
        $this->assertContains($ethereum, $allCurrencies);
    }

    public function testGetAllCurrenciesNoDuplicates(): void
    {
        $validator = new BitcoinValidator();
        $currency = new Currency('Bitcoin', 'btc', $validator);
        
        $this->registry->register($currency);
        
        $allCurrencies = $this->registry->getAll();
        
        // Should only have one currency even though it's registered under both name and symbol
        $this->assertCount(1, $allCurrencies);
        $this->assertSame($currency, $allCurrencies[0]);
    }

    public function testEmptyRegistry(): void
    {
        $this->assertEmpty($this->registry->getAll());
        $this->assertFalse($this->registry->has('bitcoin'));
        $this->assertNull($this->registry->get('bitcoin'));
    }

    public function testOverwriteRegistration(): void
    {
        $validator1 = new BitcoinValidator();
        $validator2 = new BitcoinValidator(['test' => 'config']);
        
        $currency1 = new Currency('Bitcoin', 'btc', $validator1);
        $currency2 = new Currency('Bitcoin', 'btc', $validator2);
        
        $this->registry->register($currency1);
        $this->registry->register($currency2);
        
        $retrieved = $this->registry->get('bitcoin');
        
        $this->assertSame($currency2, $retrieved);
        $this->assertNotSame($currency1, $retrieved);
    }
}