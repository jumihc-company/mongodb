<?php

namespace Jmhc\Mongodb;

use Hyperf\Database\Connection;
use Hyperf\Database\MySqlConnection;
use InvalidArgumentException;

class ConnectionFactory extends \Hyperf\Database\Connectors\ConnectionFactory
{
    protected function createConnection($driver, $connection, $database, $prefix = '', array $config = [])
    {
        if ($resolver = Connection::getResolver($driver)) {
            return $resolver($connection, $database, $prefix, $config);
        }

        switch ($driver) {
            case 'mysql':
                return new MySqlConnection($connection, $database, $prefix, $config);
            case 'mongodb':
                return new MongoConnection($connection, $database, $prefix, $config);
        }

        throw new InvalidArgumentException("Unsupported driver [{$driver}]");
    }
}
