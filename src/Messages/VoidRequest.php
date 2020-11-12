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
        $data['Version'] = $this->version;
        $data['Mode'] = $this->getTestMode() ? 'TEST' : 'PROD';
        $data['Terminal'] = [
            'ProvUserID' => self::USERNAME_RFN,
            'HashData' => $this->getTransactionHashRefundAndCancel(),
            'UserID' => self::USERNAME_RFN,
            'ID' => $this->getTerminalId(),
            'MerchantID' => $this->getMerchantId()
        ];
        $data['Customer'] = array(
            'IPAddress' => $this->getClientIp()
        );
        $data['Order'] = array(
            'OrderID' => $this->getOrderId()
        );
        $data['Transaction'] = array(
            'Type' => 'void',
            'Amount' => $this->getAmountInteger(),
            'CurrencyCode' => $this->currency_list[$this->getCurrency()],
            'CardholderPresentCode' => "0",
            'MotoInd' => "N"
        );

        return $data;
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
