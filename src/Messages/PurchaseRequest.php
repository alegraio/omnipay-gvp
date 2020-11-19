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
            $data = $this->getSalesRequestParamsFor3d();
        } else {
            $data = $this->getSalesRequestParams();
        }

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
        return 'sales';
    }

    /**
     * @return array
     */
    public function getSensitiveData(): array
    {
        return ['Number', 'CVV2', 'ExpireDate', 'cardnumber', 'cardexpiredatemonth', 'cardexpiredateyear', 'cardcvv2'];
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
