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
        if (isset($this->getData()['isHtml'])) {
            return true;
        } else {
            return $this->data["Transaction"]->Response->Code === '00';
        }
    }

    /**
     * @return bool
     */
    public function isRedirect(): bool
    {
        if (isset($this->getData()['isHtml'])) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getRedirectData(): array
    {
        return $this->isRedirect() ? $this->getData() : [];
    }
}
