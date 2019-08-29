<?php

namespace App\Repositories;

use App\Models\Client;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getClientEntity($clientIdentifier)
    {
        return Client::where('identifier', $clientIdentifier)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        $client = Client::where('identifier', $clientIdentifier)->first();

        if (!is_null($client)) {
            return $client->secret === $clientSecret;
        }

        return false;
    }
}
