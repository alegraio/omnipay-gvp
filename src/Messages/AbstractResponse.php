<?php
/**
 * Gvp Abstract Response
 */

namespace Omnipay\Gvp\Messages;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse implements RedirectResponseInterface
{
    /** @var bool */
    public $isRedirect = false;

    /** @var array */
    public $redirectUrl = [
        'test' => 'https://sanalposprovtest.garanti.com.tr/servlet/gt3dengine',
        'prod' => 'https://sanalposprov.garanti.com.tr/servlet/gt3dengine'
    ];

    /**
     * AbstractResponse constructor.
     * @param RequestInterface $request
     * @param $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        $this->setData($data);
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {

        if (!$this->isSuccessful()) {
            return (string)$this->data->Transaction->Response->ErrorMsg;
        }

        return (string)(!$this->getIsRedirect() ? $this->data->Transaction->Response->Message : null);
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return (string)($this->data->Transaction->Response->Code ?? null);
    }

    /**
     * @param $data
     */
    public function setData($data): void
    {
        if (is_array($data)) {
            $content = $data;
            $this->setIsRedirect(true);
        } else {
            $content = simplexml_load_string($data);
        }

        $this->data = $content;
    }

    /**
     * @return bool
     */
    public function getIsRedirect(): bool
    {
        return $this->isRedirect;
    }

    /**
     * @param bool $isRedirect
     */
    public function setIsRedirect(bool $isRedirect): void
    {
        $this->isRedirect = $isRedirect;
    }

    /**
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        if ($this->getIsRedirect()) {
            return true;
        }

        return (string)$this->data->Transaction->Response->Code === '00';
    }

    public function getTransactionReference(): ?string
    {
        return (string)$this->data->Transaction->RetrefNum;
    }
}
