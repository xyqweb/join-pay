<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/13/23
 * Time: 2:01 PM
 */

namespace xyqWeb\JoinPay\Constant;


class PayType
{
    /**
     * 支付宝h5
     */
    public const ALI_PAY_H5 = 'ALIPAY_H5';
    /**
     * 微信公众号
     */
    public const WE_JSAPI_PAY = 'WEIXIN_GZH';
    /**
     * 微信小程序
     */
    public const WE_APPLET_PAY = 'WEIXIN_XCX';
    /**
     * 快捷支付
     */
    public const FAST_PAY = 'FAST_PAY';
}