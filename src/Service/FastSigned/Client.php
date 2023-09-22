<?php

namespace xyqWeb\JoinPay\Service\FastSigned;

use Illuminate\Support\Collection;
use xyqWeb\JoinPay\Service\BaseClient;
use xyqWeb\JoinPay\Support\RsaSigner;

class Client extends BaseClient
{
    /**
     * send signed sms
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
        $baseParams['method'] = 'fastPay.agreement.signSms';
        $params = array_merge($params, $baseParams);
        return $this->fastRequest($url, $params, 'POST');
    }

    /**
     * create signed order request
     *
     * @param array $params
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \xyqWeb\JoinPay\Exceptions\HttpException
     * @throws \xyqWeb\JoinPay\Exceptions\InvalidArgumentException
     * @throws \xyqWeb\JoinPay\Exceptions\RuntimeException
     */
    public function signed(array $params): Collection
    {
        $url = $this->getFastApi('/fastpay');
        $baseParams = $this->baseFastParams();
        $baseParams['sec_key'] = RsaSigner::randomStr();
        $baseParams['method'] = 'fastPay.agreement.smsSign';
        $params = array_merge($params, $baseParams);
        return $this->fastRequest($url, $params, 'POST');
    }

    /**
     * cancel signed
     *
     * @param array $params
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \xyqWeb\JoinPay\Exceptions\HttpException
     * @throws \xyqWeb\JoinPay\Exceptions\InvalidArgumentException
     * @throws \xyqWeb\JoinPay\Exceptions\RuntimeException
     */
    public function cancelSigned(array $params): Collection
    {
        $url = $this->getFastApi('/fastpay');
        $baseParams = $this->baseFastParams();
        $baseParams['sec_key'] = RsaSigner::randomStr();
        $baseParams['method'] = 'fastPay.agreement.unSign';
        $params = array_merge($params, $baseParams);
        return $this->fastRequest($url, $params, 'POST');
    }

    /**
     * query signed orders
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
        $url = $this->getFastApi('/fastpay');
        $baseParams = $this->baseFastParams();
        $baseParams['sec_key'] = RsaSigner::randomStr();
        $baseParams['method'] = 'fastPay.agreement.qrySign';
        $params = array_merge($params, $baseParams);
        return $this->fastRequest($url, $params, 'POST');
    }
}