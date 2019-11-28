<?php

namespace Jmhc\Mongodb;

use Hyperf\Database\Connectors\ConnectionFactory;
use Hyperf\DbConnection\Pool\PoolFactory as BasePoolFactory;
use Jmhc\Mongodb\Pool\PoolFactory;

/**
 * 配置服务
 * @package Jmhc\Restful
 */
class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                BasePoolFactory::class => PoolFactory::class,
                ConnectionFactory::class => MongoDbConnectionFactory::class,
                'db.connector.mongodb' => MongoDbConnector::class,
            ],
        ];
    }
}
