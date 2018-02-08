<?php

namespace Stone\Pay\Provider\Alipay\SignType;

/**
 * Rsa2签名
 *
 * @package Stone\Pay\Provider\Alipay\SignType
 * @author  令狐冲 <nicolaslei@163.com>
 */
class Rsa2 extends AbstractSignType
{
    /**
     * @param $string
     * @param $res
     *
     * @return string
     */
    protected function sign($string, $res)
    {
        openssl_sign($string, $sign, $res, OPENSSL_ALGO_SHA256);

        return base64_encode($sign);
    }

    /**
     * @param $string
     * @param $keyId
     * @param $sign
     *
     * @return bool
     */
    protected function verify($string, $keyId, $sign)
    {
        return (bool)openssl_verify($string, $sign, $keyId, OPENSSL_ALGO_SHA256);
    }

    /**
     * @return string
     */
    public function getSignType()
    {
        return 'RSA2';
    }
}