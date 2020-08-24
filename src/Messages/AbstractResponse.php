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

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
        $this->setData($data);
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        if (!$this->isSuccessful()) {
            return $this->data["Transaction"]->Response->ErrorMsg . "->" . $this->data["Transaction"]->Response->SysErrMsg;
        }

        return isset($this->data["Transaction"]) ? $this->data["Transaction"]->Response->Message : $this->data;
    }

    public function getCode(): ?string
    {
        return (string)($this->data["Transaction"]->Response->Code ?? null);
    }

    /**
     * @param mixed $data
     * @return array
     */
    public function setData($data): array
    {
        if (is_array($data)) {
            $content = $data;
            $this->setIsRedirect(true);
        } else {
            $content = (array)simplexml_load_string($data);
        }

        return $this->data = $content;
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
}
