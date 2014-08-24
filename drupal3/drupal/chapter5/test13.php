<?php

//In this example, the $variable becomes a different variable when it enters the function.
function function_with_no_reference($variable) {
	$variable = "We are trying to change this variable: " . $variable;
}

//In this example, the $variable is a reference to the variable that got passed in.
function function_with_reference(&$variable) {
	$variable = "This variable was passed into a function and modified: " . $variable;
}

$my_variable = "This is the original value";

//function_with_no_reference($my_variable);
//print $my_variable;

function_with_reference($my_variable);
print $my_variable;

?>