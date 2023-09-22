<?php

namespace xyqWeb\JoinPay\Service\FastRefund;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        $app['refund'] = function ($app) {
            return new Client($app);
        };
    }
}