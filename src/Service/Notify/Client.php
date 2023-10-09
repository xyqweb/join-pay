<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/15/23
 * Time: 3:40 PM
 */

namespace xyqWeb\JoinPay\Service\Notify;


use xyqWeb\JoinPay\Exceptions\JoinPayException;
use xyqWeb\JoinPay\Service\BaseClient;
use xyqWeb\JoinPay\Support\Signer;

class Client extends BaseClient
{
    /**
     * @param array $params
     * @return array
     * @throws JoinPayException
     * @see parseNotify
     */
    public function parseNotify(array $params): array
    {
        $sign = Signer::sign($params, $this->config->get('key'));
        if ($sign != strtolower($params['hmac'])) {
            throw new JoinPayException('[回调异常]异常代码：签名校验失败');
        }
        return $params;
    }
}