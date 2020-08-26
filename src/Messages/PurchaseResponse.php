<?php
/**
 * Gvp Purchase Response
 */

namespace Omnipay\Gvp\Messages;

class PurchaseResponse extends AbstractResponse
{

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

        return $this->isRedirect() ? $this->redirectUrl[$urlType] : '';
    }
}
