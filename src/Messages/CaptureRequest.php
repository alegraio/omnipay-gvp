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
        $data = $this->getAuthorizeRequestParams();
        $data['Transaction']['Type'] = 'postauth';
        $data['Card']['CVV2'] = $this->getCard()->getCvv();

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
     * @param $data
     * @return CaptureResponse
     */
    protected function createResponse($data): CaptureResponse
    {
        $response = new CaptureResponse($this, $data);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }
}

