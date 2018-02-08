<?php

namespace Stone\Pay\Provider\Weixin\SignType;

/**
 * Interface SignTypeInterface
 * @package Stone\Pay\Provider\Weixin\SignType
 */
interface SignTypeInterface
{
    /**
     * 签名
     *
     * @param array $data
     * @param       $key
     * @return mixed
     */
    public function generateSignature(array $data, $key);

    /**
     * @return string
     */
    public function getSignType();
}