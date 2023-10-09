<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/15/23
 * Time: 9:26 AM
 */

namespace xyqWeb\JoinPay\Support;


class Signer
{
    /**
     * build sign
     *
     * @param array $signArray
     * @param string $key
     * @return string
     */
    public static function sign(array $signArray, string $key): string
    {
        if(isset($signArray['hmac'])){
            unset($signArray['hmac']);
        }
        ksort($signArray);
        return md5(implode('', $signArray) . $key);
    }
}