<?php

namespace Stone\Pay\Provider\Alipay\Api;

use Psr\Log\LoggerAwareInterface;
use Stone\Pay\Provider\Alipay\SignType\SignTypeInterface;

interface ApiInterface extends LoggerAwareInterface
{
    public function request();

    public function setSign(SignTypeInterface $sign);

    public function setAppId($appId);

    public function setPrivateKey($privateKey);

    public function setPublicKey($publicKey);

    public function enableSandboxPattern();
}