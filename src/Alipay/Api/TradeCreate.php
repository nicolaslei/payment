<?php

namespace Stone\Pay\Alipay\Api;

/**
 * 统一收单交易创建接口
 *
 * @package Stone\Pay\Alipay\Api
 * @author  令狐冲 <lhc@lianni.com>
 */
class TradeCreate extends AbstractApi
{
    /**
     * TradeCreate constructor.
     *
     * @param $outTradeNo
     * @param $totalAmount
     * @param $subject
     */
    public function __construct($outTradeNo, $totalAmount, $subject)
    {
        $this->setBizContentParam([
            'out_trade_no' => $outTradeNo,
            'subject'      => $subject,
            'total_amount' => $totalAmount,
        ]);
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'alipay.trade.create';
    }
}