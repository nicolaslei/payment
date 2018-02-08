<?php

namespace Stone\Pay\Provider\Weixin\Api;

use Stone\Pay\Exception\ApiResponseException;
use Stone\Pay\Exception\SignatureValidationException;
use Stone\Pay\LoggerTrait;
use Stone\Pay\Provider\Weixin\Helper;
use Stone\Pay\Provider\Weixin\SignType\Factory as SignFactory;
use Stone\Pay\Provider\Weixin\SignType\SignTypeInterface;

/**
 * Class AbstractApi
 * @package Stone\Pay\Provider\Weixin\Api
 * @author  令狐冲 <nicolaslei@163.com>
 */
abstract class AbstractApi implements ApiInterface
{
    use LoggerTrait;

    protected $apiUrl = 'https://api.mch.weixin.qq.com';

    protected $sandboxTest = false;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var SignTypeInterface
     */
    protected $signType;

    /**
     * @var string
     */
    protected $appSecret;

    /**
     * @return string
     */
    abstract protected function getApiUri();

    /**
     * @return array|bool|mixed
     * @throws ApiResponseException
     * @throws SignatureValidationException
     * @throws \Stone\Pay\Exception\InvalidArgumentException
     */
    public function request()
    {
        $this->setParam('nonce_str', Helper::generateNonceStr());

        if (empty($this->parameters['spbill_create_ip'])) {
            $this->setParam('spbill_create_ip', $this->getIp());
        }

        $this->filterParameters();

        $response = Helper::httpRequest(
            $this->getApiUrl(),
            $this->sign()->toXml()
        );

        if ($response['return_code'] == 'SUCCESS') {
            // 签名方式
            $signType = empty($response['sign_type']) ? 'MD5' : $response['sign_type'];

            // 返回数据
            $sign = SignFactory::load($signType)
                ->generateSignature($response, $this->appSecret);

            if ($response['sign'] == $sign) {
                if ($response['result_code'] == 'SUCCESS') {
                    return $response;
                }

                throw new ApiResponseException(
                    sprintf('%s:%s', $response['err_code'], $response['err_code_des']),
                    $this->logger
                );
            }

            throw new SignatureValidationException('微信同步响应签名校验失败', $this->logger);
        }

        throw new ApiResponseException($response['return_msg'], $this->logger);
    }

    /**
     * @return string
     */
    public function toXml()
    {
        return Helper::arrayToXml($this->parameters);
    }

    public function setAppId($appId)
    {
        $this->setParam('appid', $appId);
    }

    public function setMchId($mchId)
    {
        $this->setParam('mch_id', $mchId);
    }

    public function setAppSecret($appSecret)
    {
        $this->appSecret = $appSecret;
    }

    public function setSignType(SignTypeInterface $signType)
    {
        $this->signType = $signType;
        $this->setParam('sign_type', $signType->getSignType());
    }

    /**
     * @return $this|mixed
     * @throws ApiResponseException
     * @throws \Stone\Pay\Exception\InvalidArgumentException
     */
    public function enableSandboxPattern()
    {
        $signKeyParameters = [
            'mch_id'    => $this->parameters['mch_id'],
            'nonce_str' => Helper::generateNonceStr()
        ];

        $signKeyParameters['sign'] = SignFactory::load()
            ->generateSignature($signKeyParameters, $this->appSecret);

        $response = Helper::httpRequest(
            $this->apiUrl . '/sandboxnew/pay/getsignkey',
            Helper::arrayToXml($signKeyParameters)
        );

        if ($response['return_code'] == 'FAIL') {
            throw new ApiResponseException($response['return_msg'], $this->logger);
        }

        $this->appSecret   = $response['sandbox_signkey'];
        $this->sandboxTest = true;

        return $this;
    }

    protected function setParam($key, $val)
    {
        $this->parameters[$key] = trim($val);
    }

    protected function getApiUrl()
    {
        return $this->apiUrl . ($this->sandboxTest ? '/sandboxnew' : '') . $this->getApiUri();
    }

    /**
     * 过滤参数
     *
     * @return $this
     */
    protected function filterParameters()
    {
        $this->parameters = array_filter(
            $this->parameters,
            function ($param) {
                return !empty($param);
            }
        );

        return $this;
    }

    protected function getIp()
    {
        // 判断服务器是否允许$_SERVER
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            // 不允许就使用getenv获取
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }

        return $realip;
    }

    /**
     * @return $this
     */
    protected function sign()
    {
        $sign = $this->signType
            ->generateSignature($this->parameters, $this->appSecret);

        $this->setParam('sign', $sign);

        return $this;
    }
}