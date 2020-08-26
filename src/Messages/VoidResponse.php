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
        return current($this->data["Transaction"]->Response->Code) === '00';
    }
}
