<?php

namespace OmnipayTest\Gvp\Messages;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class GvpTestCase extends TestCase
{
    /**
     * @return array
     */
    protected function getAuthorizeParams(): array
    {
        $params = [
            'card' => $this->getCardInfo(),
            'orderId' => '45467687343',
            'amount' => '100',
            'currency' => 'TRY',
            'installment' => '',
            'clientIp' => 'xxx'
        ];

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     */
    protected function getCaptureParams(): array
    {
        $params = [
            'card' => $this->getCardInfo(),
            'orderId' => '45467687343',
            'amount' => '100',
            'currency' => 'TRY',
            'installment' => '',
            'clientIp' => 'xxx'
        ];

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     */
    protected function getPurchaseParams(): array
    {
        $params = $this->getDefaultPurchaseParams();

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     */
    protected function getPurchase3dParams(): array
    {
        $params = $this->getDefaultPurchaseParams();
        $params['paymentMethod'] = '3d';

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     */
    protected function getCompletePurchaseParams(): array
    {
        $params = [
            'orderId' => '9809896rew',
            'amount' => "50000",
            'currency' => 'TRY',
            'cavv' => 'xxx',
            'eci' => 'xxx',
            'xid' => 'xxx',
            'md' => 'xxx',
            'mdStatus' => '1',
            'clientIp' => 'xxx'
        ];

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     */
    protected function getRefundParams(): array
    {
        $params = [
            'orderId' => '4354353454',
            'amount' => '10',
            'currency' => 'TRY',
            'clientIp' => 'xxx'
        ];

        return $this->provideMergedParams($params);
    }

    protected function getVoidParams(): array
    {
        $params = [
            'orderId' => '9809896',
            'amount' => '100',
            'currency' => 'TRY',
            'clientIp' => 'xxx'
        ];

        return $this->provideMergedParams($params);
    }

    /**
     * @return array
     */
    private function getDefaultOptions(): array
    {
        return [
            'testMode' => true,
            'terminalId' => '30691297',
            'merchantId' => '7000679',
            'password' => '123qweASD/'
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    private function provideMergedParams(array $params): array
    {
        $params = array_merge($params, $this->getDefaultOptions());
        return $params;
    }

    /**
     * @return array
     */
    protected function getDefaultPurchaseParams(): array
    {
        $params = [
            'card' => $this->getCardInfo(),
            'orderId' => '4354353454',
            'amount' => '500',
            'currency' => 'TRY',
            'returnUrl' => 'https://eticaret.garanti.com.tr/destek/postback.aspx',
            'cancelUrl' => 'https://eticaret.garanti.com.tr/destek/postback.aspx',
            'installment' => '',
            'paymentMethod' => '',
            'clientIp' => 'xxx',
            'secureKey' => '12345678'
        ];

        return $params;
    }

    /**
     * @return CreditCard
     */
    private function getCardInfo(): CreditCard
    {
        $cardInfo = $this->getValidCard();
        $cardInfo['number'] = '5406697543211173';
        $cardInfo['expiryMonth'] = '03';
        $cardInfo['expiryYear'] = '23';
        $cardInfo['cvv'] = '465';
        $card = new CreditCard($cardInfo);
        $card->setEmail('test@garanti.com.tr');
        $card->setFirstName('Test name');
        $card->setLastName('Test lastname');

        return $card;
    }
}