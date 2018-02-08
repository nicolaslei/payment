<?php

namespace Stone\Pay\Provider\Alipay\Api;

/**
 * Class TradeAppPay
 * @package Stone\Pay\Provider\Alipay\Api
 * @author  令狐冲 <lhc@lianni.com>
 */
class TradeAppPay extends AbstractApi
{
    /**
     * TradeAppPay constructor.
     *
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
            'subject'      => $subject,
            'total_amount' => $totalAmount,
            'product_code' => 'QUICK_MSECURITY_PAY',
        ]);
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'alipay.trade.app.pay';
    }
}