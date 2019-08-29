<?php

namespace App;

use App\Repositories\AccessTokenRepository;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;

class TokenVerifier
{
    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var AccessTokenRepository
     */
    private $accessTokenRepository;

    public function __construct($publicKey, AccessTokenRepository $accessTokenRepository)
    {
        $this->publicKey = $publicKey;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    public function verify(Token $token)
    {
        $signer = new Sha256();

        $publicKey = 'file://' . realpath($this->publicKey);

        $keychain = new Keychain();

        try {
            if (!$token->verify($signer, $keychain->getPublicKey($publicKey))) {
                return false;
            }

            $data = new ValidationData(time());

            if (!$token->validate($data)) {
                return false;
            }

            if ($this->accessTokenRepository->isAccessTokenRevoked($token->getClaim('jti'))) {
                return false;
            }

            return true;
        } catch(\Exception $ex) {
            return false;
        }
    }
}