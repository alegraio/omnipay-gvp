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
        return $this->getAuthorizeRequestParams();
    }

    /**
     * @param $data
     * @return AuthorizeResponse
     */
    protected function createResponse($data): AuthorizeResponse
    {
        $response = new AuthorizeResponse($this, $data);
        $response->setServiceRequestParams($data);

        return $response;
    }
}

