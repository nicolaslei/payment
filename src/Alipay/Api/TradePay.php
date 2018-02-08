<?php

namespace Stone\Pay\Alipay\Api;

/**
 * 统一收单交易支付接口
 *
 * @package Stone\Pay\Alipay\Api
 * @author  令狐冲 <nicolaslei@163.com>
 */
class TradePay extends AbstractApi
{
    /**
     * TradePay constructor.
     *
     * @param $notifyUrl
     * @param $outTradeNo
     * @param $totalAmount
     * @param $subject
     * @param $scene
     */
    public function __construct($notifyUrl, $outTradeNo, $totalAmount, $subject, $scene)
    {
        $this->setParam('notify_url', $notifyUrl);
        $this->setBizContentParam([
            'out_trade_no' => $outTradeNo,
            'subject'      => $subject,
            'total_amount' => $totalAmount,
            'scene'        => $scene,
        ]);
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'alipay.trade.pay';
    }
}