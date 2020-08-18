<?php
/**
 * Gvp Authorize Response
 */

namespace Omnipay\Gvp\Messages;

class AuthorizeResponse extends AbstractResponse
{
    /**
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        return $this->data["Transaction"]->Response->Code === '00';
    }
}
