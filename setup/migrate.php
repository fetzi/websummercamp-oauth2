<?php

use Illuminate\Database\Schema\Blueprint;
include __DIR__ . '/bootstrap.php';

$schema = $capsule->schema('default');
// cleanup
$schema->dropAllTables();
$schema->create('access_tokens', function(Blueprint $table) {
    $table->increments('id');
    $table->string('identifier');
    $table->integer('client_id');
    $table->integer('user_id');
    $table->string('scopes');
    $table->timestamp('expires_at');
    $table->timestamp('revoked_at')->nullable();
    $table->timestamps();
  });

  $schema->create('auth_codes', function(Blueprint $table) {
    $table->increments('id');
    $table->string('identifier');
    $table->integer('client_id');
    $table->integer('user_id');
    $table->string('scopes');
    $table->string('redirect_uri');
    $table->timestamp('expires_at');
    $table->timestamp('revoked_at')->nullable();
    $table->timestamps();
  });

  $schema->create('clients', function(Blueprint $table) {
    $table->increments('id');
    $table->string('identifier');
    $table->string('secret');
    $table->string('name');
    $table->string('redirect_uri');
    $table->timestamps();
  });

  $schema->create('refresh_tokens', function(Blueprint $table) {
    $table->increments('id');
    $table->string('identifier');
    $table->integer('access_token_id');
    $table->string('scopes')->nullable();
    $table->timestamp('expires_at');
    $table->timestamp('revoked_at')->nullable();
    $table->timestamps();
  });