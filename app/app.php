<?php

require __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

define('URL', $_ENV['URL_ADDRESS']);

use Delight\Db\PdoDatabase;
use Delight\Db\PdoDsn;
use Delight\Auth\Auth;

$host       = 'mysql:host='.$_ENV["DB_HOST"].';port='.$_ENV["DB_PORT"].';dbname='.$_ENV["DB_NAME"].';charset=utf8mb4';
$username   = $_ENV['DB_USERNAME'];
$password   = $_ENV['DB_PASSWORD'];

$PdoDns = new PdoDsn(
    $host,
    $username,
    $password
);

$db     = PdoDatabase::fromDsn($PdoDns);
$auth   = new Auth($db);

if ($auth->isLoggedIn()) {
    // user is signed in
    $inuser = $db->selectRow(
        'SELECT * FROM users WHERE id = ?',
        [ $auth->getUserId() ]
    );
}