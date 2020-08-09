<?php
/**
 * Gvp Capture Response
 */

namespace Omnipay\Gvp\Messages;

use Exception;
use RuntimeException;

class CaptureResponse extends AbstractResponse
{
    public function getTransactionReference()
    {
        return $this->data['reference_number'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getClientId(): ?string
    {
        return $this->data['client_id'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->data['user_id'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getEncKey(): ?string
    {
        return $this->data['encryption_key'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getMacKey(): ?string
    {
        return $this->data['mac_key'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->data['phone'] ?? null;
    }


    /**
     * @return string
     */
    private function getTimeZone(): string
    {
        $p = date('P');
        $x = explode(':', $p);
        $dif = $x[0];
        $f = $dif[0];
        $s = substr($dif, 1);

        if ($f === '-') {
            $rTime = '8';
        } else {
            $rTime = '0';
        }

        $rTime .= dechex($s);

        return $rTime;
    }

    /**
     * @param string $value
     * @param int $pad
     * @return string
     */
    private function specPadLen(string $value, int $pad = 2): string
    {
        $len = strlen($value);
        $dLen = strtoupper(dechex($len));
        return str_pad($dLen, $pad, '0', STR_PAD_LEFT);
    }

    /**
     * @param string $str
     * @return string
     */
    private function specToBHex(string $str): string
    {
        return strtoupper(bin2hex($str));
    }

    /**
     * @return string
     */
    private function createValidPHone(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->getPhone());

        if (strpos($phone, '00') === 0) {
            $phone = substr($phone, 2);
        } elseif (strpos($phone, '0') === 0) {
            $phone = substr($phone, 1);
        }

        if (strlen($phone) === 10) {
            return '90' . $phone;
        }

        return $phone;
    }

}
