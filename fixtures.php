<?php

require_once 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use WeLovePhp\Items\ItemsManager;
use WeLovePhp\Users\UsersManager;

try {
    $config = new \Doctrine\DBAL\Configuration();
    $connectionParams = array(
        'dbname' => 'welovephp',
        'user' => 'root',
        'password' => 'ok123',
        'host' => 'localhost',
        'driver' => 'pdo_mysql',
    );
    $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

    $conn->executeQuery($conn->getDatabasePlatform()->getTruncateTableSQL('items'));
    $conn->executeQuery($conn->getDatabasePlatform()->getTruncateTableSQL('users'));

    $redis = new Predis\Client('tcp://127.0.0.1:6379');

    $im = new ItemsManager($conn, $redis);
    $um = new UsersManager($conn, $redis);

    $im->create('we love php 1');
    $im->create('we love php 2');
    $im->create('we love php 3');
    $im->create('we love php 4');
    $im->create('we love php 5');
    $im->create('we love php 6');
    $im->create('we love php 7');

    $um->create('adan');
    $um->create('gonzalo');
    $um->create('javier');
    $um->create('marcos');
    $um->create('manu');
    $um->create('roger');

} catch (\Exception $e) {
    die($e->getMessage());
}