<?php

/*
Figure out how to sort an array in reverse order
*/

// Associative arrays have a 'key' and a 'value'.
$my_array = array('George' => 1972, 'Sally' => 1975, 'Deepak' => 1969);

//Sort the array in reverse order with respect to the values.
arsort($my_array);
var_dump($my_array);

?>