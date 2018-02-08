<?php

namespace Stone\Pay\Weixin\Api;

/**
 * Class RefundQuery
 * @package Stone\Pay\Weixin\Api
 * @author  令狐冲 <nicolaslei@163.com>
 */
class RefundQuery extends AbstractApi
{
    /**
     * RefundQuery constructor.
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
        return '/pay/refundquery';
    }
}