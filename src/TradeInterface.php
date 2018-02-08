<?php

namespace Stone\Pay;

interface TradeInterface
{
    public function getOutTradeNo();

    public function getTradeTotalAmount();
}