<?php

namespace OmnipayTest\Gvp\Messages;


use Omnipay\Gvp\Messages\RefundRequest;

class RefundRequestTest extends GvpTestCase
{
    /**
     * @var RefundRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
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
        $this->setMockHttpResponse('RefundSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('https://sanalposprovtest.garanti.com.tr/VPServlet', $this->request->getEndpoint());
        self::assertSame('PROVRFN', $this->request->getProcessName());
        self::assertSame('032402285379', $response->getTransactionReference());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('RefundFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('', $response->getTransactionReference());
        self::assertSame('https://sanalposprovtest.garanti.com.tr/VPServlet', $this->request->getEndpoint());
        self::assertSame('PROVRFN', $this->request->getProcessName());
        self::assertSame('92', $response->getCode());
        self::assertSame('Bu terminal için yanlış işyeri numarası girilmiştir', $response->getMessage());
    }
}