<?php

//A very simple function.

function simple_function() {
	return "I am the return value from a function!";
}

//A function with 2 parameters.
function perform_calculation($num1, $num2) {
	return $num1 + $num2;
}

//A practical function to roll dice.
function roll_dice($highest_num = 6, $num_dice = 1) {
	$output = '';
	for($i = 0; $i < $num_dice; $i++) {
		$number = rand(1, $highest_num);
		$output .= '[' . $number . ']';
	}
	$output = 'The result of your roll was: ' . $output;
	return $output;
}

print simple_function() . "\n";
print "The sum of numbers 2 and 3 is : " . perform_calculation(2,3) . "."; 

print roll_dice() . "\n";
print roll_dice(20, 10);

?>