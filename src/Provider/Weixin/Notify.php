<?php

namespace Stone\Pay\Provider\Weixin;

use Stone\Pay\NotifyInterface;
use Stone\Pay\TradeInterface;
use Stone\Pay\Provider\Weixin\SignType\Factory as SignFactory;

class Notify implements NotifyInterface
{
    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $mchId;

    /**
     * @var string
     */
    private $appSecret;

    private $response;

    /**
     * Notify constructor.
     * @param $appId
     * @param $appSecret
     * @param $mchId
     */
    public function __construct($appId, $appSecret, $mchId)
    {
        $this->appId     = $appId;
        $this->mchId     = $mchId;
        $this->appSecret = $appSecret;

        $data = @file_get_contents('php://input');
        $this->response = Helper::xmlToArray($data);
    }

    /**
     * @param TradeInterface|null $trade
     * @return array|bool
     * @throws \Stone\Pay\Exception\InvalidArgumentException
     */
    public function verify(TradeInterface $trade = null)
    {
        $response = $this->response;
        if (!empty($response)) {
            // 参数正确
            if ($response['appid'] == $this->appId && $response['mch_id'] == $this->mchId) {
                // 通信状态成功
                if ($response['return_code'] == 'SUCCESS') {
                    // 默认MD5加密
                    $signType = empty($response['sign_type']) ? 'MD5' : $response['sign_type'];

                    $sign = SignFactory::load($signType)
                        ->generateSignature($response, $this->appSecret);

                    // 签名验证成功&&支付成功
                    if ($sign == $response['sign'] && $response['result_code'] == 'SUCCESS') {
                        if ($trade instanceof TradeInterface && $trade->getTradeTotalAmount() == $response['total_fee']) {
                            return $response;
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * @return array|bool
     * @throws \Stone\Pay\Exception\InvalidArgumentException
     */
    public function verifySign()
    {
        $response = $this->response;
        if (empty($response)) {
            // 参数正确
            if ($response['appid'] == $this->appId && $response['mch_id'] == $this->mchId) {
                // 通信状态成功
                if ($response['return_code'] == 'SUCCESS') {
                    // 默认MD5加密
                    $signType = empty($response['sign_type']) ? 'MD5' : $response['sign_type'];

                    $sign = SignFactory::load($signType)
                        ->generateSignature($response, $this->appSecret);

                    if ($sign == $response['sign'] && $response['result_code'] == 'SUCCESS') {
                        return $response;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function replySuccess()
    {
        return Helper::arrayToXml(
            [
                'return_code' => 'SUCCESS'
            ]
        );
    }

    /**
     * @return string
     */
    public function replyFail()
    {
        return Helper::arrayToXml(
            [
                'return_code' => 'FAIL',
            ]
        );
    }

    public function verifyStatus()
    {
        // TODO: Implement verifyStatus() method.
    }

    public function verifyTrade(TradeInterface $trade)
    {
        // TODO: Implement verifyTrade() method.
    }
}