<?php

namespace Stone\Pay\Provider\Weixin\Api\TradeType;

use Stone\Pay\Provider\Weixin\Helper;
use Stone\Pay\Provider\Weixin\SignType\SignTypeInterface;

/**
 * Class App
 * @package Stone\Pay\Provider\Weixin\Api\TradeType
 * @author  令狐冲 <lhc@lianni.com>
 */
class App extends AbstractTradeType
{
    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->parameters = [
            'trade_type'  => 'APP',
            'device_info' => 'WEB'
        ];
    }

    /**
     * @param array $data
     * @param SignTypeInterface $sign
     * @param $appSecret
     *
     * @return array
     */
    public function responseHandle(array $data, SignTypeInterface $sign, $appSecret)
    {
        $response = [
            'appid'     => $data['appid'],
            'partnerid' => $data['mch_id'],
            'prepayid'  => $data['prepay_id'],
            'package'   => 'Sign=WXPay',
            'noncestr'  => Helper::generateNonceStr(),
            'timestamp' => strval(time())
        ];

        $response['sign'] = $sign->generateSignature($response, $appSecret);

        return $response;
    }
}