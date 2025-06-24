<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Tests\Unit\Validators;

use Multicoin\AddressValidator\Validators\CardanoValidator;
use PHPUnit\Framework\TestCase;

class CardanoValidatorTest extends TestCase
{
    private CardanoValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new CardanoValidator([
            'bech32Hrp' => [
                'prod' => ['addr'],
                'testnet' => ['addr_test']
            ]
        ]);
    }

    public function testValidMainnetAddresses(): void
    {
        $validAddresses = [
            // Real Cardano mainnet addresses
            'addr1vxg28zkyte3tdq4rt9cghftdjpql9wua3cw6uzhtngkkuds9aecst', // User-provided valid address
            'addr1vxg28zkyte3tdq4rt9cghftdjpql9wua3cw6uzhtngkkuds9aecst', // Duplicate for now
            'addr1vxg28zkyte3tdq4rt9cghftdjpql9wua3cw6uzhtngkkuds9aecst'  // Duplicate for now
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address, ['networkType' => 'prod']),
                "Mainnet address {$address} should be valid"
            );
        }
    }

    public function testValidTestnetAddresses(): void
    {
        $validAddresses = [
            // Real Cardano testnet addresses
            'addr_test1qqy6nhfyks7wdu3dudslys37v252w2nwhv0fw2nfawemmn8k8ttq8f3gag0h89aepvx3xf69g0l9pf80tqv7cve0l33sw96paj',
            'addr_test1qpw0djgj0x59ngrjvqthn7enhvruxnsavsw5th63la3mjel3tkc974sr23jmlzgq5zda4gtv8k9cy38756r9y3qgmkqqjz6aa7',
            'addr_test1vzpwq95z3xyum8vqn9w62iw33854d9udnyeegzyca3d4j9s7gw9qd'
        ];

        foreach ($validAddresses as $address) {
            $this->assertTrue(
                $this->validator->isValidAddress($address, ['networkType' => 'testnet']),
                "Testnet address {$address} should be valid"
            );
        }
    }

    public function testInvalidAddresses(): void
    {
        $invalidAddresses = [
            '',
            'addr1invalid',
            '1BvBMSEYstWetqTFn5Au4m4GFg7xJaNVN2', // Bitcoin
            '0x742d35Cc6339C4532CE58b5D3Ea8d5A8d6F6395C', // Ethereum
            'rLNaPoKeeBjZe2qs6x52yVPZpZ8td4dc6w', // Ripple
            'addr1', // Too short
            'addr_test1', // Too short
            'wrong_prefix_addr1qx2fxv2umyhttkxyxp8x0dlpdt3k6cwng5pxj3jhsydzer3jcu5d8ps7zex2k2xt3uqxgjqnnj83ws8lhrn648jjxtwq2ytjqp'
        ];

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                $this->validator->isValidAddress($address),
                "Address {$address} should be invalid"
            );
        }
    }

    public function testNetworkSpecificValidation(): void
    {
        $mainnetAddress = 'addr1qx2fxv2umyhttkxyxp8x0dlpdt3k6cwng5pxj3jhsydzer3jcu5d8ps7zex2k2xt3uqxgjqnnj83ws8lhrn648jjxtwq2ytjqp';
        $testnetAddress = 'addr_test1qqy6nhfyks7wdu3dudslys37v252w2nwhv0fw2nfawemmn8k8ttq8f3gag0h89aepvx3xf69g0l9pf80tqv7cve0l33sw96paj';

        // Mainnet address should work on prod network
        $this->assertTrue(
            $this->validator->isValidAddress($mainnetAddress, ['networkType' => 'prod'])
        );

        // Testnet address should work on testnet network
        $this->assertTrue(
            $this->validator->isValidAddress($testnetAddress, ['networkType' => 'testnet'])
        );

        // Cross-network validation should fail
        $this->assertFalse(
            $this->validator->isValidAddress($mainnetAddress, ['networkType' => 'testnet'])
        );

        $this->assertFalse(
            $this->validator->isValidAddress($testnetAddress, ['networkType' => 'prod'])
        );
    }

    public function testGetSupportedNetworks(): void
    {
        $networks = $this->validator->getSupportedNetworks();
        
        $this->assertContains('prod', $networks);
        $this->assertContains('testnet', $networks);
    }
}