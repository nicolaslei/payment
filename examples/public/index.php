<?php

use Slim\App;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../../vendor/autoload.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new App($configuration);

$app->get(
    '/',
    function (ServerRequestInterface $request, ResponseInterface $response) use ($app) {

        $tradePrecreate = new \Stone\Pay\Alipay\Api\TradePrecreate(
            'http://www.abc.com','123456789442', 30.22, '连你订水-支付测试'
        );

        $server = new \Stone\Pay\Alipay\Server(
            '2016102000728056',
            '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',
            $tradePrecreate
        );

        $server->enableSandboxPattern(
            '2016102000728056',
            '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'
        );

        var_dump($server->request());
    }
);

$app->get(
    '/weixin',
    function () {
        try {
            $api = new \Stone\Pay\Weixin\Api\UnifiedOrder(
                new \Stone\Pay\Weixin\Api\TradeType\Native('11111111'),
                'http://www.abc.com',
                '1231456',
                '阿斯多夫',
                33
            );

            $server = new \Stone\Pay\Weixin\Server(
                'wx98bf309e38efa80e',
                'ae80886801c16ddf9d741f60560e1195',
                1424257102,
                $api
            );

            $response = $server->enableSandboxPattern()->request();
            var_dump($response);exit;
        } catch (\Stone\Pay\Weixin\Exception\WeiXinException $e) {
            echo $e->getMessage();
        }
    }
);

$app->run();
