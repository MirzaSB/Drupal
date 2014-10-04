<?php

//MySQL database credentials.
$server = get_setting('db_server');
$username = get_setting('db_username');
$password = get_setting('db_password');
$database = get_setting('db_database');

try {
    //Connect to the DB.
    $pdo = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    echo $e->getMessage();
}

?>