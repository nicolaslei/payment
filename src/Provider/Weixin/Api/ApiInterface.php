<?php

namespace Stone\Pay\Provider\Weixin\Api;

use Psr\Log\LoggerAwareInterface;
use Stone\Pay\Provider\Weixin\SignType\SignTypeInterface;

/**
 * Interface ApiInterface
 * @package Stone\Pay\Provider\Weixin\Api
 */
interface ApiInterface extends LoggerAwareInterface
{
    /**
     * @return mixed
     */
    public function request();

    /**
     * @return mixed
     */
    public function enableSandboxPattern();

    /**
     * @param $appId
     * @return mixed
     */
    public function setAppId($appId);

    /**
     * @param $mchId
     * @return mixed
     */
    public function setMchId($mchId);

    /**
     * @param $appSecret
     * @return mixed
     */
    public function setAppSecret($appSecret);

    /**
     * @param SignTypeInterface $signType
     * @return mixed
     */
    public function setSignType(SignTypeInterface $signType);
}