<?php
/**
 * Gvp Capture Request
 */

namespace Omnipay\Gvp\Messages;


class CaptureRequest extends AbstractRequest
{

    /**
     * @return array
     */
    public function getData(): array
    {
        $this->setPaymentType();
        $data = $this->getAuthorizeRequestParams();
        $data['Transaction']['Type'] = 'postauth';
        $data['Card']['CVV2'] = $this->getCard()->getCvv();

        return $data;
    }

    /**
     * @param $data
     * @return CaptureResponse
     */
    protected function createResponse($data): CaptureResponse
    {
        return new CaptureResponse($this, $data);
    }
}

