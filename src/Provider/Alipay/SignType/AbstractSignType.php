<?php

namespace Stone\Pay\Provider\Alipay\SignType;

use Stone\Pay\Provider\Alipay\Helper;

/**
 * Class AbstractSignType
 * @package Stone\Pay\Provider\Alipay\SignType
 * @author  令狐冲 <nicolaslei@163.com>
 */
abstract class AbstractSignType implements SignTypeInterface
{
    /**
     * @param $string
     * @param $res
     *
     * @return string
     */
    abstract protected function sign($string, $res);

    /**
     * @param $string
     * @param $keyId
     * @param $sign
     *
     * @return boolean
     */
    abstract protected function verify($string, $keyId, $sign);

    /**
     * @param array $data
     * @param $privateKey
     * @return mixed
     */
    public function generateSignature(array $data, $privateKey)
    {
        // sign不参与签名
        unset($data['sign']);

        ksort($data);
        reset($data);

        $string = Helper::implode($data);

        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($privateKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";

        return $this->sign($string, $res);
    }

    /**
     * 校验签名
     *
     * @param $string
     * @param $publicKey
     * @param $sign
     *
     * @return bool
     */
    public function verifySign($string, $publicKey, $sign)
    {
        $keyId = "-----BEGIN PUBLIC KEY-----\n"
            . wordwrap($publicKey, 64, "\n", true)
            . "\n-----END PUBLIC KEY-----";

        return $this->verify($string, $keyId, base64_decode($sign));
    }
}