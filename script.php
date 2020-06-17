<?php

use Bank\CommissionTask\Classes\User as User;

require 'vendor/autoload.php';

$handle = fopen($argv[1], "r");
if ($handle) {
    $csv = array_map('str_getcsv', file($argv[1]));
    fclose($handle);
} else {
}

$user_data = [];
foreach ($csv as $row) {
    $date = $row[0];
    $id = $row[1];
    $user_type = $row[2];
    $operation_type = $row[3];
    $amount = $row[4];
    $currency = $row[5];
    $inArray = false;
        
    foreach ($user_data as $user) {
        if ($user->getId() == $id) {
            $inArray = true;
            break;
        }
    }
    if (!$inArray) {
        $user_data[$id] =  new User($id, $user_type);

    } 

    fwrite(STDOUT, $user_data[$id]->operate($operation_type, $amount, $currency, $date)."\n");
}

