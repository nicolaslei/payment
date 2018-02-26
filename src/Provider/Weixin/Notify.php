<?php

namespace Stone\Pay\Provider\Weixin;

use Stone\Pay\Exception\InvalidArgumentException;
use Stone\Pay\LoggerTrait;
use Stone\Pay\NotifyInterface;
use Stone\Pay\TradeInterface;
use Stone\Pay\Provider\Weixin\SignType\Factory as SignFactory;

class Notify implements NotifyInterface
{
    use LoggerTrait;

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

    /**
     * @var array
     */
    private $response;

    /**
     * Notify constructor.
     * @param $appId
     * @param $appSecret
     * @param $mchId
     * @throws InvalidArgumentException
     */
    public function __construct($appId, $appSecret, $mchId)
    {
        $this->appId     = $appId;
        $this->mchId     = $mchId;
        $this->appSecret = $appSecret;

        $data = @file_get_contents('php://input');
        $data = Helper::xmlToArray($data);

        if (empty($data) || !is_array($data)) {
            throw new InvalidArgumentException('微信支付异步回调接收不到数据');
        }

        // 日志
        $this->recordDebugLog(
            sprintf(
                '微信支付异步回调通知数据[%s]',
                var_export($data, true)
            )
        );

        $this->response = $data;
    }

    /**
     * @param TradeInterface|null $trade
     * @return array|bool
     * @throws \Stone\Pay\Exception\InvalidArgumentException
     */
    public function verify(?TradeInterface $trade)
    {
        $response = $this->response;
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
                    if ($trade instanceof TradeInterface) {
                        if ($trade->getTradeTotalAmount() == $response['total_fee'])
                            return $response;
                    } else {
                        return $response;
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
     * @return void
     */
    public function replySuccess()
    {
        echo Helper::arrayToXml([
            'return_code' => 'SUCCESS'
        ]);
        exit;
    }

    /**
     * @return void
     */
    public function replyFail()
    {
        echo Helper::arrayToXml([
            'return_code' => 'FAIL',
        ]);
        exit;
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