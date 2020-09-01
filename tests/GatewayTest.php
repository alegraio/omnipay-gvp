<?php

namespace Omnipay\Tests;

use Omnipay\Common\CreditCard;
use Omnipay\Gvp\Messages\AuthorizeResponse;
use Omnipay\Gvp\Gateway;
use Omnipay\Gvp\Messages\CaptureResponse;
use Omnipay\Gvp\Messages\CompletePurchaseResponse;
use Omnipay\Gvp\Messages\PurchaseResponse;
use Omnipay\Gvp\Messages\RefundResponse;
use Omnipay\Gvp\Messages\VoidResponse;


class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    public $gateway;

    /** @var array */
    public $options;

    public function setUp()
    {
        /** @var Gateway gateway */
        $this->gateway = new Gateway(null, $this->getHttpRequest());
        $this->gateway->setMerchantId('7000679');
        $this->gateway->setTerminalId('30691297');
        $this->gateway->setTestMode(true);
        $this->gateway->setUserName('PROVAUT');
        $this->gateway->setPassword('123qweASD/');
    }

    public function testCompletePurchase()
    {
        $this->options = [
            'orderId' => '98976534',
            'amount' => "10",
            'currency' => 'TRY',
            'cavv' => 'jCm0m+u/0hUfAREHBAMBcfN+pSo=',
            'eci' => '02',
            'xid' => 'RszfrwEYe/8xb7rnrPuh6C9pZSQ=',
            'md' => 'G1YfkxEZ8Noemg4MRspO20vEiXaEk51APnDVEz+WRaZCdQ8gq+3XJRSTiNWDuZUhrvpkP7r3mk+BGumU6GL2XP/NFyczbI+2dQi8uS/etyI0QcvcFd2NGhLBsDNYfOwILnzLqvoyienmWkZy1a5I/w==',
            'mdStatus' => '1',
            'clientIp' => '10.241.19.2'
        ];

        /** @var CompletePurchaseResponse $response */
        $response = $this->gateway->completePurchase($this->options)->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testPurchase()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '98976534',
            'amount' => "10",
            'currency' => 'TRY',
            'returnUrl' => "https://eticaret.garanti.com.tr/destek/postback.aspx",
            'cancelUrl' => "https://eticaret.garanti.com.tr/destek/postback.aspx",
            'installment' => "",
            'paymentMethod' => '3d',
            'clientIp' => '10.241.19.2',
            'secureKey' => '12345678'
        ];

        /** @var PurchaseResponse $response */
        $response = $this->gateway->purchase($this->options)->send();
        $this->assertTrue($response->isSuccessful());
    }


    public function testCapture()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '90082020_324109',
            'amount' => "100",
            'currency' => 'TRY',
            'installment' => "",
            'clientIp' => '10.241.19.2'
        ];

        /** @var CaptureResponse $response */
        $response = $this->gateway->capture($this->options)->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testAuthorize()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '90082020_324109',
            'amount' => "100",
            'currency' => 'TRY',
            'installment' => "",
            'clientIp' => '10.241.19.2'
        ];

        /** @var AuthorizeResponse $response */
        $response = $this->gateway->authorize($this->options)->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testVoid()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '10082020_324109',
            'amount' => "100",
            'currency' => 'TRY',
            'installment' => "",
            'clientIp' => '10.241.19.2'
        ];

        /** @var VoidResponse $response */
        $response = $this->gateway->void($this->options)->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testRefund()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '10082020_324109',
            'amount' => "100",
            'currency' => 'TRY',
            'installment' => "",
            'clientIp' => '10.241.19.2'
        ];


        /** @var RefundResponse $response */
        $response = $this->gateway->refund($this->options)->send();
        $this->assertTrue($response->isSuccessful());
    }


    /**
     * @return CreditCard
     */
    private function getCardInfo(): CreditCard
    {
        $cardInfo = $this->getValidCard();
        $cardInfo['number'] = '4282209004348015';
        $cardInfo['expiryMonth'] = "08";
        $cardInfo['expiryYear'] = "22";
        $cardInfo['cvv'] = "123";
        $card = new CreditCard($cardInfo);
        $card->setEmail("emrez@garanti.com.tr");
        $card->setFirstName('Test name');
        $card->setLastName('Test lastname');

        return $card;
    }
}
