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
        $data = $this->getAuthorizeRequestParams();
        $this->setRequestParams($data);

        return $data;
    }

    /**
     * @return string
     */
    public function getProcessName(): string
    {
        return self::USERNAME_AUT;
    }

    /**
     * @return string
     */
    public function getProcessType(): string
    {
        return 'preauth';
    }

    /**
     * @return array
     */
    public function getSensitiveData(): array
    {
        return ['Number', 'ExpireDate'];
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

