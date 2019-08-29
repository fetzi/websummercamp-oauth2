<?php

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production
            'logger' => [
                'name' => 'slim-app',
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
            'database' => [
                'driver'   => getenv('DB_DRIVER'),
                'database' => __DIR__ . getenv('DB_DATABASE'),
                'prefix'   => '',
            ],
            'oauth' => [
                'encryption-key' => 'encryptionkeyverysecret',
                'private-key' => __DIR__ . '/../keys/private.key',
                'public-key' => __DIR__ . '/../keys/public.key',
            ]
        ],
    ]);
};
