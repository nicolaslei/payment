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
    public function __construct($notifyUrl, $outTradeNo, $refundAmount)
    {
        $this->setParam('notify_url', $notifyUrl);
        $this->setBizContentParam([
            'out_trade_no'  => $outTradeNo,
            'refund_amount' => $refundAmount,
        ]);
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'alipay.trade.refund';
    }
}