<?php

namespace OmnipayTest\Gvp\Messages;


use Omnipay\Gvp\Messages\RefundRequest;
use Omnipay\Gvp\Messages\VoidRequest;

class VoidRequestTest extends GvpTestCase
{
    /**
     * @var VoidRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getRefundParams());
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
        $this->setMockHttpResponse('VoidSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertTrue($response->isCancelled());
        self::assertSame('https://sanalposprovtest.garanti.com.tr/VPServlet', $this->request->getEndpoint());
        self::assertSame('PROVRFN', $this->request->getProcessName());
        self::assertSame('032402285379', $response->getTransactionReference());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('VoidFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isCancelled());
        self::assertSame('032402285397', $response->getTransactionReference());
        self::assertSame('https://sanalposprovtest.garanti.com.tr/VPServlet', $this->request->getEndpoint());
        self::assertSame('PROVRFN', $this->request->getProcessName());
        self::assertSame('92', $response->getCode());
        self::assertSame('İptal edebileceğiniz birden fazla işlem var, RRN bilgisi gonderi', $response->getMessage());
    }
}