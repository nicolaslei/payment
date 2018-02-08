<?php

namespace Stone\Pay\Provider\Alipay\Api;

/**
 * Class BizContent
 * @package Stone\Pay\Provider\Alipay\Api
 * @author  令狐冲 <nicolaslei@163.com>
 */
class BizContent
{
    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @param $args
     */
    public function setParam($args)
    {
        if (is_array($args)) {
            $this->parameters = array_merge($this->parameters, $args);
        }
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->parameters);
    }
}