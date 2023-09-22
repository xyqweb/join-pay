<?php

namespace xyqWeb\JoinPay\Support;

use Pimple\Container;
use xyqWeb\JoinPay\Constant\JoinPayType;
use xyqWeb\JoinPay\Constant\TradeType;

class FastServiceContainer extends Container
{
    /**
     * @var array
     */
    protected $providers = [];
    /**
     * @var array
     */
    protected $userConfig = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct();
        $this->userConfig = $config;
        $this->registerProviders($this->getProviders());
        $this->logConfiguration($config);
    }

    /**
     * Log configuration.
     *
     * @param array $config
     */
    public function logConfiguration(array $config)
    {
        $config = new Config($config);
        $keys = ['mch_no', 'platform_public_key', 'public_key', 'private_key'];
        foreach ($keys as $key) {
            !$config->has($key) || $config[$key] = '***' . substr($config[$key], -6);
        }
    }

    /**
     * Add a provider.
     *
     * @param string $provider
     *
     * @return FastServiceContainer
     */
    public function addProvider(string $provider): FastServiceContainer
    {
        $this->providers[] = $provider;
        return $this;
    }

    /**
     * Set providers.
     *
     * @param array $providers
     */
    public function setProviders(array $providers)
    {
        $this->providers = [];
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    /**
     * Return all providers.
     *
     * @return array
     */
    public function getProviders(): array
    {
        return array_merge([
            FastConfigServiceProvider::class,
        ], $this->providers);
    }

    /**
     * @return array
     */
    public function getDefaultConfig(): array
    {
        $base = [
            'http' => [
                'timeout' => 60.0,
            ],
            'debug' => true,
            'mch_no' => '',
            'version' => JoinPayType::FAST_API_VERSION,
            'rand_str' => RsaSigner::randomStr(32),
            'sign_type' => '2',
            'platform_public_key' => '',
            'public_key' => '',
            'private_key' => '',
        ];
        return array_replace_recursive($base, $this->userConfig);
    }

    /**
     * Register providers.
     * @param array $providers
     */
    private function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            $this->register(new $provider());
        }
    }

    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get(string $id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed $value
     */
    public function __set(string $id, $value)
    {
        $this->offsetSet($id, $value);
    }
}