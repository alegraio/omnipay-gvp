<?php
/**
 * Gvp Capture Request
 */

namespace Omnipay\Gvp\Messages;


class CaptureRequest extends AbstractRequest
{

    /**
     * @return array
     */
    public function getData(): array
    {
        $data = $this->getSalesRequestParams();
        $data['Transaction']['Type'] = 'postauth';

        return $data;
    }

    /**
     * @param string $value
     * @return CaptureRequest
     */
    public function setUserId(string $value): CaptureRequest
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

