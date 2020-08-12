<?php
/**
 * Gvp Authorize Request
 */

namespace Omnipay\Gvp\Messages;

use Exception;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;

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

