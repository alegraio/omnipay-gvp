<?php
/**
 * Gvp Abstract Request
 */

namespace Omnipay\Gvp\Messages;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /** @var string */
    protected $version = 'v0.01';

    /** @var array */
    protected $endpoints = [
        'test' => 'https://sanalposprovtest.garanti.com.tr/VPServlet',
        'prod' => 'https://sanalposprov.garanti.com.tr/VPServlet'
    ];

    protected $currency_list = [
        'TRY' => 949,
        'YTL' => 949,
        'TRL' => 949,
        'TL' => 949,
        'USD' => 840,
        'EUR' => 978,
        'GBP' => 826,
        'JPY' => 392
    ];

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->endpoints["test"] : $this->endpoints["prod"];
    }

    /**
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setMerchantId(string $value): AbstractRequest
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * @return string
     */
    public function getTerminalId(): string
    {
        return $this->getParameter('terminalId');
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setTerminalId(string $value): AbstractRequest
    {
        return $this->setParameter('terminalId', $value);
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->getParameter('username');
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setUserName(string $value): AbstractRequest
    {
        return $this->setParameter('username', $value);
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->getParameter('password');
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setPassword(string $value): AbstractRequest
    {
        return $this->setParameter('password', $value);
    }

    /**
     * @param mixed $data
     * @return ResponseInterface|AbstractResponse
     * @throws InvalidResponseException
     */
    public function sendData($data)
    {
        try {
            if ($this->getPaymentMethod() === '3d') {
                $response = $data;
            } else {
                $document = new \DOMDocument('1.0', 'UTF-8');
                $root = $document->createElement('GVPSRequest');
                $xml = static function ($root, $data) use ($document, &$xml) {
                    foreach ($data as $key => $value) {
                        if (is_array($value)) {
                            $subs = $document->createElement($key);
                            $root->appendChild($subs);
                            $xml($subs, $value);
                        } else {
                            $root->appendChild($document->createElement($key, $value));
                        }
                    }
                };

                $xml($root, $data);
                $document->appendChild($root);

                $httpRequest = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(),
                    ['Content-Type' => 'application/x-www-form-urlencoded'], $document->saveXML());

                $response = (string)$httpRequest->getBody()->getContents();
            }

            return $this->response = $this->createResponse($response);
        } catch (\Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setOrderId(string $value): AbstractRequest
    {
        return $this->setParameter('orderId', $value);
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->getParameter('orderId');
    }

    /**
     * @return string
     */
    public function getInstallment(): string
    {
        return $this->getParameter('installment');
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setInstallment(string $value): AbstractRequest
    {
        return $this->setParameter('installment', $value);
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->getParameter('lang') ?? 'tr';
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setLang(string $value): AbstractRequest
    {
        return $this->setParameter('lang', $value);
    }

    /**
     * @param string $value
     * @return AbstractRequest
     */
    public function setSecureKey(string $value): AbstractRequest
    {
        return $this->setParameter('secureKey', $value);
    }

    /**
     * @return string
     */
    public function getSecureKey(): string
    {
        return $this->getParameter('secureKey');
    }

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    protected function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * @return string
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    protected function getTransactionHash(): string
    {
        $amount = (int)$this->getAmount();

        return strtoupper(SHA1(sprintf('%s%s%s%s%s',
            $this->getOrderId(),
            $this->getTerminalId(),
            $this->getCard()->getNumber(),
            $amount,
            $this->getSecurityHash())));
    }

    /**
     * @return string
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    protected function getTransactionHashWithoutCardNumber(): string
    {
        $amount = (int)$this->getAmount();

        return strtoupper(SHA1(sprintf('%s%s%s%s',
            $this->getOrderId(),
            $this->getTerminalId(),
            $amount,
            $this->getSecurityHash())));
    }

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    protected function getSalesRequestParams(): array
    {
        $data = $this->getInfo();
        $data['Card'] = array(
            'Number' => $this->getCard()->getNumber(),
            'ExpireDate' => $this->getCard()->getExpiryDate('my'),
            'CVV2' => $this->getCard()->getCvv()
        );

        $data['Order'] = array(
            'OrderID' => $this->getOrderId()
        );

        $data['Customer'] = array(
            'IPAddress' => $this->getClientIp(),
            'EmailAddress' => $this->getCard()->getEmail()
        );

        $data['Terminal'] = [
            'ProvUserID' => $this->getUserName(),
            'HashData' => $this->getTransactionHash(),
            'UserID' => $this->getUserName(),
            'ID' => $this->getTerminalId(),
            'MerchantID' => $this->getMerchantId()
        ];

        $data['Transaction'] = array(
            'Type' => 'sales',
            'InstallmentCnt' => $this->getInstallment(),
            'Amount' => (int)$this->getAmount(),
            'CurrencyCode' => $this->currency_list[$this->getCurrency()],
            'CardholderPresentCode' => "0",
            'MotoInd' => "N"
        );

        return $data;
    }


    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    protected function getCompleteSalesRequestParams(): array
    {

        $data = $this->getInfo();
        $data['Order'] = array(
            'OrderID' => $this->getOrderId()
        );

        $data['Customer'] = array(
            'IPAddress' => $this->getClientIp(),
        );

        $data['Terminal'] = [
            'ProvUserID' => $this->getUserName(),
            'HashData' => $this->getTransactionHashWithoutCardNumber(),
            'UserID' => $this->getUserName(),
            'ID' => $this->getTerminalId(),
            'MerchantID' => $this->getMerchantId()
        ];

        $data['Transaction'] = array(
            'Type' => 'sales',
            'Amount' => (int)$this->getAmount(),
            'CurrencyCode' => $this->currency_list[$this->getCurrency()],
            'MotoInd' => "N"
        );

        return $data;
    }

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    protected function getAuthorizeRequestParams(): array
    {
        $data = $this->getInfo();
        $data['Terminal'] = [
            'ProvUserID' => $this->getUserName(),
            'HashData' => $this->getTransactionHash(),
            'UserID' => $this->getUserName(),
            'ID' => $this->getTerminalId(),
            'MerchantID' => $this->getMerchantId()
        ];
        $data['Customer'] = array(
            'IPAddress' => $this->getClientIp(),
            'EmailAddress' => $this->getCard()->getEmail()
        );
        $data['Card'] = array(
            'Number' => $this->getCard()->getNumber(),
            'ExpireDate' => $this->getCard()->getExpiryDate('my')
        );
        $data['Order'] = array(
            'OrderID' => $this->getOrderId()
        );
        $data['Transaction'] = array(
            'Type' => 'preauth',
            'InstallmentCnt' => $this->getInstallment(),
            'Amount' => (int)$this->getAmount(),
            'CurrencyCode' => $this->currency_list[$this->getCurrency()],
            'CardholderPresentCode' => "0",
            'MotoInd' => "N"
        );

        return $data;
    }

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    protected function getSalesRequestParamsFor3d(): array
    {
        $params['apiversion'] = $this->version;
        $params['mode'] = $this->getTestMode() ? 'TEST' : 'PROD';
        $params['terminalprovuserid'] = $this->getUserName();
        $params['terminaluserid'] = $this->getUserName();
        $params['terminalid'] = $this->getTerminalId();
        $params['terminalmerchantid'] = $this->getMerchantId();
        $params['txntype'] = 'sales';
        $params['txnamount'] = (int)$this->getAmount();
        $params['txncurrencycode'] = $this->currency_list[$this->getCurrency()];
        $params['txninstallmentcount'] = $this->getInstallment();
        $params['customeremailaddress'] = $this->getCard()->getEmail();
        $params['customeripaddress'] = $this->getClientIp();
        $params['orderid'] = $this->getOrderId();
        $params['successurl'] = $this->getReturnUrl();
        $params['errorurl'] = $this->getCancelUrl();
        $params['lang'] = $this->getLang();
        $params['txntimestamp'] = date("d/m/Y H:i:s");
        $params['refreshtime'] = 5;
        $params['cardnumber'] = $this->getCard()->getNumber();
        $params['cardexpiredatemonth'] = $this->getCard()->getExpiryMonth();
        $params['cardexpiredateyear'] = $this->getCard()->getExpiryYear();
        $params['cardcvv2'] = $this->getCard()->getCvv();
        $params['secure3dsecuritylevel'] = '3D';

        $hashData = strtoupper(sha1($this->getTerminalId() . $params['orderid'] . $params['txnamount'] . $params['successurl'] . $params['errorurl'] . $params['txntype'] . $params['txninstallmentcount'] . $this->getSecureKey() . $this->getSecurityHash()));
        $params['secure3dhash'] = $hashData;

        return $params;
    }

    private function getSecurityHash(): string
    {
        $tidPrefix = str_repeat('0', 9 - strlen($this->getTerminalId()));
        $terminalId = sprintf('%s%s', $tidPrefix, $this->getTerminalId());

        return strtoupper(SHA1(sprintf('%s%s', $this->getPassword(), $terminalId)));
    }

    /**
     * @return array
     */
    private function getInfo(): array
    {
        $data['Version'] = $this->version;
        $data['Mode'] = $this->getTestMode() ? 'TEST' : 'PROD';

        return $data;
    }
}
