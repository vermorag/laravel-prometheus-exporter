<?php

declare(strict_types = 1);

namespace Arquivei\LaravelPrometheusExporter;

use InvalidArgumentException;
use Prometheus\Storage\Adapter;
use Prometheus\Storage\APC;
use Prometheus\Storage\InMemory;
use Prometheus\Storage\Redis;
use Prometheus\Storage\RedisNg;

class StorageAdapterFactory
{
    /**
     * Factory a storage adapter.
     *
     * @param string $driver
     * @param array  $config
     *
     * @return Adapter
     */
    public function make(string $driver, array $config = []) : Adapter
    {
        switch ($driver) {
            case 'memory':
                return new InMemory();
            case 'redis':
                return $this->makeRedisAdapter($config);
            case 'redis-ng':
                return $this->makeRedisNgAdapter($config);
            case 'apc':
                return new APC();
        }

        throw new InvalidArgumentException(sprintf('The driver [%s] is not supported.', $driver));
    }

    /**
     * Factory a redis storage adapter.
     *
     * @param array $config
     *
     * @return Redis
     */
    protected function makeRedisAdapter(array $config) : Redis
    {
        if (isset($config['prefix'])) {
            Redis::setPrefix($config['prefix']);
        }

        return new Redis($config);
    }

    /**
     * Factory a redis-ng storage adapter.
     *
     * @param array $config
     *
     * @return RedisNg
     */
    protected function makeRedisNgAdapter(array $config) : RedisNg
    {
        if (isset($config['prefix'])) {
            RedisNg::setPrefix($config['prefix']);
        }

        return new RedisNg($config);
    }
}
