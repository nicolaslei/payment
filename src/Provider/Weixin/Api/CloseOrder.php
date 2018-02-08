<?php

namespace Stone\Pay\Provider\Weixin\Api;

/**
 * Class CloseOrder
 * @package Stone\Pay\Provider\Weixin\Api
 * @author  令狐冲 <nicolaslei@163.com>
 */
class CloseOrder extends AbstractApi
{
    /**
     * CloseOrder constructor.
     * @param $outTradeNo
     */
    public function __construct($outTradeNo)
    {
        $this->setParam('out_trade_no', $outTradeNo);
    }

    /**
     * @return string
     */
    protected function getApiUri()
    {
        return '/pay/closeorder';
    }
}