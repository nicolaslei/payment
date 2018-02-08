<?php

namespace Stone\Pay\Provider\Weixin\SignType;

/**
 * Class AbstractSignType
 * @package Stone\Pay\Provider\Weixin\SignType
 * @author  令狐冲 <nicolaslei@163.com>
 */
abstract class AbstractSignType implements SignTypeInterface
{
    /**
     * 签名
     *
     * @param $string
     * @param $key
     *
     * @return mixed
     */
    abstract public function sign($string, $key);

    /**
     * 创建签名
     *
     * @param array $data
     * @param       $key
     * @return mixed
     */
    public function generateSignature(array $data, $key)
    {
        // sign不参与签名
        unset($data['sign']);

        ksort($data);
        reset($data);

        $string = "";

        foreach ($data as $k => $v) {
            if (!empty($v)) {
                $string .= $k . "=" . $v . "&";
            }
        }

        $string .= "key=" . $key;

        return $this->sign($string, $key);
    }
}