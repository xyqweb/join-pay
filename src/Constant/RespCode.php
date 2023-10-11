<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/13/23
 * Time: 2:06 PM
 */

namespace xyqWeb\JoinPay\Constant;


class RespCode
{
    public const SUCCESS = 100;
    // 快捷支付响应code
    public const FAST_ORDER_SUCCESS = 'P1000'; // 交易成功
    public const FAST_ORDER_FAIL = 'P2000'; // 交易失败
    public const FAST_ORDER_HANDLE = 'P3000'; // 交易处理中
    public const FAST_ORDER_CANCEL = 'P4000'; // 订单取消
    public const FAST_ORDER_BLOCK = 'P5000'; // 风控阻断
    public const FAST_ORDER_CLOSED = 'P6000'; // 订单已关闭
    public const FAST_BIZ_CODE_SUCCESS = 'JS000000';
    public const FAST_AGREE_STATUS_SUCCESS = '02'; // 签约记录成功状态
    public const FAST_AGREE_OPT_SIGNED = '01'; // 签约操作类型-签约
    public const FAST_AGREE_OPT__CANCEL = '02'; // 签约操作类型-解约
}