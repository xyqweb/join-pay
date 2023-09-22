<?php

namespace xyqWeb\JoinPay\Constant;

class JoinPayType
{
    public const API_VERSION = 2.3;
    public const FAST_API_VERSION = '1.0';
    public const TRANSACTION_API_HOST = 'https://www.joinpay.com';
    public const TRANSACTION_FAST_API_HOST = 'https://api.joinpay.com';

    // 证件类型
    public const ID_TYPE = [
        '1' => '身份证',
        '2' => '军官证',
        '3' => '士兵证',
        '4' => '护照',
        '5' => '港澳台居民往来通行证',
        '6' => '临时身份证',
        '7' => '户口本',
        '8' => '警官证',
        '9' => '外国人永久居留证',
        '10' => '其他',
        '11' => '外国护照',
        '12' => '营业执照',
    ];
    // 支持签约快捷支付的银行
    public const BANK_CODE = [
        'ICBC' => '工商银行',
        'BOC' => '中国银行',
        'ECITIC' => '中信银行',
        'SHB' => '上海银行',
        'CEB' => '光大银行',
        'CMBC' => '民生银行',
        'BCCB' => '北京银行',
        'PINGANBANK' => '平安银行',
        'BOCO' => '交通银行',
        'CMBCHINA' => '招商银行',
        'CGB' => '广发银行',
        'CCB' => '建设银行',
        'SPDB' => '上海浦发银行',
        'POST' => '中国邮政',
        'GRCB' => '广州农村商业银行',
    ];
    public const REQUIRE_ENCRYPTED_FIELDS = [
        'payer_name',
        'id_type',
        'id_no',
        'bank_card_no',
        'mobile_no',
        'expire_date',
        'cvv',
        'sign_no',
        'bank_card_no',
    ];
}