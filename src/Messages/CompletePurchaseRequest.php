<?php
/**
 * Gvp Complete Purchase Request
 */

namespace Omnipay\Gvp\Messages;

use Exception;
use Omnipay\Common\Exception\RuntimeException;

class CompletePurchaseRequest extends AbstractRequest
{
    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getData()
    {
        if (!in_array($this->getMdStatus(), array(1, 2, 3, 4), false)) {
            throw new RuntimeException('3DSecure verification error');
        }

        $data = $this->getCompleteSalesRequestParams();
        $data['Transaction']['CardholderPresentCode'] = "13";
        $secure3d = [
            "AuthenticationCode" => $this->getCavv(),
            "SecurityLevel" => $this->getEci(),
            "TxnID" => $this->getXid(),
            "Md" => $this->getMd(),
        ];
        $data['Transaction']['Secure3D'] = $secure3d;

        return $data;
    }

    /**
     * @return string
     */
    public function getCavv(): string
    {
        return $this->getParameter('cavv');
    }

    /**
     * @param string $value
     * @return CompletePurchaseRequest
     */
    public function setCavv(string $value): CompletePurchaseRequest
    {
        return $this->setParameter('cavv', $value);
    }

    /**
     * @return string
     */
    public function getEci(): string
    {
        return $this->getParameter('eci');
    }

    /**
     * @param string $value
     * @return CompletePurchaseRequest
     */
    public function setEci(string $value): CompletePurchaseRequest
    {
        return $this->setParameter('eci', $value);
    }

    /**
     * @return string
     */
    public function getXid(): string
    {
        return $this->getParameter('xid');
    }

    /**
     * @param string $value
     * @return CompletePurchaseRequest
     */
    public function setXid(string $value): CompletePurchaseRequest
    {
        return $this->setParameter('xid', $value);
    }

    /**
     * @return string
     */
    public function getMd(): string
    {
        return $this->getParameter('md');
    }

    /**
     * @param string $value
     * @return CompletePurchaseRequest
     */
    public function setMd(string $value): CompletePurchaseRequest
    {
        return $this->setParameter('md', $value);
    }

    /**
     * @return string
     */
    public function getMdStatus(): string
    {
        return $this->getParameter('mdStatus');
    }

    /**
     * @param string $value
     * @return CompletePurchaseRequest
     */
    public function setMdStatus(string $value): CompletePurchaseRequest
    {
        return $this->setParameter('mdStatus', $value);
    }

    /**
     * @param $data
     * @return CompletePurchaseResponse
     */
    protected function createResponse($data): CompletePurchaseResponse
    {
        $response = new CompletePurchaseResponse($this, $data);
        $requestParams = $this->getRequestParams();
        $response->setServiceRequestParams($requestParams);

        return $response;
    }
}
