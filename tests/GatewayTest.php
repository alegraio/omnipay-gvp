<?php

namespace OmnipayTest\PayU;

use Omnipay\Gvp\Gateway;
use Omnipay\Gvp\Messages\AuthorizeRequest;
use Omnipay\Gvp\Messages\CaptureRequest;
use Omnipay\Gvp\Messages\CompletePurchaseRequest;
use Omnipay\Gvp\Messages\PurchaseRequest;
use Omnipay\Gvp\Messages\RefundRequest;
use Omnipay\Gvp\Messages\VoidRequest;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp(): void
    {
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase(): void
    {
        /** @var PurchaseRequest $request */
        $request = $this->gateway->purchase(['orderRef' => '41838239']);

        self::assertInstanceOf(PurchaseRequest::class, $request);
        self::assertSame('41838239', $request->getOrderRef());
    }

    public function testCompletePurchase(): void
    {
        /** @var CompletePurchaseRequest $request */
        $request = $this->gateway->completePurchase(['orderRef' => '41838239']);

        self::assertInstanceOf(CompletePurchaseRequest::class, $request);
        self::assertSame('41838239', $request->getOrderRef());
    }

    public function testAuthorize(): void
    {
        /** @var AuthorizeRequest $request */
        $request = $this->gateway->authorize(['bin' => '557829']);

        self::assertInstanceOf(CardInfoV1Request::class, $request);
        self::assertSame('557829', $request->getBin());
    }

    public function testCapture(): void
    {
        /** @var CaptureRequest $request */
        $request = $this->gateway->capture(['clientId' => '34353']);

        self::assertInstanceOf(CaptureRequest::class, $request);
        self::assertSame('34353', $request->getClientId());
    }

    public function testRefund(): void
    {
        /** @var RefundRequest $request */
        $request = $this->gateway->refund(['orderRef' => '41838239']);

        self::assertInstanceOf(RefundRequest::class, $request);
        self::assertSame('41838239', $request->getOrderRef());
    }

    public function testVoid(): void
    {
        /** @var VoidRequest $request */
        $request = $this->gateway->void(['refNoExt' => '784']);

        self::assertInstanceOf(VoidRequest::class, $request);
        self::assertSame('784', $request->getRefNoExt());
    }
}