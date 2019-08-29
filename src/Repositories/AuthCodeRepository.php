<?php

namespace App\Repositories;

use App\Models\AuthCode;
use Carbon\Carbon;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $authCodeEntity->save();
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAuthCode($codeId)
    {
        $authCode = AuthCode::where('identifier', $codeId)->first();

        if (!is_null($authCode)) {
            $authCode->revoked_at = Carbon::now();
            $authCode->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthCodeRevoked($codeId)
    {
        $authCode = AuthCode::where('identifier', $codeId)->first();

        return is_null($authCode) || !is_null($authCode->revoked_at);
    }

    /**
     * {@inheritdoc}
     */
    public function getNewAuthCode()
    {
        $authCode = new AuthCode();
        $authCode->scopes = [];

        return $authCode;
    }
}
