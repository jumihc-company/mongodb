<?php

namespace Jmhc\Mongodb\Pool;

use Hyperf\Contract\ConnectionInterface;
use Hyperf\DbConnection\Pool\DbPool;
use Jmhc\Mongodb\MongoPoolConnection;

class MongoDbPool extends DbPool
{
    protected function createConnection(): ConnectionInterface
    {
        return new MongoPoolConnection($this->container, $this, $this->config);
    }
}
