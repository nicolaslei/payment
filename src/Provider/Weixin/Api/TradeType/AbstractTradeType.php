<?php

namespace Stone\Pay\Provider\Weixin\Api\TradeType;
use Stone\Pay\Provider\Weixin\SignType\SignTypeInterface;

/**
 * Class AbstractTradeType
 * @package Stone\Pay\Provider\Weixin\Api\TradeType
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

    /**
     * @param array $data
     * @param SignTypeInterface $signType
     * @param $appSecret
     *
     * @return array
     */
    public function responseHandle(array $data, SignTypeInterface $signType, $appSecret)
    {
        return $data;
    }
}