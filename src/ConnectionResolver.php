<?php

namespace Jmhc\Mongodb;

use Hyperf\DbConnection\Pool\PoolFactory;
use Jmhc\Mongodb\Pool\MongoDbPoolFactory;
use Psr\Container\ContainerInterface;

class ConnectionResolver extends \Hyperf\DbConnection\ConnectionResolver
{
    /**
     * The default connection name.
     *
     * @var string
     */
    protected $default = 'mongodb';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        if ($this->default == 'mongodb') {
            $this->factory = $container->get(MongoDbPoolFactory::class);
        } else {
            $this->factory = $container->get(PoolFactory::class);
        }
    }
}
