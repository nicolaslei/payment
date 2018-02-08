<?php

namespace Stone\Pay\Provider\Weixin;

use Psr\Log\LoggerAwareInterface;
use Stone\Pay\Exception\InvalidArgumentException;
use Stone\Pay\LoggerTrait;
use Stone\Pay\Provider\Weixin\Api\ApiInterface;
use Stone\Pay\Provider\Weixin\Api\UnifiedOrder;
use Stone\Pay\Provider\Weixin\SignType\Md5;
use Stone\Pay\Provider\Weixin\SignType\Sha256;
use Stone\Pay\Provider\Weixin\SignType\SignTypeInterface;

/**
 * Class ApiServer
 * @package Stone\Pay\Provider\Weixin
 */
class Server implements LoggerAwareInterface
{
    use LoggerTrait;

    /**
     * @var ApiInterface
     */
    private $api;

    /**
     * @var bool
     */
    private $sandboxPattern;

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $mchId;

    /**
     * @var string
     */
    private $appSecret;

    /**
     * @var Md5|SignTypeInterface
     */
    private $signType;

    /**
     * Api constructor.
     * @param $appId
     * @param $appSecret
     * @param $mchId
     * @param ApiInterface|null $api
     * @param SignTypeInterface|null $signType
     * @param bool $sandboxPattern
     */
    public function __construct(
        $appId,
        $appSecret,
        $mchId,
        ApiInterface $api = null,
        SignTypeInterface $signType = null,
        $sandboxPattern = false
    )
    {
        if ($api instanceof ApiInterface) {
            $this->setApi($api);
        }

        $this->appId          = $appId;
        $this->mchId          = $mchId;
        $this->appSecret      = $appSecret;
        $this->signType       = $signType;
        $this->sandboxPattern = $sandboxPattern;
    }

    public function enableSandboxPattern()
    {
        $this->sandboxPattern = true;

        return $this;
    }

    public function signByMd5()
    {
        $this->setSignType(new Md5());

        return $this;
    }

    public function signBySha256()
    {
        $this->setSignType(new Sha256());

        return $this;
    }

    public function setSignType(SignTypeInterface $signType)
    {
        $this->signType = $signType;

        return $this;
    }

    /**
     * @param ApiInterface $api
     * @return ApiInterface
     */
    public function setApi(ApiInterface $api)
    {
        return $this->api = $api;
    }

    public function pay($notifyUrl, $outTradeNo, $body, $totalFee)
    {

    }

    /**
     * @param ApiInterface|null $api
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function request(ApiInterface $api = null)
    {
        $signType = $this->signType;
        $api      = $api ?: $this->api;

        if (!$api instanceof ApiInterface) {
            throw new InvalidArgumentException('请设置要调用的API');
        }

        // 默认支付md5签名
        if (!$signType instanceof SignTypeInterface) {
            $signType = new Md5();
        }

        $api->setAppId($this->appId);
        $api->setMchId($this->mchId);
        $api->setAppSecret($this->appSecret);
        $api->setSignType($signType);

        if ($this->logger) {
            $api->setLogger($this->logger);
        }

        if ($this->sandboxPattern) {
            $api->enableSandboxPattern();
        }

        return $api->request();
    }
}