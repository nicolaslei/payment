<?php

namespace Stone\Pay\Provider\Alipay\Api\Traits;

use Stone\Pay\Provider\Alipay\Api\BizContent;

/**
 * Trait BizContentTrait
 * @package Stone\Pay\Provider\Alipay\Api\Traits
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
            $parameters = [$args[0] => $args[1]];
        } else {
            $parameters = $args[0];
        }

        $this->getBizContent()
            ->setParam($parameters);

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