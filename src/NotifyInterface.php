<?php

namespace Stone\Pay;

/**
 * Interface NotifyInterface
 * @package Stone\Pay
 */
interface NotifyInterface
{
    /**
     * @param TradeInterface|null $trade
     *
     * @return boolean|array
     */
    public function verify(?TradeInterface $trade);

    /**
     * @return mixed
     */
    public function replySuccess();

    /**
     * @return mixed
     */
    public function replyFail();
}