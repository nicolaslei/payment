<?php

namespace Stone\Pay\Provider\Weixin\Api\TradeType;

/**
 * Class Jsapi
 * @package Stone\Pay\Provider\Weixin\Api\TradeType
 * @author  令狐冲 <nicolaslei@163.com>
 */
class Jsapi extends AbstractTradeType
{
    /**
     * Jsapi constructor.
     * @param $openid
     */
    public function __construct($openid)
    {
        $this->parameters = [
            'trade_type'  => 'JSAPI',
            'device_info' => 'WEB',
            'openid'      => $openid
        ];
    }
}