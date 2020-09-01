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
            'ProvUserID' => $this->getUserName(),
            'HashData' => $this->getTransactionHashWithoutCardNumber(),
            'UserID' => $this->getUserName(),
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
            'Amount' => (int)$this->getAmount(),
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
        return new RefundResponse($this, $data);
    }
}
