<?php

namespace Stone\Pay\Weixin\Api;

use Stone\Pay\Weixin\Api\TradeType\TradeTypeInterface;

/**
 * Class UnifiedOrder
 * @package Stone\Pay\Weixin\Api
 * @author  令狐冲 <nicolaslei@163.com>
 */
class UnifiedOrder extends AbstractApi
{
    /**
     * UnifiedOrder constructor.
     * @param TradeTypeInterface $tradeType
     * @param                    $notifyUrl
     * @param                    $outTradeNo
     * @param                    $body
     * @param                    $totalFee
     */
    public function __construct(
        TradeTypeInterface $tradeType,
        $notifyUrl,
        $outTradeNo,
        $body,
        $totalFee
    )
    {
        $this->parameters = array_merge(
            $this->parameters,
            $tradeType->getParameters()
        );

        $this->setParam('out_trade_no', $outTradeNo);
        $this->setParam('body', $body);
        $this->setParam('total_fee', $totalFee);
        $this->setParam('notify_url', $notifyUrl);
    }

    public function setFeeType($feeType)
    {
        $this->setParam('fee_type', $feeType);

        return $this;
    }

    public function setAttach($attach)
    {
        $this->setParam('attach ', $attach);

        return $this;
    }

    public function setTimeStart($timeStart)
    {
        $this->setParam('time_start', $timeStart);

        return $this;
    }

    public function setTimeExpire($timeExpire)
    {
        $this->setParam('time_expire', $timeExpire);

        return $this;
    }

    public function setLimitPay()
    {
        $this->setParam('limit_pay', 'no_credit');

        return $this;
    }

    public function setGoodsDetail($detail)
    {
        $this->setParam('detail', $detail);

        return $this;
    }

    public function setGoodsTag($goodsTag)
    {
        $this->setParam('goods_tag', $goodsTag);

        return $this;
    }

    public function setSpBillCreateIp($spBillCreateIp)
    {
        $this->setParam('spbill_create_ip', $spBillCreateIp);

        return $this;
    }

    protected function getApiUri()
    {
        return '/pay/unifiedorder';
    }
}