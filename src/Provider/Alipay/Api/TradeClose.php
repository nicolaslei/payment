<?php

namespace Stone\Pay\Provider\Alipay\Api;

/**
 * 统一收单交易关闭接口
 *
 * @package Stone\Pay\Provider\Alipay\Api
 * @author  令狐冲 <nicolaslei@163.com>
 */
class TradeClose extends AbstractApi
{
    /**
     * TradeClose constructor.
     *
     * @param $outTradeNo
     */
    public function __construct($outTradeNo)
    {
        $this->setBizContentParam([
            'out_trade_no' => $outTradeNo
        ]);
    }

    /**
     * 设置支付宝系统中的交易流水号
     *
     * @param $tradeNo
     */
    public function setTradeNo($tradeNo)
    {
        $this->setBizContentParam('trade_no', $tradeNo);
    }

    /**
     * 设置卖家端自定义的的操作员 ID
     *
     * @param $operatorId
     */
    public function setOperatorId($operatorId)
    {
        $this->setBizContentParam('operator_id', $operatorId);
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'alipay.trade.pay';
    }
}