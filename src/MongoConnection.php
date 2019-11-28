<?php

namespace Jmhc\Mongodb;

use Hyperf\Database\Connection as BaseConnection;
use Jmhc\Mongodb\Query\Grammar as QueryGrammar;
use Jmhc\Mongodb\Query\Processor;
use Jmhc\Mongodb\Schema\Builder;
use Jmhc\Mongodb\Schema\Grammar as SchemaGrammar;

class MongoConnection extends BaseConnection
{
    /**
     * @inheritdoc
     */
    public function disconnect()
    {
        unset($this->pdo);
    }

    /**
     * Get a MongoDB collection.
     * @param string $name
     * @return Collection
     */
    public function getCollection($name)
    {
        return new Collection($this, $this->getPdo()->selectCollection($name));
    }

    /**
     * @inheritdoc
     */
    public function getElapsedTime($start)
    {
        return parent::getElapsedTime($start);
    }

    /**
     * Get a schema builder instance for the connection.
     */
    public function getSchemaBuilder(): Builder
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new Builder($this);
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \Hyperf\Database\Grammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new QueryGrammar());
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Hyperf\Database\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar());
    }

    /**
     * Get the default post processor instance.
     *
     * @return \Hyperf\Database\Query\Processors\Processor
     */
    protected function getDefaultPostProcessor()
    {
        return new Processor();
    }
}
