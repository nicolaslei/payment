<?php

namespace Stone\Pay\Weixin\SignType;

use Stone\Pay\Weixin\Exception\WeiXinException;

/**
 * Class Factory
 * @package Stone\Pay\Weixin\SignType
 * @author  令狐冲 <nicolaslei@163.com>
 */
class Factory
{
    /**
     * @param string $signType
     * @return Md5|Sha256
     * @throws WeiXinException
     */
    public static function load($signType = 'MD5')
    {
        $signType = strtoupper($signType);
        switch ($signType) {
            case 'MD5':
                return new Md5();
            case 'HMAC-SHA256':
                return new Sha256();
            default:
                throw new WeiXinException(
                    sprintf("签名方式[%s]不存在", $signType)
                );
        }
    }
}