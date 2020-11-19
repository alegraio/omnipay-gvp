<?php

namespace OmnipayTest\Gvp\Messages;

use Omnipay\Gvp\Messages\PurchaseRequest;

class Purchase3dRequestTest extends GvpTestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getPurchase3dParams());
    }

    public function testOrderId(): void
    {
        self::assertArrayNotHasKey('orderId', $this->request->getData());

        $this->request->setOrderId('181683681');

        self::assertSame('181683681', $this->request->getOrderId());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('Purchase3dSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertTrue($response->isRedirect());
        self::assertSame('4354353454', $response->getRedirectData()['orderid']);
        self::assertSame('https://sanalposprovtest.garanti.com.tr/servlet/gt3dengine', $response->getRedirectUrl());
        self::assertSame('PROVAUT', $this->request->getProcessName());
    }
}