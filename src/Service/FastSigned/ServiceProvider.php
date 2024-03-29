<?php

namespace xyqWeb\JoinPay\Service\FastSigned;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $app)
    {
        $app['signed'] = function ($app) {
            return new Client($app);
        };
    }
}