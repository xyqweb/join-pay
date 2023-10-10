<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: xingyongqiang
 * Date: 5/13/23
 * Time: 5:48 PM
 */

namespace xyqWeb\JoinPay\Service;


use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Pimple\Container;
use xyqWeb\JoinPay\Constant\JoinPayType;
use xyqWeb\JoinPay\Constant\RespCode;
use xyqWeb\JoinPay\Exceptions\HttpException;
use xyqWeb\JoinPay\Exceptions\JoinPayException;
use xyqWeb\JoinPay\Support\AesSigner;
use xyqWeb\JoinPay\Support\Config;
use xyqWeb\JoinPay\Support\Http;
use xyqWeb\JoinPay\Support\RsaSigner;
use xyqWeb\JoinPay\Support\Signer;
use xyqWeb\JoinPay\Traits\ResponseCastable;

class BaseClient
{
    use ResponseCastable;

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
     * @param Container $container
     */
    public function __construct(Container $container)
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
        $params['hmac'] = Signer::sign($params, $this->config->get('key'));
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
     * @param string $api
     * @param array $params
     * @param string $method
     * @return Collection
     * @throws GuzzleException
     * @throws HttpException
     * @throws \xyqWeb\JoinPay\Exceptions\InvalidArgumentException
     * @throws \xyqWeb\JoinPay\Exceptions\RuntimeException
     */
    public function fastRequest(string $api, array $params, string $method = 'post'): Collection
    {
        foreach ($params['data'] as $key => &$value) {
            if (in_array($key, JoinPayType::REQUIRE_ENCRYPTED_FIELDS)) {
                $value = AesSigner::encryptECB($value, $params['sec_key']);
            }
        }
        if (!empty($params['sec_key'])) {
            $params['sec_key'] = RsaSigner::encrypt($params['sec_key'], $this->config->get('platform_public_key'));
        }
        $params['data'] = json_encode($params['data'], JSON_UNESCAPED_UNICODE);
        $params['sign'] = RsaSigner::sign($params, $this->config->get('private_key'));
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
     * @return array 解除参数
     */
    public function baseFastParams(): array
    {
        // 加载配置数据
        return [
            'mch_no' => $this->config->get('mch_no'),
            'version' => $this->config->get('version'),
            'rand_str' => $this->config->get('rand_str'),
            'sign_type' => $this->config->get('sign_type'),
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
        return JoinPayType::TRANSACTION_API_HOST . $api;
    }

    /**
     * 获取快捷API地址
     *
     * @param string $api
     * @return string
     */
    public function getFastApi(string $api): string
    {
        return JoinPayType::TRANSACTION_FAST_API_HOST . $api;
    }

    /**
     * @throws JoinPayException
     */
    public function checkResult(Collection $response)
    {
        if (strtolower($response['hmac']) != Signer::sign($response->toArray(), $this->config->get('key'))) {
            throw new JoinPayException('[支付异常]异常代码：10080002 异常信息：验证签名失败', '10080002');
        }
        //验证支付
        if (isset($response['ra_Code'])) {
            if (RespCode::SUCCESS === intval($response['ra_Code'])) {
                return;
            }
            $message = $response['rb_CodeMsg'] ?? '系统错误';
            $code = $response['ra_Code'] ?? '';
            throw new JoinPayException('[支付异常]异常代码：' . $code . ' 异常信息：' . $message, $code);
        }
        //验证退款
        if (isset($response['rb_Code'])) {
            if (RespCode::SUCCESS === intval($response['rb_Code'])) {
                return;
            }
            $message = $response['rc_CodeMsg'] ?? '系统错误';
            $code = $response['rb_Code'] ?? '';
            throw new JoinPayException('[退款异常]异常代码：' . $code . ' 异常信息：' . $message, $code);
        }
        $message = '系统错误';
        $code = '';
        throw new JoinPayException('[支付异常]异常代码：' . $code . ' 异常信息：' . $message, $code);
    }

    /**
     * checkFastResult
     *
     * @param Collection $response
     * @return Collection
     * @throws JoinPayException
     * @throws \xyqWeb\JoinPay\Exceptions\InvalidArgumentException
     * @author xyq
     */
    public function checkFastResult(Collection $response): Collection
    {
        if (!RsaSigner::verify($response->toArray(), $response['sign'], $this->config->get('platform_public_key'))) {
            throw new JoinPayException('[支付异常]异常代码：JS100001 异常信息：签名验证失败', 'JS100001');
        }
        if ($response['biz_code'] != RespCode::FAST_BIZ_CODE_SUCCESS) {
            $message = $response['biz_msg'] ?? '系统错误';
            $code = $response['biz_code'] ?? '';
            throw new JoinPayException('[支付异常]异常代码：' . $code . ' 异常信息：' . $message, $code);
        }
        if (is_string($response['data'])) {
            $response['data'] = json_decode($response['data'], true);
        }
        if (is_array($response['data']) && !empty($response['data'])) {
            if (!empty($response['sec_key'])) {
                $response['sec_key'] = RsaSigner::decrypt($response['sec_key'], $this->config->get('private_key'));
            }
            $data = $response['data'];
            foreach ($data as $key => &$val) {
                if (in_array($key, JoinPayType::REQUIRE_ENCRYPTED_FIELDS)) {
                    $val = AesSigner::decryptECB($val, $response['sec_key']);
                }
            }
            $response['data'] = $data;
        }
        $message = $response['data']['err_msg'] ?? '系统错误';
        $code = $response['data']['err_code'] ?? '';
        // 验证签约
        if (isset($response['data']['status'])) {
            if (!empty($response['data']['sign_no'])) {
                return $response;
            }
            $message = '签约失败';
            $code = 'P2000';
        }
        //验证支付
        if (isset($response['data']['order_status'])) {
            if (RespCode::FAST_SUCCESS === $response['data']['order_status'] || RespCode::FAST_AGREE_SUCCESS === $response['data']['order_status']) {
                return $response;
            }
            throw new JoinPayException('[支付异常]异常代码：' . $code . ' 异常信息：' . $message, $code);
        }
        //验证退款
        if (isset($response['data']['refund_status'])) {
            if (RespCode::SUCCESS === intval($response['data']['refund_status'])) {
                return $response;
            }
            throw new JoinPayException('[退款异常]异常代码：' . $code . ' 异常信息：' . $message, $code);
        }
        throw new JoinPayException('[支付异常]异常代码：' . $code . ' 异常信息：' . $message, $code);
    }
}