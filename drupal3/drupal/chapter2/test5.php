<?php

// 1. Our original example.
$some_string = 'world';

// 2. A numeric variable, notice there's no quotes.
$some_string = 42;

// 3. We can perform a calculation and assign the result to a variable.
$some_string = 42 * 10;

// 4. We can use the variable as part of a calculation.
$some_string = 23 + $some_string;

// 5. We can re-assign a variable a value that includes the original variable.
$some_string = "the number " .  $some_string;

// 6. TRUE or FALSE are considered 'boolean' values.
$some_string = TRUE;

// 7. Let's see what FALSE looks like.
$some_string = FALSE;

// 8. This is an example of a variable that will change over time.
$some_string = time();

// Let's print out our variable.
print 'Hello, ' . $some_string . '!';

?>