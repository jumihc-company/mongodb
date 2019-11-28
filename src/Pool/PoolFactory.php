<?php

namespace Jmhc\Mongodb\Pool;

use Hyperf\Contract\ConfigInterface;
use Hyperf\DbConnection\Pool\DbPool;
use Hyperf\DbConnection\Pool\PoolFactory as BasePoolFactory;
use Hyperf\Di\Container;
use Hyperf\Di\Exception\NotFoundException;
use InvalidArgumentException;

class PoolFactory extends BasePoolFactory
{
    /**
     * @inheritdoc
     * @throws NotFoundException
     */
    public function getPool(string $name): DbPool
    {
        if (isset($this->pools[$name])) {
            return $this->pools[$name];
        }

        return $this->pools[$name] = $this->createPool($name);
    }

    /**
     * Create pool
     * @param string $name
     * @return DbPool|MongoDbPool|mixed
     * @throws NotFoundException
     */
    protected function createPool(string $name)
    {
        $config = $this->container->get(ConfigInterface::class);

        $key = sprintf('databases.%s', $name);
        if (!$config->has($key)) {
            throw new InvalidArgumentException(sprintf('config[%s] is not exist!', $key));
        }

        $driver = $config->get(sprintf('%s.driver', $key));
        if (!$driver) {
            throw new InvalidArgumentException('A driver must be specified.');
        }

        return $this->getPoolByDriver($driver, $name);
    }

    /**
     * Get pool by driver
     * @param string $driver
     * @param string $name
     * @return DbPool|MongoDbPool|mixed
     * @throws NotFoundException
     */
    protected function getPoolByDriver(string $driver, string $name)
    {
        if ($driver == 'mongodb') {
            return $this->getMongodbPool($name);
        }

        return $this->getDbPool($name);
    }

    /**
     * Get MongodbPool
     * @param string $name
     * @return MongoDbPool|mixed
     * @throws NotFoundException
     */
    protected function getMongodbPool(string $name)
    {
        if ($this->container instanceof Container) {
            return $this->container->make(MongoDbPool::class, ['name' => $name]);
        }

        return new MongoDbPool($this->container, $name);
    }

    /**
     * Get DbPool
     * @param string $name
     * @return DbPool|mixed
     * @throws NotFoundException
     */
    protected function getDbPool(string $name)
    {
        if ($this->container instanceof Container) {
            return $this->container->make(DbPool::class, ['name' => $name]);
        }

        return new DbPool($this->container, $name);
    }
}
