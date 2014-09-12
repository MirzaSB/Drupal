<?php

include('database.php');

$output = '';

//Execute an SQL query.
$sql_queryString = 'SELECT * FROM people';
//$sql_queryString = 'SELECT * FROM people WHERE shoe_size > 8';
//$sql_queryString = "SELECT * FROM people WHERE shoe_size > 8 AND name LIKE '%ee%'";
//$sql_queryString = "SELECT name from people ORDER BY name ASC";
$sql = $pdo->prepare($sql_queryString);
$sql->execute();

//Fetch all of the values and print them out.
//$result = $sql->fetchAll();
while($result = $sql->fetch(PDO::FETCH_ASSOC)) {
    foreach ($result as $key => $val) {
        $output .= $key . ' = ' . $val . '<br />';
    }
    $output .= "<br />";
}

print $output;

?>