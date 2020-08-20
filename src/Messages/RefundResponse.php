<?php
/**
 * Gvp Refund Response
 */

namespace Omnipay\Gvp\Messages;

class RefundResponse extends AbstractResponse
{
    /**
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        return $this->data["Transaction"]->Response->Code === '00';
    }
}
