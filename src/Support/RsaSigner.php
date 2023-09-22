<?php

namespace xyqWeb\JoinPay\Support;

use xyqWeb\JoinPay\Exceptions\InvalidArgumentException;
use xyqWeb\JoinPay\Exceptions\RuntimeException;

class RsaSigner
{
    const CHARS = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    /**
     * 使用公钥加密
     *
     * @param string $data
     * @param string $pubKey
     * @return string
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public static function encrypt(string $data, string $pubKey): string
    {
//        $pubKey = wordwrap($pubKey, 64, PHP_EOL, true);
        $pubKey = '-----BEGIN PUBLIC KEY-----' . PHP_EOL . $pubKey . PHP_EOL . "-----END PUBLIC KEY-----";
        $pubKey = openssl_get_publickey($pubKey);
        if ($pubKey === false) {
            throw new InvalidArgumentException("rsa解密公钥无效");
        }

        $crypto = '';
        $isSuccess = openssl_public_encrypt($data, $crypto, $pubKey);
        openssl_free_key($pubKey);
        if (!$isSuccess) {
            throw new RuntimeException("rsa加密失败");
        }
        return base64_encode($crypto);
    }

    /**
     * 使用私钥解密
     *
     * @param string $data
     * @param string $priKey
     * @return string
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public static function decrypt(string $data, string $priKey): string
    {
//        $priKey = wordwrap($priKey, 64, PHP_EOL, true);
        $priKey = '-----BEGIN RSA PRIVATE KEY-----' . PHP_EOL . $priKey . PHP_EOL . '-----END RSA PRIVATE KEY-----';
        $priKey = openssl_get_privatekey($priKey);
        if ($priKey === false) {
            throw new InvalidArgumentException("rsa解密私钥无效");
        }

        $decrypted = '';
        $isSuccess = openssl_private_decrypt(base64_decode($data), $decrypted, $priKey);
        openssl_free_key($priKey);
        if (!$isSuccess) {
            throw new RuntimeException("rsa解密失败");
        }
        return $decrypted;
    }

    /**
     * 使用私钥进行签名
     *
     * @param array $data
     * @param string $priKey
     * @return string
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public static function sign(array $data, string $priKey): string
    {
        $signStr = self::getSignStr($data);
//        $priKey = wordwrap($priKey, 64, PHP_EOL, true);
        $priKey = '-----BEGIN RSA PRIVATE KEY-----' . PHP_EOL . $priKey . PHP_EOL . '-----END RSA PRIVATE KEY-----';
        $priKey = openssl_get_privatekey($priKey);
        if ($priKey === false) {
            throw new InvalidArgumentException("rsa签名私钥无效");
        }
        $binary_signature = '';
        $isSuccess = openssl_sign($signStr, $binary_signature, $priKey, OPENSSL_ALGO_MD5);
        openssl_free_key($priKey);
        if (!$isSuccess) {
            throw new RuntimeException("rsa签名失败");
        }
        return base64_encode($binary_signature);
    }

    /**
     * 使用公钥进行验签
     *
     * @param array $data
     * @param string $signParam
     * @param string $pubKey
     * @return bool
     * @throws InvalidArgumentException
     */
    public static function verify(array $data, string $signParam, string $pubKey): bool
    {
        $signStr = self::getSignStr($data);
//        $pubKey = wordwrap($pubKey, 64, PHP_EOL, true);
        $pubKey = '-----BEGIN PUBLIC KEY-----' . PHP_EOL . $pubKey . PHP_EOL . "-----END PUBLIC KEY-----";
        $pubKey = openssl_get_publickey($pubKey);
        if ($pubKey === false) {
            throw new InvalidArgumentException("rsa验签公钥无效");
        }
        $signParam = base64_decode($signParam);
        $isMatch = openssl_verify($signStr, $signParam, $pubKey, OPENSSL_ALGO_MD5);
        openssl_free_key($pubKey);
        return $isMatch;
    }

    /**
     * 获取待签名字符串
     *
     * @param array $data
     * @return string
     */
    public static function getSignStr(array $data): string
    {
        ksort($data);
        //拼接字符串
        $str = [];
        foreach ($data as $key => $value) {
            //不参与签名、验签
            if ($key == "sign" || $key == "sec_key" || $key == "private_key" || $key == "public_key" || $key == "platform_public_key") {
                continue;
            }
            if ($value === null) {
                $value = '';
            }
            $str[] = $key . '=' . $value;
        }
        return implode('&', $str);
    }

    /**
     * 生成指定长度的随机字符串
     *
     * @param int $length
     * @return string
     */
    public static function randomStr(int $length = 16): string
    {
        $str = "";
        $strLen = strlen(static::CHARS) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= static::CHARS[mt_rand(0, $strLen)];
        }
        return $str;
    }
}