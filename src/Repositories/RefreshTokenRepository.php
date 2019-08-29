<?php

namespace App\Repositories;

use App\Models\RefreshToken;
use Carbon\Carbon;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntityInterface)
    {
        $refreshTokenEntityInterface->save();
    }

    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken($tokenId)
    {
        $refreshToken = RefreshToken::where('identifier', $tokenId)->first();

        if (!is_null($refreshToken)) {
            $refreshToken->revoked_at = Carbon::now();
            $refreshToken->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        $refreshToken = RefreshToken::where('identifier', $tokenId)->first();

        return is_null($refreshToken) || !is_null($refreshToken->revoked_at);
    }

    /**
     * {@inheritdoc}
     */
    public function getNewRefreshToken()
    {
        $refreshToken = new RefreshToken();

        return $refreshToken;
    }
}
