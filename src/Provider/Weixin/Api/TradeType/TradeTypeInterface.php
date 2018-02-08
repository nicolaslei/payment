<?php

namespace Stone\Pay\Provider\Weixin\Api\TradeType;

use Stone\Pay\Provider\Weixin\SignType\SignTypeInterface;

/**
 * Interface TradeTypeInterface
 * @package Stone\Pay\Provider\Weixin\Api\TradeType
 */
interface TradeTypeInterface
{
    /**
     * @return array
     */
    public function getParameters();

    /**
     * @param array $data
     * @param SignTypeInterface $signType
     * @param $appSecret
     *
     * @return array
     */
    public function responseHandle(array $data, SignTypeInterface $signType, $appSecret);
}