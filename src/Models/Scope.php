<?php

namespace App\Models;

use League\OAuth2\Server\Entities\ScopeEntityInterface;

class Scope implements ScopeEntityInterface
{
    public function getIdentifier()
    {
        return 'scopeid';
    }

    public function jsonSerialize()
    {
        return [
            'identifier' => 'scopeid',
        ];
    }
}
