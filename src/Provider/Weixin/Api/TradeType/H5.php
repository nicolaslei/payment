<?php

namespace Stone\Pay\Provider\Weixin\Api\TradeType;

/**
 * Class H5
 * @package Stone\Pay\Provider\Weixin\Api\TradeType
 * @author  令狐冲 <nicolaslei@163.com>
 */
class H5 extends AbstractTradeType
{
    /**
     * H5 constructor.
     * @param        $wapName
     * @param        $wapUrl
     * @param string $sceneType
     */
    public function __construct($wapName, $wapUrl, $sceneType = 'Wap')
    {
        $this->parameters = [
            'trade_type'  => 'MWEB',
            'scene_info' => json_encode(
                [
                    'h5_info' => [
                        'type'     => $sceneType,
                        'wap_url'  => $wapUrl,
                        'wap_name' => $wapName
                    ]
                ]
            )
        ];
    }
}