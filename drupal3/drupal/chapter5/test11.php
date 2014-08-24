<?php

//How to get contents from a file into a variable.
ob_start(); //Initiates the output buffer.
include('data.txt'); //Gets the data.txt file from the current directory.
$data = ob_get_contents(); //Put all the contents received from the output buffer inside the data variable.
ob_end_clean(); //Closes the output buffer and frees up some memory.

//Let's make sure that the data is stored correctly.
//die(var_dump($data));

//Create our first parser.

//Let's take our data and make it an array so it's easy to work with. This is called a 'parser'.
$people_data_array = explode("\n", $data);
//die(var_dump($people_data_array));

//Loop through each line.
foreach($people_data_array as $person_string) {
	$person_array = explode("," , $person_string);
	$name = trim($person_array[0]);
	$birthyear = trim($person_array[1]);
	$fav_band = trim($person_array[2]);
	$shoe_size = trim($person_array[3]);
	$people[$name] = array(
		'birthyear' => $birthyear,
		'fav_band' => $fav_band,
		'shoe_size' => $shoe_size,
		);
}

//Check the array to ensure parsing was done correctly.
//die(var_dump($people));

//Initialize our output variable to prevent any errors.
$output = '';

//Now go through each item in the array and build some content.
foreach ($people as $name => $details) {
	$output .= '
		<tr>
			<td>' . $name . '</td>
			<td>' . $details['birthyear'] . '</td>
			<td>' . $details['fav_band'] . '</td>
			<td>' . $details['shoe_size'] . '</td>
		</tr>';
}

//If there's content, let's wrap a table around it. If not, display placeholder text.
if($output != '') {
	$output = '
		<table>
			<tr>
				<th>Name</th>
				<th>Year of Birth</th>
				<th>Favorite Band</th>
				<th>Shoe size</th>
			</tr>
	' . $output . '
	</table>';
}
else {
	$output = '<p>It looks like we don\'t have any data to display.</p>';
}

print $output;

?>