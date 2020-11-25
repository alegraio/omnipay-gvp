<?php

namespace OmnipayTest\Gvp;

use Omnipay\Gvp\Gateway;

use Omnipay\Gvp\Messages\AuthorizeRequest;
use Omnipay\Gvp\Messages\CaptureRequest;
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
        $request = $this->gateway->purchase(['orderId' => '41838239']);

        self::assertInstanceOf(PurchaseRequest::class, $request);
        self::assertSame('41838239', $request->getOrderId());
    }

    public function testAuthorize(): void
    {
        /** @var AuthorizeRequest $request */
        $request = $this->gateway->authorize(['orderId' => '41838239']);

        self::assertInstanceOf(AuthorizeRequest::class, $request);
        self::assertSame('41838239', $request->getOrderId());
    }

    public function testCapture(): void
    {
        /** @var AuthorizeRequest $request */
        $request = $this->gateway->capture(['orderId' => '41838239']);

        self::assertInstanceOf(CaptureRequest::class, $request);
        self::assertSame('41838239', $request->getOrderId());
    }

    public function testRefund(): void
    {
        /** @var RefundRequest $request */
        $request = $this->gateway->refund(['orderId' => '41838239']);

        self::assertInstanceOf(RefundRequest::class, $request);
        self::assertSame('41838239', $request->getOrderId());
    }

    public function testVoid(): void
    {
        /** @var VoidRequest $request */
        $request = $this->gateway->void(['orderId' => '41838239']);

        self::assertInstanceOf(VoidRequest::class, $request);
        self::assertSame('41838239', $request->getOrderId());
    }
}