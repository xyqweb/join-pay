<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/13/23
 * Time: 5:45 PM
 */

namespace xyqWeb\JoinPay\Support;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['config'] = function ($app) {
            /**
             * @var ServiceContainer $app
             */
            return new Config($app->getDefaultConfig());
        };
    }
}