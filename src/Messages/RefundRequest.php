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
            'Type' => 'refund',
            'Amount' => $this->getAmountInteger(),
            'CurrencyCode' => $this->currency_list[$this->getCurrency()]
        );

        return $data;
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
