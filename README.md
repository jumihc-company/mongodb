## 介绍

简单移植 `jenssegers/mongodb` , 使其能够运行在 [hyperf](https://github.com/hyperf/hyperf) 下。

## 安装

使用以下命令安装：
```
composer require jmhc/mongodb
```

## 配置

> 配置路径为 `config/autoload/databases.php` 
>
> 详细配置参考 [configuration](https://github.com/jenssegers/laravel-mongodb#configuration)

配置示例：
```php
    ...
    'mongodb' => [
        'driver'   => 'mongodb',
        'host'     => env('MONGODB_HOST', 'mongo'),
        'port'     => env('MONGODB_PORT', 27017),
        'database' => env('MONGODB_DATABASE', 'mongo'),
        'username' => env('MONGODB_USERNAME', ''),
        'password' => env('MONGODB_PASSWORD', ''),
        'options'  => [
            'database' => env('MONGODB_AUTH_DATABASE', 'admin'),
        ],
        'pool' => [
            'min_connections' => 1,
            'max_connections' => 10,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => (float) env('MONGODB_MAX_IDLE_TIME', 60),
        ],
    ],
```

## 使用

> 需要配置数据库链接 `driver` 为 `mongodb`
> 
> 默认使用 `mongodb` 链接

```php
use Jmhc\Mongodb\Eloquent\Model;

class Message extends Model
{}
```
