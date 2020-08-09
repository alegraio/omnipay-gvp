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
        $data = $this->getSalesRequestParams();
        $data['Transaction']['Type'] = 'preauth';

        return $data;

    }

    /**
     * @param string $value
     * @return AuthorizeRequest
     */
    public function setUserId(string $value): AuthorizeRequest
    {
        return $this->setParameter('userId', $value);
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->getParameter('userId');
    }
}

