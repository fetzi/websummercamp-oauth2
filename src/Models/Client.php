<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use League\OAuth2\Server\Entities\ClientEntityInterface;

class Client extends Model implements ClientEntityInterface
{
    protected $fillable = ['identifier', 'secret', 'name', 'redirect_uri'];

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function getSecret()
    {
        return $this->secret;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }

    public function isConfidential()
    {
        return true;
    }
}