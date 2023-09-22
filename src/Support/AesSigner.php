<?php

namespace xyqWeb\JoinPay\Support;

use xyqWeb\JoinPay\Exceptions\RuntimeException;

class AesSigner
{
    const EBC_MODE = "AES-128-ECB";

    /**
     * AES加密，模式为：AES/ECB/PKCK7Padding
     * @param string $data
     * @param string $secKey
     * @return string
     * @throws RuntimeException
     */
    public static function encryptECB(string $data, string $secKey): string
    {
        $encrypted = openssl_encrypt($data, self::EBC_MODE, $secKey, OPENSSL_RAW_DATA);
        if ($encrypted === false) {
            throw new RuntimeException("aes加密失败");
        }
        return base64_encode($encrypted);
    }

    /**
     * AES解密，模式为：AES/ECB/PKCK7Padding
     * @param string $data
     * @param string $secKey
     * @return string
     * @throws RuntimeException
     */
    public static function decryptECB(string $data, string $secKey): string
    {
        $decrypted = openssl_decrypt(base64_decode($data), self::EBC_MODE, $secKey, OPENSSL_RAW_DATA);
        if ($decrypted === false) {
            throw new RuntimeException("aes解密失败");
        }
        return $decrypted;
    }
}