<?php

/*
Print a random name to the browser in the form of "Hello [randomname], how are you?"
*/

$arr = array('George' => 1982, 'Sally' => 1973, 'Deepak' => 1969);

print "Hello " . array_rand($arr) . ", how are you?";

?>