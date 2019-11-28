<?php

namespace Jmhc\Mongodb;

use Hyperf\Database\Connectors\ConnectorInterface;
use Hyperf\Utils\Arr;
use MongoDB\Client;

class MongoConnector implements ConnectorInterface
{
    /**
     * Establish a database connection.
     *
     * @param array $config
     * @return Client
     */
    public function connect(array $config)
    {
        // Build the connection string
        $dsn = $this->getDsn($config);

        // You can pass options directly to the MongoDB constructor
        $options = Arr::get($config, 'options', []);

        // Create the connection
        $connection = $this->createConnection($dsn, $config, $options);

        // Select database
        $connection->selectDatabase($config['database']);

        return $connection;
    }

    /**
     * Create a new MongoDB connection.
     * @param string $dsn
     * @param array $config
     * @param array $options
     * @return Client
     */
    protected function createConnection($dsn, array $config, array $options)
    {
        // By default driver options is an empty array.
        $driverOptions = [];

        if (isset($config['driver_options']) && is_array($config['driver_options'])) {
            $driverOptions = $config['driver_options'];
        }

        // Check if the credentials are not already set in the options
        if (!isset($options['username']) && !empty($config['username'])) {
            $options['username'] = $config['username'];
        }
        if (!isset($options['password']) && !empty($config['password'])) {
            $options['password'] = $config['password'];
        }

        return new Client($dsn, $options, $driverOptions);
    }

    /**
     * Determine if the given configuration array has a dsn string.
     * @param array $config
     * @return bool
     */
    protected function hasDsnString(array $config)
    {
        return isset($config['dsn']) && !empty($config['dsn']);
    }

    /**
     * Get the DSN string form configuration.
     * @param array $config
     * @return string
     */
    protected function getDsnString(array $config)
    {
        return $config['dsn'];
    }

    /**
     * Get the DSN string for a host / port configuration.
     * @param array $config
     * @return string
     */
    protected function getHostDsn(array $config)
    {
        // Treat host option as array of hosts
        $hosts = is_array($config['host']) ? $config['host'] : [$config['host']];

        foreach ($hosts as &$host) {
            // Check if we need to add a port to the host
            if (strpos($host, ':') === false && !empty($config['port'])) {
                $host = $host . ':' . $config['port'];
            }
        }

        // Check if we want to authenticate against a specific database.
        $auth_database = isset($config['options']) && !empty($config['options']['database']) ? $config['options']['database'] : null;

        return 'mongodb://' . implode(',', $hosts) . ($auth_database ? '/' . $auth_database : '');
    }

    /**
     * Create a DSN string from a configuration.
     * @param array $config
     * @return string
     */
    protected function getDsn(array $config)
    {
        return $this->hasDsnString($config)
            ? $this->getDsnString($config)
            : $this->getHostDsn($config);
    }
}
