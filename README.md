# Websummercamp 2019 - Understanding OAuth2

## Installation

```
composer install
```

## Seeding the database

```
composer db:migrate
composer db:seed
```

## Creating the signing keys

```
composer oauth:keys
```

## Starting the API

```
composer start
```

To make sure everything is working out correctly, you need to access the following url: `http://localhost:8080/authorize?response_type=code&client_id=1234`.

## Exercise 1 - OAuth 2 Flow

Build a simple webpage that provides a link to the `/authorize` endpoint to start the OAuth 2 flow.

In addition you have to implement the callback endpoint (`/callback`) that will receive the authorization code as a query parameter. The callback action needs to make an api call (`POST http://localhost:8080/token`) to the authorization server to exchange the authorization code against an access token. You will need to include the following form parameters:

```
grant_type=authorization_code
client_id=1234
client_secret=secret
redirect_uri=http://localhost:9999/callback
code={code from the request},
```

## Exercise 2 - Token introspection

In this exercise you will implement a call to the `/introspect` endpoint to check if the current token is valid or not. To keep the access token from the callback action you can use the PHP session to store it. The introspection api call is also a `POST` request with the following form parameter:

```
token={the stored access_token}
```

You can simply print the result returned from the introspection endpoint.