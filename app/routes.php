<?php

use App\Handlers\AuthorizeHandler;
use Slim\App;

return function (App $app) {
    $app->get('/authorize', AuthorizeHandler::class . ':index');
};
