<?php
/**
 * Gvp Class using API
 */

namespace Omnipay\Gvp;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Gvp\Messages\AuthorizeRequest;
use Omnipay\Gvp\Messages\CaptureRequest;
use Omnipay\Gvp\Messages\CompletePurchaseRequest;
use Omnipay\Gvp\Messages\PurchaseRequest;


/**
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{
    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     * @return string
     */
    public function getName(): string
    {
        return 'Gvp';
    }

    /**
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @param string $value
     * @return Gateway
     */
    public function setMerchantId(string $value): Gateway
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * @return string
     */
    public function getTerminalId(): string
    {
        return $this->getParameter('terminalId');
    }

    /**
     * @param string $value
     * @return Gateway
     */
    public function setTerminalId(string $value): Gateway
    {
        return $this->setParameter('terminalId', $value);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest|RequestInterface
     */
    public function authorize(array $parameters = []): RequestInterface
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest|RequestInterface
     */
    public function capture(array $parameters = []): RequestInterface
    {
        return $this->createRequest(CaptureRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function purchase(array $parameters = []): RequestInterface
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return RequestInterface
     */
    public function completePurchase(array $parameters = []): RequestInterface
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }
}
