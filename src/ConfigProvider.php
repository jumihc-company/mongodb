<?php

namespace Jmhc\Mongodb;

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
                \Hyperf\Database\Connectors\ConnectionFactory::class => ConnectionFactory::class,
                'db.connector.mongodb' => MongoConnector::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
        ];
    }
}
