<?php

namespace Stone\Pay\Provider\Alipay\SignType;

/**
 * Class Rsa
 * @package Stone\Pay\Provider\Alipay\SignType
 * @author  令狐冲 <nicolaslei@163.com>
 */
class Rsa extends AbstractSignType
{
    /**
     * @var string
     */
    const TYPE_ID = 'RSA';

    /**
     * @param $string
     * @param $res
     * @return string
     */
    protected function sign($string, $res)
    {
        openssl_sign($string, $sign, $res);

        return base64_encode($sign);
    }

    /**
     * @param $string
     * @param $keyId
     * @param $sign
     * @return bool
     */
    protected function verify($string, $keyId, $sign)
    {
        return (bool)openssl_verify($string, base64_decode($sign), $keyId);
    }

    /**
     * @return string
     */
    public function getSignType()
    {
        return self::TYPE_ID;
    }
}