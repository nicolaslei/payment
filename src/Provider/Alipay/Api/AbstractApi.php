<?php

namespace Stone\Pay\Provider\Alipay\Api;

use GuzzleHttp;
use Stone\Pay\Exception;
use Stone\Pay\Provider\Alipay\Api\Traits\BizContentTrait;
use Stone\Pay\Provider\Alipay\SignType\SignTypeInterface;
use Stone\Pay\LoggerTrait;

/**
 * Class AbstractApi
 * @package Stone\Pay\Provider\Alipay\Api
 * @author  令狐冲 <nicolaslei@163.com>
 */
abstract class AbstractApi implements ApiInterface
{
    use BizContentTrait, LoggerTrait;

    /**
     * @var SignTypeInterface
     */
    protected $sign;

    /**
     * @var string
     */
    protected $privateKey;

    /**
     * @var string
     */
    protected $publicKey;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $gateway = 'https://openapi.alipay.com/gateway.do';

    /**
     * @var bool
     */
    protected $sandboxTest = false;

    /**
     * @return string
     */
    abstract protected function getMethod();

    /**
     * @return array
     * @throws Exception\ApiResponseException
     * @throws Exception\InvalidArgumentException
     * @throws Exception\SignatureValidationException
     */
    public function request()
    {
        $method     = $this->getMethod();
        $parameters = $this->getApiParameters();

        // 日志
        $this->recordDebugLog(
            sprintf(
                '请求支付宝接口，地址[%s]，参数[%s]',
                $this->gateway,
                var_export($parameters, true)
            )
        );

        $httpClient = new GuzzleHttp\Client();
        // 请求接口
        $httpResponse = $httpClient->request(
            'POST',
            $this->gateway,
            ['form_params' => $parameters]
        );

        if ($httpResponse->getStatusCode() != '200') {
            throw new Exception\ApiResponseException('支付宝接口同步响应网络发生错误', $this->logger);
        }

        // POST会有中文乱码，需要转换编码
        $response = mb_convert_encoding(
            $httpResponse->getBody()->getContents(),
            'utf-8',
            'gb2312'
        );

        $this->recordDebugLog('支付宝接口同步响应数据：' . $response);
        unset($httpResponse);

        try {
            // 将JSON解析为数组
            $response = GuzzleHttp\json_decode($response, true);
        } catch (\InvalidArgumentException $e) {
            throw new Exception\InvalidArgumentException('支付宝接口同步响应返回数据JSON解析失败', $this->logger);
        }

        $responseKey = str_ireplace('.', '_', $method) . '_response';
        if (!isset($response[$responseKey])) {
            throw new Exception\InvalidArgumentException('支付宝接口同步响应数据不合法', $this->logger);
        }

        $responseData = $response[$responseKey];
        if ($responseData['code'] == '10000') {
            // JSON_UNESCAPED_UNICODE 解决中文被编码导致签名校验失败的问题
            $signString = GuzzleHttp\json_encode($responseData, JSON_UNESCAPED_UNICODE);
            // 校验结果（使用请求的签名方式）
            $result = $this->sign->verifySign($signString, $this->publicKey, $response['sign']);
            if ($result) {
                return $responseData;
            }

            throw new Exception\SignatureValidationException('支付宝接口同步响应签名校验失败', $this->logger);
        }

        throw new Exception\ApiResponseException(
            sprintf(
                '支付宝接口同步响应失败：CODE:%s，MSG:%s,SUB_CODE:%s，SUB_MSG:%s',
                $responseData['code'],
                $responseData['msg'],
                $responseData['sub_code'],
                $responseData['sub_msg']
            ),
            $this->logger
        );
    }

    /**
     * @param SignTypeInterface $sign
     */
    public function setSign(SignTypeInterface $sign)
    {
        $this->setParam('sign_type', $sign->getSignType());
        $this->sign = $sign;
    }

    /**
     * @return $this
     */
    public function enableSandboxPattern()
    {
        $this->gateway = 'https://openapi.alipaydev.com/gateway.do';

        return $this;
    }

    /**
     * @param $appId
     */
    public function setAppId($appId)
    {
        $this->setParam('app_id', $appId);
    }

    /**
     * @param $privateKey
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * @param $publicKey
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @param $key
     * @param $val
     */
    protected function setParam($key, $val)
    {
        $this->parameters[$key] = strval(trim($val));
    }

    /**
     * @return string
     */
    protected function getBizContentParam()
    {
        return $this->getBizContent()->toJson();
    }

    /**
     * @return array
     */
    protected function getApiParameters()
    {
        $method     = $this->getMethod();
        $parameters = $this->parameters;

        $parameters['method']      = $method;
        $parameters['charset']     = 'utf-8';
        $parameters['timestamp']   = date('Y-m-d H:i:s');
        $parameters['version']     = '1.0';
        $parameters['biz_content'] = $this->getBizContentParam();

        ksort($parameters);
        reset($parameters);

        $parameters['sign'] = $this->sign
            ->generateSignature($parameters, $this->privateKey);

        return $parameters;
    }
}