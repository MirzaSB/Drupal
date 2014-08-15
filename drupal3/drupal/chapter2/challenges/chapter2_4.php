<?php
/*
Find out how to remove the period from the string variable using trim().
*/

$variable = "  This is a 
  <strong>string</strong> example.  ";

//Remove the extra spaces.
$variable = trim($variable);
//Now remove the period.
$variable = trim($variable, ".");

//Print the final value.
print $variable;

?>