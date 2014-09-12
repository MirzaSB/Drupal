<?php

//MySQL database credentials.
$server = 'localhost';
$username = 'root';
$password = 'root';
$database = 'my_database';

try {
    //Connect to the DB.
    $pdo = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //print "Connected to the database, '$database'" . "<br />";
    //print "<br />";

}
catch (PDOException $e) {
    echo $e->getMessage();
}

?>