<?php
/**
 * User: YL
 * Date: 2019/11/12
 */

namespace Jmhc\Mongodb;

/**
 * 配置服务
 * @package Jmhc\Restful
 */
class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
        ];
    }
}
