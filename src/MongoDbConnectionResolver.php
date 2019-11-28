<?php

namespace Jmhc\Mongodb;

use Hyperf\DbConnection\ConnectionResolver;
use Jmhc\Mongodb\Pool\PoolFactory;
use Psr\Container\ContainerInterface;

class MongoDbConnectionResolver extends ConnectionResolver
{
    /**
     * @inheritdoc
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->factory = $container->get(PoolFactory::class);
    }
}
