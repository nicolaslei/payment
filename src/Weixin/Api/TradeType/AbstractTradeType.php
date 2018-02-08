<?php

namespace Stone\Pay\Weixin\Api\TradeType;

/**
 * Class AbstractTradeType
 * @package Stone\Pay\Weixin\Api\TradeType
 * @author  令狐冲 <nicolaslei@163.com>
 */
abstract class AbstractTradeType implements TradeTypeInterface
{
    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}