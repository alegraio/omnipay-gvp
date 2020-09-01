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
            'ProvUserID' => $this->getUserName(),
            'HashData' => $this->getTransactionHashWithoutCardNumber(),
            'UserID' => $this->getUserName(),
            'ID' => $this->getTerminalId(),
            'MerchantID' => $this->getMerchantId()
        ];
        $data['Customer'] = array(
            'IPAddress' => $this->getClientIp(),
            'EmailAddress' => $this->getCard()->getEmail()
        );
        $data['Order'] = array(
            'OrderID' => $this->getOrderId()
        );
        $data['Transaction'] = array(
            'Type' => 'void',
            'InstallmentCnt' => $this->getInstallment(),
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
        return new VoidResponse($this, $data);
    }
}
