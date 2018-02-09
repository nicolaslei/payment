<?php

namespace Stone\Pay\Provider\Weixin\Api\TradeType;

use Stone\Pay\Provider\Weixin\SignType\SignTypeInterface;

/**
 * Class Native
 * @package Stone\Pay\Provider\Weixin\Api\TradeType
 * @author  令狐冲 <nicolaslei@163.com>
 */
class Native extends AbstractTradeType
{
    /**
     * Native constructor.
     * @param $product
     */
    public function __construct($product)
    {
        $this->parameters = [
            'trade_type'       => 'NATIVE',
            'product_id'       => $product
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

        // 二维码地址不需要参与签名
        $response['code_url'] = $data['code_url'];

        return $response;
    }
}