<?php

namespace Jmhc\Mongodb;

use Hyperf\Database\Connectors\ConnectionFactory;
use Jmhc\Mongodb\Pool\MongoDbPoolFactory;

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
                MongoDbPoolFactory::class => MongoDbPoolFactory::class,
                ConnectionFactory::class => MongoDbConnectionFactory::class,
                'db.connector.mongodb' => MongoDbConnector::class,
            ],
        ];
    }
}
