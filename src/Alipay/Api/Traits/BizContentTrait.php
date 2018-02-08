<?php

namespace Stone\Pay\Alipay\Api\Traits;

use Stone\Pay\Alipay\Api\BizContent;

/**
 * Trait BizContentTrait
 * @package Stone\Pay\Alipay\Api\Traits
 */
trait BizContentTrait
{
    /**
     * @var BizContent
     */
    protected static $bizContent;

    /**
     * @param array ...$args
     * @return $this
     */
    public function setBizContentParam(...$args)
    {
        if (!is_array($args[0])) {
            $args = [$args[0] => $args[1]];
        }

        $this->getBizContent()->setParam($args);

        return $this;
    }

    /**
     * @return BizContent
     */
    protected function getBizContent()
    {
        if (!self::$bizContent instanceof BizContent) {
            self::$bizContent = new BizContent();
        }

        return self::$bizContent;
    }
}