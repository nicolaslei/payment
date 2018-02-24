<?php

namespace Stone\Pay\Provider\Weixin\Api;

use Stone\Pay\Provider\Weixin\Api\TradeType\TradeTypeInterface;

/**
 * Class UnifiedOrder
 * @package Stone\Pay\Provider\Weixin\Api
 * @author  令狐冲 <nicolaslei@163.com>
 */
class UnifiedOrder extends AbstractApi
{
    /**
     * @var TradeTypeInterface
     */
    protected $tradeType;

    /**
     * UnifiedOrder constructor.
     * @param TradeTypeInterface $tradeType
     * @param string $notifyUrl
     * @param string $outTradeNo
     * @param string $body
     * @param integer $totalFee
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

        $this->tradeType = $tradeType;
    }

    /**
     * @return array|bool|mixed
     * @throws \Stone\Pay\Exception\ApiResponseException
     * @throws \Stone\Pay\Exception\InvalidArgumentException
     * @throws \Stone\Pay\Exception\SignatureValidationException
     */
    public function request()
    {
        $response = parent::request();

        return $this->tradeType->responseHandle($response, $this->signType, $this->appSecret);
    }

    /**
     * @param $feeType
     * @return $this
     */
    public function setFeeType($feeType)
    {
        $this->setParam('fee_type', $feeType);

        return $this;
    }

    /**
     * @param $attach
     * @return $this
     */
    public function setAttach($attach)
    {
        $this->setParam('attach ', $attach);

        return $this;
    }

    /**
     * @param $timeStart
     * @return $this
     */
    public function setTimeStart($timeStart)
    {
        $this->setParam('time_start', $timeStart);

        return $this;
    }

    /**
     * @param $timeExpire
     * @return $this
     */
    public function setTimeExpire($timeExpire)
    {
        $this->setParam('time_expire', $timeExpire);

        return $this;
    }

    /**
     * @return $this
     */
    public function setLimitPay()
    {
        $this->setParam('limit_pay', 'no_credit');

        return $this;
    }

    /**
     * @param $detail
     * @return $this
     */
    public function setGoodsDetail($detail)
    {
        $this->setParam('detail', $detail);

        return $this;
    }

    /**
     * @param $goodsTag
     * @return $this
     */
    public function setGoodsTag($goodsTag)
    {
        $this->setParam('goods_tag', $goodsTag);

        return $this;
    }

    /**
     * @param $spBillCreateIp
     * @return $this
     */
    public function setSpBillCreateIp($spBillCreateIp)
    {
        $this->setParam('spbill_create_ip', $spBillCreateIp);

        return $this;
    }

    /**
     * @return string
     */
    protected function getApiUri()
    {
        return '/pay/unifiedorder';
    }
}