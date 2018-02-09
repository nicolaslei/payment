<?php

namespace Stone\Pay\Provider\Alipay\Api;

/**
 * 统一收单交易退款接口
 *
 * @package Stone\Pay\Provider\Alipay\Api
 * @author  令狐冲 <nicolaslei@163.com>
 */
class TradeRefund extends AbstractApi
{
    /**
     * TradeRefund constructor.
     *
     * @param $notifyUrl
     * @param $outTradeNo
     * @param $refundAmount
     */
    public function __construct($outTradeNo, $refundAmount)
    {
        $this->setBizContentParam([
            'out_trade_no'  => $outTradeNo,
            'refund_amount' => $refundAmount,
        ]);
    }

    /**
     * 退款的原因说明
     *
     * @param $refundReason
     */
    public function setRefundReason($refundReason)
    {
        $this->setBizContentParam('refund_reason', $refundReason);
    }

    /**
     * 标识一次退款请求.
     * 同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传.
     *
     * @param $outRequestNo
     */
    public function setOutRequestNo($outRequestNo)
    {
        $this->setBizContentParam('out_request_no', $outRequestNo);
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'alipay.trade.refund';
    }
}