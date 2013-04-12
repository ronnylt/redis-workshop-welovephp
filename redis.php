<?php

require_once 'vendor/autoload.php';

$redis = new Predis\Client('tcp://127.0.0.1:6379');

$redis->set('event:name', 'WeLovePHP');

print_r($redis->get('event:name'));
