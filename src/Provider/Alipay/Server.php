<?php

namespace Stone\Pay\Provider\Alipay;

use Psr\Log\LoggerAwareInterface;
use Stone\Pay\Exception\InvalidArgumentException;
use Stone\Pay\Provider\Alipay\Api\ApiInterface;
use Stone\Pay\Provider\Alipay\SignType\Rsa2;
use Stone\Pay\Provider\Alipay\SignType\SignTypeInterface;
use Stone\Pay\LoggerTrait;

/**
 * Class Server
 * @package Stone\Pay\Provider\Alipay
 * @author  令狐冲 <nicolaslei@163.com>
 */
class Server implements LoggerAwareInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    private $appId;

    /**
     * @var null|ApiInterface
     */
    private $api;

    /**
     * @var null|SignTypeInterface
     */
    private $sign;

    /**
     * @var string
     */
    private $privateKey;

    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var bool
     */
    private $sandboxPattern;

    /**
     * Server constructor.
     * @param $appId
     * @param $privateKey
     * @param $publicKey
     * @param ApiInterface|null $api
     * @param SignTypeInterface|null $sign
     */
    public function __construct($appId, $privateKey, $publicKey, ApiInterface $api = null, SignTypeInterface $sign = null)
    {
        $this->appId      = $appId;
        $this->privateKey = $privateKey;
        $this->publicKey  = $publicKey;
        $this->api        = $api;
        $this->sign       = $sign;
    }

    /**
     * @param $appId
     * @param $privateKey
     * @param $publicKey
     *
     * @return $this
     */
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
     * @throws InvalidArgumentException
     */
    public function request(ApiInterface $api = null)
    {
        $sign = $this->sign;
        $api  = $api ?: $this->api;

        if (!$api instanceof ApiInterface) {
            throw new InvalidArgumentException('请设置支付接口');
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
}