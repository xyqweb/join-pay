<?php

namespace xyqWeb\JoinPay\Service\FastNotify;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        $app['notify'] = function ($app) {
            return new Client($app);
        };
    }
}