<?php

namespace Stone\Pay\Weixin\Api;

use Stone\Pay\Weixin\ApiParameters;

/**
 * Class Refund
 * @package Stone\Pay\Weixin\Api
 * @author  令狐冲 <nicolaslei@163.com>
 */
class Refund extends AbstractApi
{
    /**
     * Refund constructor.
     * @param $transactionId 微信生成的订单号，在支付通知中有返回
     * @param $outRefundNo 商户系统内部的退款单号，商户系统内部唯一，只能是数字、大小写字母_-|*@ ，同一退款单号多次请求只退一笔。
     * @param $totalFee 订单总金额，单位为分，只能为整数
     * @param $refundFee 退款总金额，订单总金额，单位为分，只能为整数
     */
    public function __construct($transactionId, $outRefundNo, $totalFee, $refundFee = null)
    {
        $this->setParam('transaction_id', $transactionId);
        $this->setParam('out_refund_no', $outRefundNo);
        $this->setParam('total_fee', $totalFee);

        if ($refundFee === null) {
            $refundFee = $totalFee;
        }

        $this->setParam('refund_fee', $refundFee);
    }

    /**
     * @return string
     */
    protected function getApiUri()
    {
        return '/secapi/pay/refund';
    }
}