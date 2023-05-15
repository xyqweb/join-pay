<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/15/23
 * Time: 10:01 AM
 */

namespace xyqWeb\JoinPay\Service\Refund;


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