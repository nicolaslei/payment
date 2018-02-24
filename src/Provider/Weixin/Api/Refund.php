<?php

namespace Stone\Pay\Provider\Weixin\Api;

/**
 * Class Refund
 * @package Stone\Pay\Provider\Weixin\Api
 * @author  令狐冲 <nicolaslei@163.com>
 */
class Refund extends AbstractApi
{
    /**
     * Refund constructor.
     * @param string $apiClientCertPath
     * @param string $apiClientKeyPath
     * @param string|integer $outTradeNo 商户订单号
     * @param string|integer $outRefundNo 商户系统内部的退款单号，商户系统内部唯一，只能是数字、大小写字母_-|*@ ，同一退款单号多次请求只退一笔。
     * @param integer $totalFee 订单总金额，单位为分，只能为整数
     */
    public function __construct($apiClientCertPath, $apiClientKeyPath, $outTradeNo, $outRefundNo, $totalFee)
    {
        $this->setParam('out_trade_no', $outTradeNo);
        $this->setParam('out_refund_no', $outRefundNo);
        $this->setParam('total_fee', $totalFee);
        // 默认全部退款
        $this->setParam('refund_fee', $totalFee);

        $this->apiClientCertPath = $apiClientCertPath;
        $this->apiClientKeyPath  = $apiClientKeyPath;
    }

    /**
     * 设置退款金额.
     * 单位为分，只能为整数.
     *
     * @param $refundFee
     * @return void
     */
    public function setRefundFee($refundFee)
    {
        $this->setParam('refund_fee', $refundFee);
    }

    /**
     * 退款原因
     * 若商户传入，会在下发给用户的退款消息中体现退款原因.
     *
     * @param string $refundDesc
     */
    public function setRefundDesc($refundDesc)
    {
        $this->setParam('refund_desc', $refundDesc);
    }


    /**
     * @return string
     */
    protected function getApiUri()
    {
        return '/secapi/pay/refund';
    }
}