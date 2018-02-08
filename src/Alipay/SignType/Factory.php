<?php

namespace Stone\Pay\Alipay\SignType;

use Stone\Pay\Alipay\Exception\AlipayException;

/**
 * Class Factory
 * @package Stone\Pay\Alipay\SignType
 * @author  令狐冲 <nicolaslei@163.com>
 */
class Factory
{
    /**
     * @param string $signType
     * @return Rsa|Rsa2
     * @throws AlipayException
     */
    public static function load($signType = 'RSA2')
    {
        $signType = strtoupper($signType);
        switch ($signType) {
            case 'RSA2':
                return new Rsa2();
            case 'HMAC-SHA256':
                return new Rsa();
            default:
                throw new AlipayException(
                    sprintf("签名方式[%s]不存在", $signType)
                );
        }
    }
}