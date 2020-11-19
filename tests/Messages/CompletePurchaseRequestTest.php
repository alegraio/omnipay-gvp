<?php

namespace OmnipayTest\Gvp\Messages;


use Omnipay\Gvp\Messages\CompletePurchaseRequest;

class CompletePurchaseRequestTest extends GvpTestCase
{
    /**
     * @var CompletePurchaseRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getCompletePurchaseParams());
    }

    public function testEndpoint(): void
    {
        self::assertSame('https://sanalposprovtest.garanti.com.tr/VPServlet', $this->request->getEndpoint());
    }

    public function testOrderId(): void
    {
        self::assertArrayNotHasKey('orderId', $this->request->getData());

        $this->request->setOrderId('181683681');

        self::assertSame('181683681', $this->request->getOrderId());
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('CompletePurchaseSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('https://sanalposprovtest.garanti.com.tr/VPServlet', $this->request->getEndpoint());
        self::assertSame('PROVAUT', $this->request->getProcessName());
        self::assertSame('032402285121', $response->getTransactionReference());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('CompletePurchaseFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('', $response->getTransactionReference());
        self::assertSame('https://sanalposprovtest.garanti.com.tr/VPServlet', $this->request->getEndpoint());
        self::assertSame('PROVAUT', $this->request->getProcessName());
        self::assertSame('92', $response->getCode());
        self::assertSame('Aynı sipariş içinde sadece bir tane satış işlemi yapılabilir', $response->getMessage());
    }
}