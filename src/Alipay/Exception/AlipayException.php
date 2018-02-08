<?php

namespace Stone\Pay\Alipay\Exception;

use Psr\Log\LoggerInterface;

/**
 * Class AlipayException
 * @package Stone\Pay\Alipay\Exception
 * @author  令狐冲 <nicolaslei@163.com>
 */
class AlipayException extends \Exception
{
    /**
     * AlipayException constructor.
     * @param string               $message
     * @param LoggerInterface|null $logger
     */
    public function __construct($message, LoggerInterface $logger = null)
    {
        parent::__construct($message);

        if ($logger) {
            $logger->error($message);
        }
    }
}