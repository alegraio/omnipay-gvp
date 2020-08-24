<?php
/**
 * Gvp Purchase Response
 */

namespace Omnipay\Gvp\Messages;

class PurchaseResponse extends AbstractResponse
{

    /**
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        if ($this->getIsRedirect()) {
            return true;
        } else {
            return current($this->data["Transaction"]->Response->Code) === '00';
        }
    }

    /**
     * @return bool
     */
    public function isRedirect(): bool
    {
        return $this->getIsRedirect();
    }

    /**
     * @return array
     */
    public function getRedirectData(): array
    {
        return $this->isRedirect() ? $this->getData() : [];
    }

    /**
     * @return string
     */
    public function getRedirectMethod(): string
    {
        return 'POST';
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        $urlType = $this->getRequest()->getParameters()['testMode'] ? 'test' : 'prod';

        return $this->isRedirect() ? $this->redirectUrl[$urlType] : null;
    }
}
