<?php

namespace Jmhc\Mongodb\Pool;

use Hyperf\Contract\ConnectionInterface;
use Hyperf\DbConnection\Pool\DbPool;

class MongoDbPool extends DbPool
{
    protected function createConnection(): ConnectionInterface
    {
        return new MongoDbPoolConnection($this->container, $this, $this->config);
    }
}
