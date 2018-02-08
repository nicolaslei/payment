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

        $tradePrecreate = new \Stone\Pay\Provider\Alipay\Api\TradePrecreate(
            'http://www.abc.com','123456789442', 30.22, '支付测试'
        );

        $server = new \Stone\Pay\Provider\Alipay\Server(
            'appid',
            'parivatekey',
            'publickey',
            $tradePrecreate
        );

        $server->enableSandboxPattern(
            '2016102000728056',
            'parivatekey',
            'publickey'
        );

        var_dump($server->request());
    }
);

$app->get(
    '/weixin',
    function () {
        try {
            $api = new \Stone\Pay\Provider\Weixin\Api\UnifiedOrder(
                new \Stone\Pay\Provider\Weixin\Api\TradeType\App(),
                'http://www.abc.com',
                '1231456',
                '阿斯多夫',
                301
            );

            $server = new \Stone\Pay\Provider\Weixin\Server(
                'appid',
                'appsecret',
                'mchid',
                $api
            );

            $response = $server->enableSandboxPattern()->request();
            var_dump($response);exit;
        } catch (\Stone\Pay\Exception\StonePayException $e) {
            echo $e->getMessage();
        }
    }
);

$app->run();
