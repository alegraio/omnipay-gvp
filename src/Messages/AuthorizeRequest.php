<?php
/**
 * Gvp Authorize Request
 */

namespace Omnipay\Gvp\Messages;

class AuthorizeRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData(): array
    {
        $this->setPaymentType();
        return $this->getAuthorizeRequestParams();
    }

    /**
     * @param $data
     * @return AuthorizeResponse
     */
    protected function createResponse($data): AuthorizeResponse
    {
        return new AuthorizeResponse($this, $data);
    }
}

