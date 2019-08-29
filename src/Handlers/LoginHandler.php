<?php

namespace App\Handlers;

use App\Models\User;
use App\Repositories\SessionRepository;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginHandler
{
    /**
     * @var SessionRepository
     */
    private $sessionRepository;

    /**
     * @var AuthorizationServer
     */
    private $authorizationServer;

    public function __construct(
        SessionRepository $sessionRepository,
        AuthorizationServer $authorizationServer
    )
    {
        $this->sessionRepository = $sessionRepository;
        $this->authorizationServer = $authorizationServer;
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response)
    {
        $html = '<form action="/login" method="post"><input type="submit" value="Do Login"/></form>';

        $response->getBody()->write($html);

        return $response->withHeader('Content-Type', 'text/html');
    }

    public function post(ServerRequestInterface $request, ResponseInterface $response)
    {
        $user = new User(1);

        if ($user) {
            $authRequest = $this->sessionRepository->get('auth-request');

            $authRequest->setUser($user);
            $authRequest->setAuthorizationApproved(true);

            $this->sessionRepository->forget('auth-request');

            return $this->authorizationServer->completeAuthorizationRequest($authRequest, $response);
        }
    }
}