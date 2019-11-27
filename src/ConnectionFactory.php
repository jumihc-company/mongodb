<?php

namespace Jmhc\Mongodb;

use Hyperf\Database\Connectors\MySqlConnector;
use InvalidArgumentException;

class ConnectionFactory extends \Hyperf\Database\Connectors\ConnectionFactory
{
    protected function createConnection($driver, $connection, $database, $prefix = '', array $config = [])
    {
        if (! isset($config['driver'])) {
            throw new InvalidArgumentException('A driver must be specified.');
        }

        if ($this->container->has($key = "db.connector.{$config['driver']}")) {
            return $this->container->get($key);
        }

        switch ($config['driver']) {
            case 'mysql':
                return new MySqlConnector();
            case 'mongodb':
                return new Connection($config);
        }

        throw new InvalidArgumentException("Unsupported driver [{$config['driver']}]");
    }
}
