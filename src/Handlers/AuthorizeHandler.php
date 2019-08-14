<?php

namespace App\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthorizeHandler
{
    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write(json_encode(['setup' => 'success']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}