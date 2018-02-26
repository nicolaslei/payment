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

    /**
     * @var string
     */
    protected $apiUrl = 'https://api.mch.weixin.qq.com';

    /**
     * @var bool
     */
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
     * @var string
     */
    protected $apiClientCertPath;

    /**
     * @var string
     */
    protected $apiClientKeyPath;

    /**
     * @return string
     */
    abstract protected function getApiUri();

    /**
     * @return array|bool|mixed
     * @throws ApiResponseException
     * @throws SignatureValidationException
     * @throws \Stone\Pay\Exception\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\ConnectException
     */
    public function request()
    {
        $parameters = $this->parameters;

        $parameters['nonce_str'] = Helper::generateNonceStr();

        if (empty($parameters['spbill_create_ip'])) {
            $parameters['spbill_create_ip'] = $this->getIp();
        }

        $parameters = array_filter(
            $parameters,
            function ($param) {
                return !empty($param);
            }
        );

        $parameters['sign'] = $this->signType
            ->generateSignature($parameters, $this->appSecret);

        // 日志
        $this->recordDebugLog(
            sprintf(
                '请求微信支付接口，地址[%s]，参数[%s]',
                $this->getApiUrl(),
                var_export($parameters, true)
            )
        );

        $parameters = Helper::arrayToXml($parameters);

        $response = Helper::httpRequest(
            $this->getApiUrl(),
            $parameters,
            $this->apiClientCertPath,
            $this->apiClientKeyPath
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
                    sprintf('微信支付同步响应错误：%s(%s)', $response['err_code_des'], $response['err_code']),
                    $this->logger
                );
            }

            throw new SignatureValidationException('微信支付同步响应签名校验失败', $this->logger);
        }

        throw new ApiResponseException('微信支付同步响应错误：' . $response['return_msg'], $this->logger);
    }

    /**
     * @param string $appId
     * @return void
     */
    public function setAppId($appId)
    {
        $this->setParam('appid', $appId);
    }

    /**
     * @param $mchId
     * @return void
     */
    public function setMchId($mchId)
    {
        $this->setParam('mch_id', $mchId);
    }

    /**
     * @param string $appSecret
     * @return void
     */
    public function setAppSecret($appSecret)
    {
        $this->appSecret = $appSecret;
    }

    /**
     * @param SignTypeInterface $signType
     * @return void
     */
    public function setSignType(SignTypeInterface $signType)
    {
        $this->signType = $signType;
        $this->setParam('sign_type', $signType->getSignType());
    }

    /**
     * 启用沙箱模式
     *
     * @return $this|mixed
     * @throws ApiResponseException
     * @throws \Stone\Pay\Exception\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\ConnectException
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

        $this->appSecret   = $response['sandbox_signkey']; // 使用沙箱密钥
        $this->sandboxTest = true;

        return $this;
    }

    /**
     * @param $key
     * @param $val
     */
    protected function setParam($key, $val)
    {
        $this->parameters[$key] = trim($val);
    }

    /**
     * @return string
     */
    protected function getApiUrl()
    {
        return $this->apiUrl . ($this->sandboxTest ? '/sandboxnew' : '') . $this->getApiUri();
    }

    /**
     * @return array|false|string
     */
    protected function getIp()
    {
        // 判断服务器是否允许$_SERVER
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $realIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realIp = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $realIp = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            // 不允许就使用getenv获取
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realIp = getenv("HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $realIp = getenv("HTTP_CLIENT_IP");
            } else {
                $realIp = getenv("REMOTE_ADDR");
            }
        }

        return $realIp;
    }
}