<?php

namespace Stone\Pay\Alipay;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Stone\Pay\Alipay\Api\ApiInterface;
use Stone\Pay\Alipay\Exception\AlipayException;
use Stone\Pay\Alipay\SignType\Rsa2;
use Stone\Pay\Alipay\SignType\SignTypeInterface;

/**
 * Class Server
 * @package Stone\Pay\Alipay
 * @author  令狐冲 <nicolaslei@163.com>
 */
class Server implements LoggerAwareInterface
{
    private $appId;

    private $api;

    private $sign;

    private $privateKey;

    private $publicKey;

    private $logger;

    /**
     * @var bool
     */
    private $sandboxPattern;

    public function __construct($appId, $privateKey, $publicKey, ApiInterface $api = null, SignTypeInterface $sign = null)
    {
        $this->appId      = $appId;
        $this->privateKey = $privateKey;
        $this->publicKey  = $publicKey;
        $this->api        = $api;
        $this->sign       = $sign;
    }

    public function enableSandboxPattern($appId, $privateKey, $publicKey)
    {
        $this->appId      = $appId;
        $this->privateKey = $privateKey;
        $this->publicKey  = $publicKey;

        $this->sandboxPattern = true;

        return $this;
    }

    /**
     * @param ApiInterface|null $api
     * @return mixed
     * @throws AlipayException
     */
    public function request(ApiInterface $api = null)
    {
        $sign = $this->sign;
        $api  = $api ?: $this->api;

        if (!$api instanceof ApiInterface) {
            throw new AlipayException('请设置支付接口');
        }

        if (!$sign instanceof SignTypeInterface) {
            $sign = new Rsa2();
        }

        $api->setSign($sign);
        $api->setAppId($this->appId);
        $api->setPrivateKey($this->privateKey);
        $api->setPublicKey($this->publicKey);

        if ($this->logger) {
            $api->setLogger($this->logger);
        }

        if ($this->sandboxPattern) {
            $api->enableSandboxPattern();
        }

        return $api->request();
    }

    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}