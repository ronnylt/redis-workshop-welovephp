<?php

require_once 'vendor/autoload.php';
require_once 'config.php';

use WeLovePhp\Items\ItemsManager;
use Doctrine\DBAL\DriverManager;

try {
    $config = new \Doctrine\DBAL\Configuration();
    $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    $redis = new Predis\Client('tcp://127.0.0.1:6379');


    $manager = new ItemsManager($conn, $redis);


    $manager->create('we love php');

    $items = $manager->getLatestItems(4);
    print_r($items);
} catch (\Exception $e) {
    die($e->getMessage());
}