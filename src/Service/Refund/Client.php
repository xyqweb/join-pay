<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/15/23
 * Time: 10:02 AM
 */

namespace xyqWeb\JoinPay\Service\Refund;


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
     */
    public function create(array $params): Collection
    {
        $url = $this->getApi('/trade/refund.action');
        $baseParams = $this->baseParams();
        $baseParams['q1_version'] = self::API_VERSION;
        $params = array_merge($params, $baseParams);
        $response = $this->request($url, $params, 'POST');
        return $response;
    }

    /**
     * query refund info
     *
     * @param array $params
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \xyqWeb\JoinPay\Exceptions\HttpException
     */
    public function refundQuery(array $params): Collection
    {
        $url = $this->getApi('/trade/queryRefund.action');
        $baseParams = $this->baseParams();
        $baseParams['p3_Version'] = self::API_VERSION;
        $params = array_merge($params, $baseParams);
        $response = $this->request($url, $params, 'POST');
        return $response;
    }
}