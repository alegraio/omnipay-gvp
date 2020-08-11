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
        $data = $this->getSalesRequestParams();
        $data['Transaction']['Type'] = 'postauth';

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

