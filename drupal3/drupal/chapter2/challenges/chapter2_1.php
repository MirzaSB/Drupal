<?php
/*
Display the number of characters that were removed by using trim().
*/

$variable = "  This is a 
  <strong>string</strong> example.  ";

//Look for all spaces, remove them, keep track of count.
$variable = str_replace(" ", "", $variable, $count);

//Print out the number of replacements made.
print $count;

?>