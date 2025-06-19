<?php

define('DB_HOST', 'db');
define('DB_NAME', 'auth_db');
define('DB_USER', 'user');
define('DB_PASSWORD', 'password');

$connect = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if($connect->connect_error){
    die("Connection failed: " . $connect->connect_error);
}
