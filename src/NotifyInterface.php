<?php
namespace Stone\Pay;

interface NotifyInterface
{
    /**
     * @param TradeInterface|null $trade
     *
     * @return boolean|array
     */
    public function verify(TradeInterface $trade = null);

    /**
     * @return mixed
     */
    public function replySuccess();

    /**
     * @return mixed
     */
    public function replyFail();
}