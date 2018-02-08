<?php

namespace Stone\Pay\Weixin;

use Stone\Pay\Weixin\Api\ApiInterface;
use Stone\Pay\Weixin\Api\UnifiedOrder;
use Stone\Pay\Weixin\Exception\WeiXinException;
use Stone\Pay\Weixin\SignType\Md5;
use Stone\Pay\Weixin\SignType\Sha256;
use Stone\Pay\Weixin\SignType\SignTypeInterface;

/**
 * Class ApiServer
 * @package Stone\Pay\Weixin
 */
class Server
{
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
     * @return array
     * @throws WeiXinException
     */
    public function request(ApiInterface $api = null)
    {
        $signType = $this->signType;
        $api      = $api ?: $this->api;

        if (!$api instanceof ApiInterface) {
            throw new WeiXinException('请设置要调用的API');
        }

        // 默认支付md5签名
        if (!$signType instanceof SignTypeInterface) {
            $signType = new Md5();
        }

        $api->setAppId($this->appId);
        $api->setMchId($this->mchId);
        $api->setAppSecret($this->appSecret);
        $api->setSignType($signType);

        if ($this->sandboxPattern) {
            $api->enableSandboxPattern();
        }

        return $api->request();
    }
}