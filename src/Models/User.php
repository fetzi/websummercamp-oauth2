<?php

namespace App\Models;

use League\OAuth2\Server\Entities\UserEntityInterface;

class User implements UserEntityInterface
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getIdentifier()
    {
        return $this->id;
    }
}
