<?php
/**
 * Gvp Purchase Request
 */

namespace Omnipay\Gvp\Messages;

use Exception;

class PurchaseRequest extends AbstractRequest
{
    private const PAYMENT_TYPE_3D = "3d";

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getData()
    {
        if ($this->getPaymentMethod() === self::PAYMENT_TYPE_3D) {
            return $this->getSalesRequestParamsFor3d();
        }

        return $this->getSalesRequestParams();
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
     * @return PurchaseResponse
     */
    protected function createResponse($data): PurchaseResponse
    {
        $response = new PurchaseResponse($this, $data);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }
}
