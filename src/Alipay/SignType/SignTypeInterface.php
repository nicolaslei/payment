<?php

namespace Stone\Pay\Alipay\SignType;

/**
 * Interface SignTypeInterface
 * @package Stone\Pay\Weixin\SignType
 */
interface SignTypeInterface
{
    /**
     * 签名
     *
     * @param array $data
     * @param       $key
     * @return mixed
     */
    public function generateSignature(array $data, $privateKey);

    /**
     * @param $string
     * @param $publicKey
     * @param $sign
     * @return bool
     */
    public function verifySign($string, $publicKey, $sign);

    /**
     * @return string
     */
    public function getSignType();
}