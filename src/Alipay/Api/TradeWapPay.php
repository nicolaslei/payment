<?php

namespace Stone\Pay\Alipay\Api;

/**
 * 手机网站支付
 *
 * @package Stone\Pay\Alipay\Api
 * @author  令狐冲 <lhc@lianni.com>
 */
class TradeWapPay extends AbstractApi
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
            'product_code' => 'QUICK_WAP_WAY',
        ]);
    }

    /**
     * 添加该参数后在h5支付收银台会出现返回按钮，可用于用户付款中途退出并返回到该参数指定的商户网站地址.
     * 注：该参数对支付宝钱包标准收银台下的跳转不生效.
     *
     * @param $quitUrl
     */
    public function setQuitUrl($quitUrl)
    {
        $this->setBizContentParam('quit_url', $quitUrl);
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'alipay.trade.wap.pay';
    }
}