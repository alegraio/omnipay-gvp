<?php
/**
 * Gvp Complete Purchase Response
 */

namespace Omnipay\Gvp\Messages;

class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        return $this->data["Transaction"]->Response->Code === '00';
    }
}
