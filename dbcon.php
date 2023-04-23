<?php

require __DIR__.'/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Auth;

$factory = (new Factory)
    ->withServiceAccount('inventory-management-sys-4a080-firebase-adminsdk-97269-6b979ff0ee.json')
    ->withDatabaseUri('https://inventory-management-sys-4a080-default-rtdb.asia-southeast1.firebasedatabase.app/');

    $database = $factory->createDatabase();
    $auth = $factory->createAuth();
?>