<?php

use App\Repositories\AccessTokenRepository;
use App\Repositories\AuthCodeRepository;
use App\Repositories\ClientRepository;
use App\Repositories\RefreshTokenRepository;
use App\Repositories\ScopeRepository;
use App\TokenVerifier;
use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        Manager::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');
            $manager = new Manager();
            $manager->addConnection($settings['database'], 'default');
            $manager->setAsGlobal();
            $manager->bootEloquent();
            return $manager;
        },
        AuthCodeGrant::class => function (ContainerInterface $container) {
            $grant = new AuthCodeGrant(
                $container->get(AuthCodeRepository::class),
                $container->get(RefreshTokenRepository::class),
                new \DateInterval('PT1M')
            );

            $grant->setRefreshTokenTTL(new \DateInterval('P1M'));

            return $grant;
        },
        RefreshTokenGrant::class => function (ContainerInterface $container) {
            $grant = new RefreshTokenGrant(
                $container->get(RefreshTokenRepository::class)
            );

            $grant->setRefreshTokenTTL(new \DateInterval('P1M'));

            return $grant;
        },
        AuthorizationServer::class => function (ContainerInterface $container) {
            $config = $container->get('settings')['oauth'];

            $encryptionKey = $config['encryption-key'];
            $privateKey = $config['private-key'];

            $server = new AuthorizationServer(
                $container->get(ClientRepository::class),
                $container->get(AccessTokenRepository::class),
                $container->get(ScopeRepository::class),
                $privateKey,
                $encryptionKey
            );

            $server->enableGrantType(
                $container->get(AuthCodeGrant::class),
                new \DateInterval('PT1H')
            );

            $server->enableGrantType(
                $container->get(RefreshTokenGrant::class),
                new \DateInterval('PT1H')
            );

            return $server;
        },
        TokenVerifier::class => function (ContainerInterface $container) {
            $config = $container->get('settings')['oauth'];
            $publicKey = $config['public-key'];

            return new TokenVerifier($publicKey, $container->get(AccessTokenRepository::class));
        }
    ]);
};
