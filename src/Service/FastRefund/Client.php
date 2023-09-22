<?php

namespace xyqWeb\JoinPay\Service\FastRefund;

use Illuminate\Support\Collection;
use xyqWeb\JoinPay\Service\BaseClient;

class Client extends BaseClient
{
    /**
     * create refund request
     *
     * @param array $params
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \xyqWeb\JoinPay\Exceptions\HttpException
     * @throws \xyqWeb\JoinPay\Exceptions\InvalidArgumentException
     * @throws \xyqWeb\JoinPay\Exceptions\RuntimeException
     */
    public function create(array $params): Collection
    {
        $url = $this->getFastApi('/refund');
        $baseParams = $this->baseFastParams();
        $baseParams['method'] = 'fastPay.refund';
        $params = array_merge($params, $baseParams);
        return $this->fastRequest($url, $params, 'POST');
    }

    /**
     * query refund info
     *
     * @param array $params
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \xyqWeb\JoinPay\Exceptions\HttpException
     * @throws \xyqWeb\JoinPay\Exceptions\InvalidArgumentException
     * @throws \xyqWeb\JoinPay\Exceptions\RuntimeException
     */
    public function refundQuery(array $params): Collection
    {
        $url = $this->getFastApi('/refund');
        $baseParams = $this->baseFastParams();
        $baseParams['method'] = 'refund.query';
        $params = array_merge($params, $baseParams);
        return $this->fastRequest($url, $params, 'POST');
    }
}