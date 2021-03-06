<?php

//Original code
$adjective_array = array("good", "bad", "ugly");
$adjective = array_rand(array_flip($adjective_array));

$adverb_array = array("very", "kind of", "super", "not");
$adverb = array_rand(array_flip($adverb_array));

$punctuation_array = array('!', '?', '...', '.');
$punctuation = array_rand(array_flip($punctuation_array));

$output = "This peach is " . $adverb . " " . $adjective . $punctuation;

//Only yell if it is an exclaimation mark.
if($punctuation == "!") {
	$output = strtoupper($output);
}

print $output;

//Our code wrapped in a function.
function generate_peach_string() {

	$adjective_array = array("good", "bad", "ugly");
	$adjective = array_rand(array_flip($adjective_array));

	$adverb_array = array("very", "kind of", "super", "not");
	$adverb = array_rand(array_flip($adverb_array));

	$punctuation_array = array('!', '?', '...', '.');
	$punctuation = array_rand(array_flip($punctuation_array));

	$output = "This peach is " . $adverb . " " . $adjective . $punctuation;

	//Only yell if it is an exclaimation mark.
	if($punctuation == "!") {
		$output = strtoupper($output);
	}

	return $output;
}

//Now we can run the logic anywhere we want.
print "<p>One cat said '" . generate_peach_string() . "' but the other one said '" . generate_peach_string() . "</p>";

?>