<?php

namespace App\Handlers;

use App\Repositories\SessionRepository;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthorizeHandler
{
    /**
     * @var AuthorizationServer
     */
    private $authorizationServer;

    /**
     * @var SessionRepository
     */
    private $sessionRepository;

    public function __construct(AuthorizationServer $authorizationServer, SessionRepository $sessionRepository)
    {
        $this->authorizationServer = $authorizationServer;
        $this->sessionRepository = $sessionRepository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        try {
            $authRequest = $this->authorizationServer->validateAuthorizationRequest($request);
            $this->sessionRepository->set('auth-request', $authRequest);

            return $response->withHeader('Location', '/login')->withStatus(302);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        }
    }
}