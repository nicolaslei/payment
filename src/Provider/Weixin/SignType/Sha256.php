<?php

namespace Stone\Pay\Provider\Weixin\SignType;

/**
 * Sha256加密
 *
 * @package Stone\Pay\Provider\Weixin\SignType
 * @author  令狐冲 <lhc@lianni.com>
 */
class Sha256 extends AbstractSignType
{
    /**
     * 返回签名方式
     *
     * @return string
     */
    public function getSignType()
    {
        return 'HMAC-SHA256';
    }

    /**
     * 签名
     *
     * @param $string
     * @param $key
     *
     * @return string sign string
     */
    public function sign($string, $key)
    {
        return strtoupper(
            hash_hmac('sha256', $string, $key)
        );
    }
}