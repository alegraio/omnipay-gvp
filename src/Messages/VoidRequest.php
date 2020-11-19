<?php
/**
 * Gvp Void Request
 */

namespace Omnipay\Gvp\Messages;

use Exception;

class VoidRequest extends AbstractRequest
{
    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getData(): array
    {
        $data = $this->getRefundRequestParams();
        $data['Transaction']['CardholderPresentCode'] = '0';
        $data['Transaction']['MotoInd'] = 'N';

        $this->setRequestParams($data);

        return $data;
    }

    /**
     * @return string
     */
    public function getProcessName(): string
    {
        return self::USERNAME_RFN;
    }

    /**
     * @return string
     */
    public function getProcessType(): string
    {
        return 'void';
    }

    /**
     * @return array
     */
    public function getSensitiveData(): array
    {
        return [];
    }


    /**
     * @param $data
     * @return VoidResponse
     */
    protected function createResponse($data): VoidResponse
    {
        $response = new VoidResponse($this, $data);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }
}
