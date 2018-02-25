<?php

namespace Stone\Pay\Provider\Alipay\Api;

/**
 * 统一收单线下交易预创建
 *
 * @package Stone\Pay\Provider\Alipay\Api
 * @author  令狐冲 <nicolaslei@163.com>
 */
class TradePrecreate extends AbstractApi
{
    /**
     * TradePrecreate constructor.
     * @param $notifyUrl
     * @param $outTradeNo
     * @param $totalAmount
     * @param $subject
     */
    public function __construct($notifyUrl, $outTradeNo, $totalAmount, $subject)
    {
        $this->setParam('notify_url', $notifyUrl);
        $this->setBizContentParam([
            'out_trade_no' => $outTradeNo,
            'total_amount' => $totalAmount,
            'subject'      => $subject
        ]);
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'alipay.trade.precreate';
    }
}