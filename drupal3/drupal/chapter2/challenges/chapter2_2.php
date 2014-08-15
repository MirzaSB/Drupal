<?php
/*
Figure out how to capitalize the first letter of all words, including the word in the <strong> tag, but 
without removing the <strong> tag permanently.
*/

$variable = "  This is a 
  <strong>string</strong> example.  ";

//Capitalize each word.
$variable = ucwords($variable);

//Special case to capitalize the first alphabet of the word inside the tag.
$variable = str_replace("string", ucwords("string"), $variable);

//Print the final value.
print $variable;

?>