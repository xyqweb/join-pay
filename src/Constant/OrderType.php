<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/13/23
 * Time: 2:00 PM
 */

namespace xyqWeb\JoinPay\Constant;

class OrderType
{
    /**
     * 订单类型：
     * ALIPAY
     * WECHAT
     * QUICK
     */
    public const ALIPAY = 'ALIPAY';
    public const WECHAT = 'WECHAT';
    public const FAST_PAY = 'FAST_PAY';

}