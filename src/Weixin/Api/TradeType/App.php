<?php

namespace Stone\Pay\Weixin\Api\TradeType;

/**
 * Class App
 * @package Stone\Pay\Weixin\Api\TradeType
 * @author  令狐冲 <lhc@lianni.com>
 */
class App extends AbstractTradeType
{
    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->parameters = [
            'trade_type'  => 'APP',
            'device_info' => 'WEB'
        ];
    }
}