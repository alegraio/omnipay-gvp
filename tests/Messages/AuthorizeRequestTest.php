<?php

namespace OmnipayTest\Gvp\Messages;

use Omnipay\Gvp\Messages\AuthorizeRequest;

class AuthorizeRequestTest extends GvpTestCase
{
    /**
     * @var AuthorizeRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->getAuthorizeParams());
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
        $this->setMockHttpResponse('AuthorizeSuccess.txt');
        $response = $this->request->send();

        self::assertTrue($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('https://sanalposprovtest.garanti.com.tr/VPServlet', $this->request->getEndpoint());
        self::assertSame('PROVAUT', $this->request->getProcessName());
        self::assertSame('032302279242', $response->getTransactionReference());
    }

    public function testSendError(): void
    {
        $this->setMockHttpResponse('AuthorizeFailure.txt');
        $response = $this->request->send();

        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isRedirect());
        self::assertSame('', $response->getTransactionReference());
        self::assertSame('https://sanalposprovtest.garanti.com.tr/VPServlet', $this->request->getEndpoint());
        self::assertSame('PROVAUT', $this->request->getProcessName());
        self::assertSame('92', $response->getCode());
        self::assertSame('Bu terminal için yanlış işyeri numarası girilmiştir', $response->getMessage());
    }
}