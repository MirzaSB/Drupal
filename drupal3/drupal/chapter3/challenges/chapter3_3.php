<?php

/*
Take our multi-dimensional array and use array_pop() to get the youngest person and print their
favorite band to the browser.
*/

// Arrays can hold arrays, those can hold arrays, and so on.
$my_array = array(
  'George' => array(
    'birthyear' => 1972,
    'fav_band' => 'The Cure',
    'shoe_size' => 10,
  ),
  'Lucy' => array(
    'birthyear' => 1984,
    'fav_band' => 'The Beatles',
    'shoe_size' => 9,
  ),
  'Sally' => array(
    'birthyear' => 1975,
    'fav_band' => 'Coldplay',
    'shoe_size' => 8,
  ),
  'Deepak' => array(
    'birthyear' => 1969,
    'fav_band' => 'Beach Boys',
    'shoe_size' => 10,
  )
);

//Get the youngest person in the array based on the "birthyear" value.
asort($my_array);

//Get the youngest person out using array_pop()
$youngest = array_pop($my_array);
print "The youngest person's favoriate band is " . $youngest['fav_band'];
//var_dump($my_array);

?>