<?php

namespace xyqWeb\JoinPay\Service;

use xyqWeb\JoinPay\Support\FastServiceContainer;

/**
 * Class Application
 * @package xyqWeb\JoinPay\Service
 * @property FastTransaction\Client $transaction
 * @property FastRefund\Client $refund
 * @property FastNotify\Client $notify
 * @property FastSigned\Client $signed
 */
class FastApplication extends FastServiceContainer
{
    protected $providers = [
        FastTransaction\ServiceProvider::class,
        FastRefund\ServiceProvider::class,
        FastNotify\ServiceProvider::class,
        FastSigned\ServiceProvider::class,
    ];
}