<?php
include __DIR__ . '/../vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;
$dotenv = Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->load();
$capsule = new Capsule;
$capsule->addConnection(
  [
    'driver'   => getenv('DB_DRIVER'),
    'database' => __DIR__ . getenv('DB_DATABASE'),
    'prefix'   => '',
  ],
  'default'
);
$capsule->setAsGlobal();
$capsule->bootEloquent();