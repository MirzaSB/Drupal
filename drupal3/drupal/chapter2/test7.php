<?php

// See http://php.net/manual/en/ref.strings.php for all string functions. Or Google 'php string functions'.

$variable = "  This is a 
  <strong>string</strong> example.  ";

// Returns FALSE if the string does not exists.
//$variable = strstr($variable, 'peaches');

// Convert new lines to <br /> tags.
//$variable = nl2br($variable);

// Remove white space (spaces, new lines and tabs) from the beginning and end.
//$variable = trim($variable);

// Return the length of the string.
//$variable = strlen($variable);

// Replace one part of the string with another.
//$variable = str_replace('example', 'party', $variable);

// Remove HTML from the string.
//$variable = strip_tags($variable);

// Covert all the characters to uppercase.
//$variable = strtoupper($variable);

// Capitalize each word.
//$variable = ucwords($variable);

// Return the date.
$variable = date('F j, Y', time()-24*60*60);

print $variable;

?>