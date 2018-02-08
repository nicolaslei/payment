<?php

namespace Stone\Pay\Exception;

use Psr\Log\LoggerInterface;

/**
 * Class StonePayException
 * @package Stone\Pay\Exception
 * @author  令狐冲 <nicolaslei@163.com>
 */
class StonePayException extends \Exception
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