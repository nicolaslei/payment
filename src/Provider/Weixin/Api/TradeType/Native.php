<?php

namespace Stone\Pay\Provider\Weixin\Api\TradeType;

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
}