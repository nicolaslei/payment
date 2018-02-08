<?php

namespace Stone\Pay\Provider\Weixin\SignType;

/**
 * MD5加密
 *
 * @package Stone\Pay\Provider\Weixin\SignType
 * @author  令狐冲 <nicolaslei@163.com>
 */
class Md5 extends AbstractSignType
{
    /**
     * 返回加密类型
     *
     * @return string
     */
    public function getSignType()
    {
        return 'MD5';
    }

    /**
     * 签名
     *
     * @param $string
     * @param $key
     *
     * @return string
     */
    public function sign($string, $key)
    {
        return strtoupper(md5($string));
    }
}