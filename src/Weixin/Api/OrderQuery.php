<?php

namespace Stone\Pay\Weixin\Api;

/**
 * Class OrderQuery
 * @package Stone\Pay\Weixin\Api
 * @author  令狐冲 <nicolaslei@163.com>
 */
class OrderQuery extends AbstractApi
{
    /**
     * OrderQuery constructor.
     * @param $outTradeNo
     */
    public function __construct($outTradeNo)
    {
        $this->setParam('out_trade_no', $outTradeNo);
    }

    /**
     * @param $transactionId
     */
    public function setTransactionId($transactionId)
    {
        // out_trade_no|out_trade_no 只能二选一
        unset($this->parameters['out_trade_no']);

        $this->setParam('transaction_id', $transactionId);
    }

    /**
     * @return string
     */
    protected function getApiUri()
    {
        return '/pay/orderquery';
    }
}