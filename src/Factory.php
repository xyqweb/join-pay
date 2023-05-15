<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/15/23
 * Time: 9:44 AM
 */

namespace xyqWeb\JoinPay;



use xyqWeb\JoinPay\Service\Application;

class Factory
{
    public static function app(array $config = [])
    {
        return new Application($config);
    }
}