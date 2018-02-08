<?php

namespace Stone\Pay\Provider\Alipay\SignType;

use Stone\Pay\Exception\InvalidArgumentException;

/**
 * Class Factory
 * @package Stone\Pay\Provider\Alipay\SignType
 * @author  令狐冲 <nicolaslei@163.com>
 */
class Factory
{
    /**
     * @param string $signType
     * @return Rsa|Rsa2
     * @throws InvalidArgumentException
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
                throw new InvalidArgumentException(
                    sprintf("签名方式[%s]不存在", $signType)
                );
        }
    }
}