<?php

// See http://php.net/manual/en/ref.array.php for all array functions. Or Google 'php array functions'.

$associative_array = array('George' => 1982, 'Sally' => 1973, 'Deepak' => 1969);

// 1. Sort by value, ascending.
asort($associative_array);
//var_dump($associative_array);

// 2. Sort by key, ascending.
ksort($associative_array);
//var_dump($associative_array);

// 3. Pick a random item.
//$random_item = array_rand($associative_array);
//var_dump($random_item);

// 4. Take an item off the end of an array.
array_pop($associative_array);
//var_dump($associative_array);

// 5. Return the number of items in the array.
$count = count($associative_array);
var_dump($count);

?>