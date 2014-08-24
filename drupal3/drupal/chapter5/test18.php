<?php

/*
// 1. Accidentally using one = instead of 2 ==
$my_variable = 'test';
if ($my_variable == 'something else') {
  // Do something.
}
print $my_variable;
*/

// 2. Missing a closing bracket.
// Parse error: syntax error, unexpected $end

$array = array('one', 'two', 'three');
$test = 'test';
if ($test == 'test') {
  foreach ($array as $item) {
    // Do
    // something
    // here.
  }
}


// 3. Missing an opening bracket
// Parse error: syntax error, unexpected '}'

$array = array('one', 'two', 'three');
$test = 'test';
if ($test == 'test') { 
  foreach ($array as $item) {
    // Do
    // something
    // here.
  }
}


// 4. Missing a parentheses
// Parse error: syntax error, unexpected '{'

$array = array('one', 'two', 'three');
$test = 'test';
if ('test' == strip_tags(trim($test))) {
  foreach ($array as $item) {
    // Do
    // something
    // here.
  }
}


// 5. Missing an argument
// Warning: Wrong parameter count

$test = 'test';
$test = trim($test);


?>