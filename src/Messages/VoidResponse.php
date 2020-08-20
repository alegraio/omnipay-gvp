<?php
/**
 * Gvp Void Response
 */

namespace Omnipay\Gvp\Messages;

class VoidResponse extends AbstractResponse
{
    /**
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        return $this->data["Transaction"]->Response->Code === '00';
    }
}
