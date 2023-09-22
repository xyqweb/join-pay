<?php

namespace xyqWeb\JoinPay\Service\FastNotify;

use xyqWeb\JoinPay\Exceptions\JoinPayException;
use xyqWeb\JoinPay\Service\BaseClient;
use xyqWeb\JoinPay\Support\RsaSigner;

class Client extends BaseClient
{
    /**
     * @param array $params
     * @return array
     * @throws JoinPayException
     * @throws \xyqWeb\JoinPay\Exceptions\InvalidArgumentException
     * @throws \xyqWeb\JoinPay\Exceptions\RuntimeException
     */
    public function parseNotify(array $params): array
    {
        if (!RsaSigner::verify($params, $params['sign'] ?? '', $this->config->get('public_key'))) {
            throw new JoinPayException('[回调异常]异常代码：签名校验失败');
        }
        return $params;
    }
}