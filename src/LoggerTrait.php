<?php

namespace Stone\Pay;

use Psr\Log\LoggerInterface;

/**
 * Trait LoggerTrait
 * @package Stone\Pay
 */
trait LoggerTrait
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param       $message
     * @param array $context
     */
    protected function recordDebugLog($message, array $context = [])
    {
        if ($this->logger) {
            $this->logger->debug($message, $context);
        }
    }

    /**
     * @param       $message
     * @param array $context
     */
    protected function recordErrorLog($message, array $context = [])
    {
        if ($this->logger) {
            $this->logger->error($message, $context);
        }
    }
}