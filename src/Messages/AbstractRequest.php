<?php
/**
 * Gvp Abstract Request
 */

namespace Omnipay\Gvp\Messages;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Gvp\Mask;
use Omnipay\Gvp\RequestInterface;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest implements RequestInterface
{
    /** @var string */
    protected const USERNAME_AUT = 'PROVAUT';

    /** @var string */
    protected const USERNAME_RFN = 'PROVRFN';

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

    protected $requestParams;

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
     */
    protected function getTransactionHash(): string
    {
        return strtoupper(SHA1(sprintf('%s%s%s%s%s',
            $this->getOrderId(),
            $this->getTerminalId(),
            $this->getCard()->getNumber(),
            $this->getAmountInteger(),
            $this->getSecurityHash())));
    }

    /**
     * @return string
     */
    protected function getTransactionHashWithoutCardNumber(): string
    {
        return strtoupper(SHA1(sprintf('%s%s%s%s%s',
            $this->getOrderId(),
            $this->getTerminalId(),
            null,
            $this->getAmountInteger(),
            $this->getSecurityHash())));
    }

    /**
     * @return string
     */
    protected function getTransactionHashRefundAndCancel(): string
    {
        return strtoupper(SHA1(sprintf('%s%s%s%s',
            $this->getOrderId(),
            $this->getTerminalId(),
            $this->getAmountInteger(),
            $this->getSecurityHash())));
    }


    /**
     * @return array
     */
    protected function getSalesRequestParams(): array
    {
        $data = $this->getInfo();
        $data['Card'] = [
            'Number' => $this->getCard()->getNumber(),
            'ExpireDate' => $this->getCard()->getExpiryDate('my'),
            'CVV2' => $this->getCard()->getCvv()
        ];

        $data['Order'] = [
            'OrderID' => $this->getOrderId()
        ];

        $data['Customer'] = [
            'IPAddress' => $this->getClientIp(),
            'EmailAddress' => $this->getCard()->getEmail()
        ];

        $data['Terminal'] = [
            'ProvUserID' => $this->getProcessName(),
            'HashData' => $this->getTransactionHash(),
            'UserID' => $this->getProcessName(),
            'ID' => $this->getTerminalId(),
            'MerchantID' => $this->getMerchantId()
        ];

        $data['Transaction'] = [
            'Type' => $this->getProcessType(),
            'InstallmentCnt' => $this->getInstallment(),
            'Amount' => $this->getAmountInteger(),
            'CurrencyCode' => $this->currency_list[$this->getCurrency()],
            'CardholderPresentCode' => '0',
            'MotoInd' => 'N'
        ];

        return $data;
    }


    /**
     * @return array
     */
    protected function getCompleteSalesRequestParams(): array
    {

        $data = $this->getInfo();
        $data['Order'] = [
            'OrderID' => $this->getOrderId()
        ];

        $data['Customer'] = [
            'IPAddress' => $this->getClientIp(),
        ];

        $data['Terminal'] = [
            'ProvUserID' => $this->getProcessName(),
            'HashData' => $this->getTransactionHashWithoutCardNumber(),
            'UserID' => $this->getProcessName(),
            'ID' => $this->getTerminalId(),
            'MerchantID' => $this->getMerchantId()
        ];

        $data['Transaction'] = [
            'Type' => $this->getProcessType(),
            'Amount' => $this->getAmountInteger(),
            'CurrencyCode' => $this->currency_list[$this->getCurrency()],
            'MotoInd' => 'N'
        ];

        return $data;
    }

    /**
     * @return array
     */
    protected function getAuthorizeRequestParams(): array
    {
        $data = $this->getInfo();
        $data['Terminal'] = [
            'ProvUserID' => $this->getProcessName(),
            'HashData' => $this->getTransactionHash(),
            'UserID' => $this->getProcessName(),
            'ID' => $this->getTerminalId(),
            'MerchantID' => $this->getMerchantId()
        ];
        $data['Customer'] = [
            'IPAddress' => $this->getClientIp(),
            'EmailAddress' => $this->getCard()->getEmail()
        ];

        $data['Card'] = [
            'Number' => $this->getCard()->getNumber(),
            'ExpireDate' => $this->getCard()->getExpiryDate('my')
        ];

        $data['Order'] = [
            'OrderID' => $this->getOrderId()
        ];

        $data['Transaction'] = [
            'Type' => $this->getProcessType(),
            'InstallmentCnt' => $this->getInstallment(),
            'Amount' => $this->getAmountInteger(),
            'CurrencyCode' => $this->currency_list[$this->getCurrency()],
            'CardholderPresentCode' => '0',
            'MotoInd' => 'N'
        ];

        return $data;
    }

    /**
     * @return array
     */
    protected function getSalesRequestParamsFor3d(): array
    {
        $installment = $this->getInstallment() ?? '';
        if ((int)$installment < 2) {
            $installment = '';
        }

        $expiryYear = \DateTime::createFromFormat('Y', $this->getCard()->getExpiryYear());
        $params['apiversion'] = $this->version;
        $params['mode'] = $this->getTestMode() ? 'TEST' : 'PROD';
        $params['terminalprovuserid'] = $this->getProcessName();
        $params['terminaluserid'] = $this->getProcessName();
        $params['terminalid'] = $this->getTerminalId();
        $params['terminalmerchantid'] = $this->getMerchantId();
        $params['txntype'] = 'sales';
        $params['txnamount'] = $this->getAmountInteger();
        $params['txncurrencycode'] = $this->currency_list[$this->getCurrency()];
        $params['txninstallmentcount'] = $installment;
        $params['customeremailaddress'] = $this->getCard()->getEmail();
        $params['customeripaddress'] = $this->getClientIp();
        $params['orderid'] = $this->getOrderId();
        $params['successurl'] = $this->getReturnUrl();
        $params['errorurl'] = $this->getCancelUrl();
        $params['lang'] = $this->getLang();
        $params['txntimestamp'] = date("d/m/Y H:i:s");
        $params['refreshtime'] = 5;
        $params['cardnumber'] = $this->getCard()->getNumber();
        $params['cardexpiredatemonth'] = sprintf("%02d", (string)$this->getCard()->getExpiryMonth());
        $params['cardexpiredateyear'] = $expiryYear->format('y');
        $params['cardcvv2'] = $this->getCard()->getCvv();
        $params['secure3dsecuritylevel'] = '3D';

        $hashContent = '';
        $hashContent .= $this->getTerminalId();
        $hashContent .= $params['orderid'];
        $hashContent .= $params['txnamount'];
        $hashContent .= $params['successurl'];
        $hashContent .= $params['errorurl'];
        $hashContent .= $params['txntype'];
        $hashContent .= $params['txninstallmentcount'];
        $hashContent .= $this->getSecureKey();
        $hashContent .= $this->getSecurityHash();



        $hashData = strtoupper(sha1($hashContent));
        $params['secure3dhash'] = $hashData;

        return $params;
    }

    /**
     * @return array
     */
    protected function getRefundRequestParams(): array
    {
        $data = $this->getInfo();
        $data['Terminal'] = [
            'ProvUserID' => $this->getProcessName(),
            'HashData' => $this->getTransactionHashRefundAndCancel(),
            'UserID' => $this->getProcessName(),
            'ID' => $this->getTerminalId(),
            'MerchantID' => $this->getMerchantId()
        ];

        $data['Customer'] = [
            'IPAddress' => $this->getClientIp()
        ];

        $data['Order'] = [
            'OrderID' => $this->getOrderId()
        ];

        $data['Transaction'] = [
            'Type' => $this->getProcessType(),
            'Amount' => $this->getAmountInteger(),
            'CurrencyCode' => $this->currency_list[$this->getCurrency()]
        ];

        return $data;
    }

    protected function setRequestParams(array $data): void
    {
        array_walk_recursive($data, [$this, 'updateValue']);
        $this->requestParams = $data;
    }

    protected function updateValue(&$data, $key): void
    {
        $sensitiveData = $this->getSensitiveData();

        if (\in_array($key, $sensitiveData, true)) {
            $data = Mask::mask($data);
        }

    }

    /**
     * @return array
     */
    protected function getRequestParams(): array
    {
        return [
            'url' => $this->getEndPoint(),
            'type' => $this->getProcessType(),
            'data' => $this->requestParams,
            'method' => $this->getHttpMethod()
        ];
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
    protected function getInfo(): array
    {
        $data['Version'] = $this->version;
        $data['Mode'] = $this->getTestMode() ? 'TEST' : 'PROD';

        return $data;
    }
}
