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
     * @return string
     */
    public function getProcessName(): string
    {
        return self::USERNAME_AUT;
    }

    /**
     * @param $data
     * @return AuthorizeResponse
     */
    protected function createResponse($data): AuthorizeResponse
    {
        $response = new AuthorizeResponse($this, $data);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }
}

