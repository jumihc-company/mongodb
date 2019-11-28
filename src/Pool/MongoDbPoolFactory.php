<?php

namespace Jmhc\Mongodb\Pool;

use Hyperf\DbConnection\Pool\DbPool;
use Hyperf\DbConnection\Pool\PoolFactory;
use Hyperf\Di\Container;

class MongoDbPoolFactory extends PoolFactory
{
    public function getPool(string $name): DbPool
    {
        if (isset($this->pools[$name])) {
            return $this->pools[$name];
        }

        if ($this->container instanceof Container) {
            $pool = $this->container->make(MongoDbPool::class, ['name' => $name]);
        } else {
            $pool = new MongoDbPool($this->container, $name);
        }

        return $this->pools[$name] = $pool;
    }
}
