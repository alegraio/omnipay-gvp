<?php

namespace Omnipay\Tests;

use Omnipay\Common\CreditCard;
use Omnipay\Gvp\Messages\AuthorizeResponse;
use Omnipay\Gvp\Gateway;
use Omnipay\Gvp\Messages\CaptureResponse;
use Omnipay\Gvp\Messages\CompletePurchaseResponse;
use Omnipay\Gvp\Messages\PurchaseResponse;


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
    }


    public function testAuthorize()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '10082020_114109',
            'username' => 'PROVAUT',
            'password' => '123qweASD/',
            'amount' => "1",
            'currency' => 'TRY',
            'installment' => "",
            'clientIp' => '10.241.19.2'
        ];

        /** @var AuthorizeResponse $response */
        $response = $this->gateway->authorize($this->options)->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testCapture()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '10082020_1141',
            'username' => 'PROVAUT',
            'password' => '123qweASD/',
            'amount' => "1",
            'currency' => 'TRY',
            'installment' => "",
            'clientIp' => '10.241.19.2'
        ];

        /** @var CaptureResponse $response */
        $response = $this->gateway->capture($this->options)->send();
        $this->assertTrue($response->isSuccessful());
    }

    public function testPurchase()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '10082020_114108',
            'username' => 'PROVAUT',
            'password' => '123qweASD/',
            'amount' => "100",
            'currency' => 'TRY',
            'returnUrl' => "www.backref.com.tr",
            'cancelUrl' => "www.backref.com.tr",
            'installment' => "",
            'paymentType' => '',
            'clientIp' => '10.241.19.2'
        ];

        /** @var PurchaseResponse $response */
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
    }

    public function testCompletePurchase()
    {
        $this->options = [
            'card' => $this->getCardInfo(),
            'orderId' => '8443343542',
            'username' => 'PROVAUT',
            'password' => '123qweASD',
            'amount' => "1",
            'currency' => 'TRY',
            'installment' => "1",
            'cavv' => '1',
            'eci' => '1',
            'xid' => '1',
            'md' => '1',
            'paymentType' => ''
        ];

        /** @var CompletePurchaseResponse $response */
        $response = $this->gateway->completePurchase($this->options)->send();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @return CreditCard
     */
    private function getCardInfo(): CreditCard
    {
        $cardInfo = $this->getValidCard();
        $cardInfo['number'] = '5549608789641500';
        $cardInfo['expiryMonth'] = "03";
        $cardInfo['expiryYear'] = "2023";
        $cardInfo['cvv'] = "712";
        $card = new CreditCard($cardInfo);
        $card->setEmail("emrez@garanti.com.tr");
        $card->setFirstName('Test name');
        $card->setLastName('Test lastname');

        return $card;
    }
}
