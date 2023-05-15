<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/13/23
 * Time: 5:41 PM
 */

namespace xyqWeb\JoinPay\Service;


use xyqWeb\JoinPay\Support\ServiceContainer;

/**
 * Class Application
 * @package xyqWeb\JoinPay\Service
 * @property Transaction\Client $transaction
 * @property Refund\Client $refund
 */
class Application extends ServiceContainer
{
    protected $providers = [
        Transaction\ServiceProvider::class,
        Refund\ServiceProvider::class,
    ];
}