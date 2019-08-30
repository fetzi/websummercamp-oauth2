<?php
include '../vendor/autoload.php';

use GuzzleHttp\Client;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$path = $_SERVER['PATH_INFO'] ?? '';

switch ($path) {
    case '/callback':
        callbackAction();
        break;
    default:
        indexAction();
        break;
}

function callbackAction()
{
    parse_str($_SERVER['QUERY_STRING'], $query);

    $client = new Client();

    $response = $client->post('http://localhost:8080/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '1234',
            'client_secret' => 'secret',
            'redirect_uri' => 'http://localhost:9999/callback',
            'code' => $query['code'],
        ]
    ]);

    $tokenInfo = json_decode($response->getBody()->getContents(), true);

    $_SESSION['token'] = $tokenInfo['access_token'];
    $json = json_encode($tokenInfo, JSON_PRETTY_PRINT);

    require(__DIR__ . '/callback.php');
}

function indexAction()
{
    echo file_get_contents(__DIR__ . '/index.html');
}