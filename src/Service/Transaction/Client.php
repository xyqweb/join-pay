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
use InvalidArgumentException;
use xyqWeb\JoinPay\Constant\JoinPayType;
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
     */
    public function pay(array $params): Collection
    {
        $url = $this->getApi('/trade/uniPayApi.action');
        $baseParams = $this->baseParams();
        $baseParams['p0_Version'] = JoinPayType::API_VERSION;
        if (!$this->config->get('debug')) {
            if (empty($this->config->get('merchant_no'))){
                throw new InvalidArgumentException('报备商户号错误');
            }
            $params['qa_TradeMerchantNo'] = $this->config->get('merchant_no');
        }
        $params = array_merge($params, $baseParams);
        return $this->request($url, $params, 'POST');
    }

    /**
     * query orders
     *
     * @param array $params
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \xyqWeb\JoinPay\Exceptions\HttpException
     */
    public function query(array $params): Collection
    {
        $url = $this->getApi('/trade/queryOrder.action');
        $baseParams = $this->baseParams();
        $params = array_merge($params, $baseParams);
        return $this->request($url, $params, 'POST');
    }
}