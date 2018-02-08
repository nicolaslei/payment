<?php

namespace Stone\Pay\Provider\Alipay;

/**
 * Class Helper
 * @package Stone\Pay\Provider\Alipay
 * @author  令狐冲 <nicolaslei@163.com>
 */
class Helper
{
    /**
     * @param array $data
     *
     * @return string
     */
    public static function implode(array $data)
    {
        $string = '';

        foreach ($data as $k => $v) {
            if (!empty($v) && is_string($v)) {
                $string .= $k . "=" . $v . "&";
            }
        }

        return rtrim($string, "&");
    }
}