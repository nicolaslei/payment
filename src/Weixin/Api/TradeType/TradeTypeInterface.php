<?php

namespace Stone\Pay\Weixin\Api\TradeType;

use Stone\Pay\Weixin\ApiParameters;

/**
 * Interface TradeTypeInterface
 * @package Stone\Pay\Weixin\Api\TradeType
 */
interface TradeTypeInterface
{
    /**
     * @return array
     */
    public function getParameters();
}