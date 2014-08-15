<?php

// 1. Missing semi-colon.
// Parse error: syntax error, unexpected T_PRINT
$some_string = 'world';

// 2. Missing the '$' in the variable.
// Parse error: syntax error, unexpected '='
$some_string = 'world';

// 3. Missing quotes around the string.
// Notice: Use of undefined constant world - assumed 'world'
$some_string = "world";

// 4. Missing the period when concatenating.
// Parse error: syntax error, unexpected T_CONSTANT_ENCAPSED_STRING
$some_string = 'my' . 'world';

print 'Hello, ' . $some_string . '!';

?>