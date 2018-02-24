<?php

namespace Stone\Pay\Provider\Weixin;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Helper
{
    /**
     * @param $url
     * @param $data
     * @param null $apiClientCertPath
     * @param null $apiClientKeyPath
     * @return array|bool
     * @throws \GuzzleHttp\Exception\ConnectException
     */
    public static function httpRequest($url, $data, $apiClientCertPath = null, $apiClientKeyPath = null)
    {
        $httpClient = new Client();

        $options = [RequestOptions::BODY => $data, RequestOptions::TIMEOUT => 10];

        if ($apiClientCertPath && $apiClientKeyPath) {
            $options[RequestOptions::CERT]    = $apiClientCertPath;
            $options[RequestOptions::SSL_KEY] = $apiClientKeyPath;
        }

        $httpResponse = $httpClient->request('POST', $url, $options);
        if ($httpResponse->getStatusCode() == 200) {
            return self::xmlToArray($httpResponse->getBody()->getContents());
        }

        return false;
    }

    /**
     * @param array $array
     * @return string
     */
    public static function arrayToXml(array $array)
    {
        $dom = new \DomDocument('1.0', 'utf-8');
        //  创建根节点
        $xml = $dom->createElement('xml');
        $dom->appendchild($xml);
        foreach ($array as $item => $val) {
            $$item = $dom->createElement($item);
            $xml->appendChild($$item);
            //  创建元素值
            if (is_numeric($val)) {
                $text = $dom->createTextNode($val);
            } else {
                $text = $dom->createCDATASection($val);
            }

            $$item->appendchild($text);
        }

        return $dom->saveXML();
    }

    /**
     * @param $xml
     * @return array
     */
    public static function xmlToArray($xml)
    {
        libxml_disable_entity_loader(true); // 禁止引用外部xml实体
        $xml = simplexml_load_string(
            $xml,
            'SimpleXMLElement',
            LIBXML_NOCDATA
        );

        $response = json_decode(
            json_encode($xml),
            true
        );

        return $response;
    }

    /**
     * 生成随机字符串
     *
     * @param int $length
     * @return string
     */
    public static function generateNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str   = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        return $str;
    }
}