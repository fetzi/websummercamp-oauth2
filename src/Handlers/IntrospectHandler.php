<?php

namespace App\Handlers;

use App\TokenVerifier;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IntrospectHandler
{
    /**
     * @var Parser
     */
    private $jwtParser;

    /**
     * @var TokenVerifier
     */
    private $tokenVerifier;

    public function __construct(
        Parser $jwtParser,
        TokenVerifier $tokenVerifier
    ) {
        $this->jwtParser = $jwtParser;
        $this->tokenVerifier = $tokenVerifier;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $body = $request->getParsedBody();

        $token = $body['token'] ?? '';

        if (!empty($token)) {
            try {
                $token = $this->jwtParser->parse($token);

                if ($this->tokenVerifier->verify($token)) {
                    return $this->respondActive($response, $token);
                }
            } catch (\Exception $exception) {
                return $this->respondJson(
                    $response,
                    [
                    'error' => 'invalid_access_token',
                    ],
                    400
            );
            }
        }

        return $this->respondNotActive($response);
    }

    private function respondActive(ResponseInterface $response, Token $token)
    {
        return $this->respondJson(
            $response,
            [
                'active' => true,
                'token_type' => 'access_token',
                'scope' => $token->getClaim('scopes', ''),
                'client_id' => $token->getClaim('aud'),
                'exp' => $token->getClaim('exp'),
                'iat' => $token->getClaim('iat'),
                'sub' => $token->getClaim('sub'),
                'jti' => $token->getClaim('jti'),
            ]
        );
    }

    private function respondNotActive(ResponseInterface $response)
    {
        return $this->respondJson($response, [
            'active' => false,
        ]);
    }

    private function respondJson(ResponseInterface $response, array $data, $statusCode = 200)
    {
        $response = $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);

        $response->getBody()->write(json_encode($data));

        return $response;
    }
}