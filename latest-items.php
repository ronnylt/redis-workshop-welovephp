<?php

require_once 'vendor/autoload.php';

use WeLovePhp\Items\ItemsManager;
use Doctrine\DBAL\DriverManager;

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
    $redis = new Predis\Client('tcp://127.0.0.1:6379');


    $manager = new ItemsManager($conn, $redis);


    $manager->create('we love php');

    $items = $manager->getLatestItems(4);
    print_r($items);
} catch (\Exception $e) {
    die($e->getMessage());
}