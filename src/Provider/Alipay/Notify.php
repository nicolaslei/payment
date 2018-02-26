<?php

namespace Stone\Pay\Provider\Alipay;

use Stone\Pay\Exception;
use Stone\Pay\Provider\Alipay\SignType\Factory as SignFactory;
use Stone\Pay\LoggerTrait;
use Stone\Pay\NotifyInterface;
use Stone\Pay\TradeInterface;

/**
 * Class Notify
 * @package Stone\Pay\Provider\Alipay
 * @author  令狐冲 <nicolaslei@163.com>
 */
class Notify implements NotifyInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    private $publicKey;

    private $data;

    public function __construct()
    {
        $data = $_POST ?: $_GET;
        if (empty($data) || !is_array($data)) {
            throw new Exception\InvalidArgumentException('支付宝异步通知回调接收不到数据');
        }

        // 日志
        $this->recordDebugLog(
            sprintf(
                '微信支付异步回调通知数据[%s]',
                var_export($data, true)
            )
        );

        $this->data = $data;
    }

    /**
     * @param null|TradeInterface $trade
     * @return array|bool
     */
    public function verify(?TradeInterface $trade)
    {
        $data = $this->data;

        if ($trade instanceof TradeInterface && $this->verifyTrade($trade)) {
            return $data;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function verifySign()
    {
        $data = $this->data;

        $sign     = $data['sign'];
        $signType = $data['sign_type'];

        // 异步验签sign/sign_type不参与签名
        unset($data['sign'], $data['sign_type']);

        $waitSignString = Helper::implode($data);

        try {
            $result = SignFactory::load($signType)
                ->verifySign($waitSignString, $this->publicKey, $sign);

            if (!$result) {
                throw new Exception\SignatureValidationException(
                    sprintf(
                        "支付宝异步通知签名校验失败，签名类型[%s]，签名[%s]，签名参数[%s]",
                        $sign,
                        $signType,
                        var_export($data, true)
                    )
                );
            }

            return true;

        } catch (Exception\StonePayException $e) {
            // 日志
            $this->recordErrorLog($e->getMessage());

            return false;
        }
    }

    public function verifyStatus()
    {
        if (isset($this->data['trade_status'])) {
            $status = $this->data['trade_status'];
            if (in_array($status, ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param TradeInterface $trade
     * @return bool
     */
    protected function verifyTrade(TradeInterface $trade)
    {
        $data = $this->data;

        if ($trade instanceof TradeInterface
            && isset($data['out_trade_no'])
            && isset($data['total_amount'])
        ) {
            $outTradeNo  = $data['out_trade_no'];
            $totalAmount = $data['total_amount'];

            if ($outTradeNo == $trade->getOutTradeNo() && $totalAmount == $trade->getTradeTotalAmount()) {
                return true;
            }
        }

        return false;
    }


    /**
     * @return mixed
     */
    public function replySuccess()
    {
        echo 'success';
        exit;
    }

    /**
     * @return mixed
     */
    public function replyFail()
    {
        echo 'fail';
        exit;
    }
}