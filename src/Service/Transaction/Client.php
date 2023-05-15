<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/15/23
 * Time: 9:57 AM
 */

namespace xyqWeb\JoinPay\Service\Transaction;


use Illuminate\Support\Collection;
use xyqWeb\JoinPay\Service\BaseClient;

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
     */
    public function pay(array $params): Collection
    {
        $url = $this->getApi('/trade/uniPayApi.action');
        $baseParams = $this->baseParams();
        $baseParams['p0_Version'] = self::API_VERSION;
        $params = array_merge($params, $baseParams);
        $response = $this->request($url, $params, 'POST');
        return $response;
    }

    /**
     * query orders
     *
     * @param array $params
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \xyqWeb\JoinPay\Exceptions\HttpException
     * @throws \xyqWeb\JoinPay\Exceptions\InvalidArgumentException
     */
    public function query(array $params): Collection
    {
        $url = $this->getApi('/trade/queryOrder.action');
        $baseParams = $this->baseParams();
        $params = array_merge($params, $baseParams);
        $response = $this->request($url, $params, 'POST');
        return $response;
    }
}