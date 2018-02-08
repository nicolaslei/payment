<?php

namespace Stone\Pay\Weixin;

use GuzzleHttp\Client;

class Helper
{
    /**
     * @param $url
     * @param $data
     * @return array|bool
     */
    public static function httpRequest($url, $data)
    {
        $httpClient = new Client();

        $httpResponse = $httpClient->request('POST', $url, ['body' => $data, 'timeout' => 10]);
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
}