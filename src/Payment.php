<?php

namespace Stone\Pay;

use Stone\Pay\Weixin\Server;

class Payment
{
    private static $instance;

    public static function load()
    {
        if (!self::$instance instanceof Payment) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function weixin($appId, $appSecret, $mchId)
    {
        return new Server($appId, $appSecret, $mchId);
    }

    public function alipay()
    {

    }
}