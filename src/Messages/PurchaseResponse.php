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
        return true;
    }

    /**
     * @return bool
     */
    public function isRedirect(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function getRedirectData(): array
    {
        return $this->getData();
    }
}
