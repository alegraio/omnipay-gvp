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
        if ($this->getPaymentType() === self::PAYMENT_TYPE_3D) {
            return $this->getSalesRequestParamsFor3d();
        }
        return $this->getSalesRequestParams();
    }

    /**
     * @param $data
     * @return PurchaseResponse
     */
    protected function createResponse($data): PurchaseResponse
    {
        return new PurchaseResponse($this, $data);
    }
}
