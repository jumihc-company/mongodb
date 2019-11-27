<?php

namespace Jmhc\Mongodb;

class ConnectionResolver extends \Hyperf\DbConnection\ConnectionResolver
{
    /**
     * The default connection name.
     *
     * @var string
     */
    protected $default = 'mongodb';
}
