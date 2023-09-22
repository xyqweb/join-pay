<?php

namespace xyqWeb\JoinPay\Service\FastTransaction;

use Illuminate\Support\Collection;
use xyqWeb\JoinPay\Service\BaseClient;
use xyqWeb\JoinPay\Support\RsaSigner;

class Client extends BaseClient
{
    /**
     * create pay request
     *
     * @param array $params
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \xyqWeb\JoinPay\Exceptions\HttpException
     * @throws \xyqWeb\JoinPay\Exceptions\InvalidArgumentException
     * @throws \xyqWeb\JoinPay\Exceptions\RuntimeException
     */
    public function pay(array $params): Collection
    {
        $url = $this->getFastApi('/fastpay');
        $baseParams = $this->baseFastParams();
        $baseParams['sec_key'] = RsaSigner::randomStr();
        if (!empty($params['data']['sms_code'])) {
            $baseParams['method'] = 'fastPay.agreement.smsPay';
        } else {
            $baseParams['method'] = 'fastPay.agreement.pay';
        }
        $params = array_merge($params, $baseParams);
        return $this->fastRequest($url, $params, 'POST');
    }

    /**
     * send sms
     *
     * @param array $params
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \xyqWeb\JoinPay\Exceptions\HttpException
     * @throws \xyqWeb\JoinPay\Exceptions\InvalidArgumentException
     * @throws \xyqWeb\JoinPay\Exceptions\RuntimeException
     */
    public function sendSms(array $params): Collection
    {
        $url = $this->getFastApi('/fastpay');
        $baseParams = $this->baseFastParams();
        $baseParams['sec_key'] = RsaSigner::randomStr();
        $baseParams['method'] = 'fastPay.agreement.paySms';
        $params = array_merge($params, $baseParams);
        return $this->fastRequest($url, $params, 'POST');
    }

    /**
     * query orders
     *
     * @param array $params
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \xyqWeb\JoinPay\Exceptions\HttpException
     * @throws \xyqWeb\JoinPay\Exceptions\InvalidArgumentException
     * @throws \xyqWeb\JoinPay\Exceptions\RuntimeException
     */
    public function query(array $params): Collection
    {
        $url = $this->getFastApi('/query');
        $baseParams = $this->baseFastParams();
        $baseParams['method'] = 'fastPay.query';
        $params = array_merge($params, $baseParams);
        return $this->fastRequest($url, $params, 'POST');
    }
}