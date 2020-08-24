<?php
/**
 * Gvp Abstract Response
 */

namespace Omnipay\Gvp\Messages;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse implements RedirectResponseInterface
{
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
     * @param string $data
     * @return array
     */
    public function setData(string $data): array
    {
        if (mb_strpos($data, "html")) {
            $content = (array)$data;
            $content['isHtml'] = true;
        } else {
            $content = (array)simplexml_load_string($data);
        }

        return $this->data = $content;
    }
}
