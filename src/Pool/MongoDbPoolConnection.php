<?php

namespace Jmhc\Mongodb\Pool;

use Hyperf\Contract\ConnectionInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Database\Connection;
use Hyperf\Database\ConnectionInterface as DbConnectionInterface;
use Hyperf\DbConnection\Pool\DbPool;
use Hyperf\DbConnection\Traits\DbConnection;
use Hyperf\Pool\Connection as BaseConnection;
use Hyperf\Pool\Exception\ConnectionException;
use Jmhc\Mongodb\MongoDbConnectionFactory;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class MongoDbPoolConnection extends BaseConnection implements ConnectionInterface, DbConnectionInterface
{
    use DbConnection;

    /**
     * @var DbPool
     */
    protected $pool;

    /**
     * @var DbConnectionInterface
     */
    protected $connection;

    /**
     * @var MongoDbConnectionFactory
     */
    protected $factory;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    protected $transaction = false;

    /**
     * @inheritdoc
     */
    public function __construct(ContainerInterface $container, DbPool $pool, array $config)
    {
        parent::__construct($container, $pool);
        $this->factory = $container->get(MongoDbConnectionFactory::class);
        $this->config = $config;
        $this->logger = $container->get(StdoutLoggerInterface::class);

        $this->reconnect();
    }

    public function __call($name, $arguments)
    {
        return $this->connection->{$name}(...$arguments);
    }

    /**
     * @inheritdoc
     */
    public function getActiveConnection(): DbConnectionInterface
    {
        if ($this->check()) {
            return $this;
        }

        if (! $this->reconnect()) {
            throw new ConnectionException('MongoDb connection reconnect failed.');
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function reconnect(): bool
    {
        $this->connection = $this->factory->make($this->config);

        if ($this->connection instanceof Connection) {
            // Reset event dispatcher after db reconnect.
            if ($this->container->has(EventDispatcherInterface::class)) {
                $dispatcher = $this->container->get(EventDispatcherInterface::class);
                $this->connection->setEventDispatcher($dispatcher);
            }

            // Reset reconnector after db reconnect.
            $this->connection->setReconnector(function ($connection) {
                $this->logger->warning('MongoDb database connection refreshing.');
                if ($connection instanceof Connection) {
                    $this->refresh($connection);
                }
            });
        }

        $this->lastUseTime = microtime(true);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function close(): bool
    {
        unset($this->connection);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function release(): void
    {
        if ($this->isTransaction()) {
            $this->rollBack(0);
            $this->logger->error('Maybe you\'ve forgotten to commit or rollback the MongoDb transaction.');
        }
        parent::release();
    }

    public function setTransaction(bool $transaction): void
    {
        $this->transaction = $transaction;
    }

    public function isTransaction(): bool
    {
        return $this->transaction;
    }

    /**
     * Refresh pdo and readPdo for current connection.
     * @param Connection $connection
     */
    protected function refresh(Connection $connection)
    {
        $refresh = $this->factory->make($this->config);
        if ($refresh instanceof Connection) {
            $connection->disconnect();
            $connection->setPdo($refresh->getPdo());
            $connection->setReadPdo($refresh->getReadPdo());
        }

        $this->logger->warning('MongoDb database connection refreshed.');
    }
}
