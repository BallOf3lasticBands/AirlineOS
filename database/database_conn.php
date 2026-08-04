<?php

$hostname = 'localhost';
$database_username = 'root';
$database_password = '';
$database_name = 'airlineos';

$db_connect = mysqli_connect($hostname, $database_username, $database_password, $database_name);

if (!$db_connect){
    echo 'Could not connect to database: ' . mysqli_error();
    exit();
}
