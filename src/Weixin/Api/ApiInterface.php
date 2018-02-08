<?php

namespace Stone\Pay\Weixin\Api;

use Stone\Pay\Weixin\ApiParameters;
use Stone\Pay\Weixin\SignType\SignTypeInterface;

/**
 * Interface ApiInterface
 * @package Stone\Pay\Weixin\Api
 */
interface ApiInterface
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