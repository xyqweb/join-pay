<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/13/23
 * Time: 5:48 PM
 */

namespace xyqWeb\JoinPay\Service;


use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use xyqWeb\JoinPay\Constant\RespCode;
use xyqWeb\JoinPay\Exceptions\HttpException;
use xyqWeb\JoinPay\Exceptions\JoinPayException;
use xyqWeb\JoinPay\Support\Config;
use xyqWeb\JoinPay\Support\Http;
use xyqWeb\JoinPay\Support\ServiceContainer;
use xyqWeb\JoinPay\Support\Signer;
use xyqWeb\JoinPay\Traits\ResponseCastable;

class BaseClient
{
    use ResponseCastable;
    /**
     * API版本
     */
    public const API_VERSION = 2.1;

    /**
     * 正式环境API地址
     */
    public $TRANSACTION_API_HOST = 'https://www.joinpay.com';

    /**
     * @var Config
     */
    protected $config;
    /**
     * @var
     */
    protected $http;

    /**
     * BaseClient constructor.
     * @param ServiceContainer $container
     */
    public function __construct(ServiceContainer $container)
    {
        $config = $container['config'] ?? [];
        $this->config = $config;
    }

    /**
     * @param string $api
     * @param array $params
     * @param string $method
     * @return Collection
     * @throws GuzzleException
     * @throws HttpException
     */
    public function request(string $api, array $params, string $method = 'post'): Collection
    {
        $params['hmac'] = (new Signer())->sign($params, $this->config->get('key'));
        $options = [
            'http' => $this->config->get('http'),
            'form_params' => $params
        ];

        $response = $this->getHttp()->request($api, $method, $options);
        if ($response->getStatusCode() !== 200) {
            throw new HttpException('[汇聚支付异常]请求异常: 状态码 ' . $response->getStatusCode());
        }
        return $this->castResponse($response);
    }

    /**
     * @return array 解除参数
     */
    public function baseParams(): array
    {
        // 加载配置数据
        return [
                'p1_MerchantNo' => $this->config->get('p1_MerchantNo'),
            ];
    }

    /**
     * 请求客户端
     *
     * @return Http
     */
    public function getHttp(): Http
    {
        if (is_null($this->http)) {
            $this->http = new Http($this->config->get('http'));
        }
        return $this->http;
    }

    /**
     * 获取API地址
     *
     * @param string $api
     * @return string
     */
    public function getApi(string $api): string
    {
        return $this->TRANSACTION_API_HOST . $api;
    }

    /**
     * @throws JoinPayException
     */
    public function checkResult(Collection $response)
    {
        //验证
        if (isset($response['ra_Code']) && RespCode::SUCCESS === $response['ra_Code']) {
            //得到响应，解密数据
//            $sign = (new Signer())->sign($response->toArray(),$this->config->get('key'));
//            if (strtoupper($sign)!=$response['hmac']){
//                $message = $response['rb_CodeMsg'] ?? '系统错误';
//                $code = $response['ra_Code'] ?? '';
//                throw new JoinPayException('[支付异常]异常代码：' . $code . ' 异常信息：' . $message, $code);
//            }
            return;
        }
        $message = $response['rb_CodeMsg'] ?? '系统错误';
        $code = $response['ra_Code'] ?? '';
        throw new JoinPayException('[支付异常]异常代码：' . $code . ' 异常信息：' . $message, $code);
    }
}