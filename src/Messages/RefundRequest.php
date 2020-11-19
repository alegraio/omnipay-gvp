<?php
/**
 * Gvp Refund Request
 */

namespace Omnipay\Gvp\Messages;

use Exception;

class RefundRequest extends AbstractRequest
{
    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getData(): array
    {
        $data = $this->getRefundRequestParams();
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
        return 'refund';
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
     * @return RefundResponse
     */
    protected function createResponse($data): RefundResponse
    {
        $response = new RefundResponse($this, $data);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }
}
