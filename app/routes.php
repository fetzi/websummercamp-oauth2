<?php

use App\Handlers\AuthorizeHandler;
use App\Handlers\IntrospectHandler;
use App\Handlers\LoginHandler;
use App\Handlers\TokenHandler;
use Slim\App;

return function (App $app) {
    $app->get('/authorize', AuthorizeHandler::class);

    $app->get('/login', LoginHandler::class . ':get');
    $app->post('/login', LoginHandler::class . ':post');

    $app->post('/token', TokenHandler::class);

    $app->post('/introspect', IntrospectHandler::class);
};
