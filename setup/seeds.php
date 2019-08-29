<?php

use App\Models\Client;

include __DIR__ . '/bootstrap.php';

Client::create([
    'identifier' => '1234',
    'secret' => 'secret',
    'name' => 'test',
    'redirect_uri' => 'http://localhost:9999/callback',
]);