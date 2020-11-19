<?php
/**
 * Gvp Void Response
 */

namespace Omnipay\Gvp\Messages;

class VoidResponse extends AbstractResponse
{

    public function isCancelled()
    {
        return $this->isSuccessful() ?: parent::isCancelled();

    }
}
