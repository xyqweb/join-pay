<?php

namespace xyqWeb\JoinPay;

use xyqWeb\JoinPay\Service\FastApplication;

class FastFactory
{
    public static function app(array $config = [])
    {
        return new FastApplication($config);
    }
}