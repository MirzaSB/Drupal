<?php

function parse_data($data_file) {

	ob_start();
	include($data_file);
	$data = ob_get_contents();
	ob_end_clean();

	$people_data_array = explode("\n", $data);

	foreach($people_data_array as $person_string) {
		$person_array = explode(",", $person_string);
		$name = trim($person_array[0]);
		$birthyear = trim($person_array[1]);
		$fav_band = trim($person_array[2]);
		$shoe_size = trim($person_array[3]);
		$people[$name] = array(
			"birthyear" => $birthyear,
			"fav_band" => $fav_band,
			"shoe_size" => $shoe_size,
			); 
	}

	$output = '';

	foreach($people as $name => $details) {
		$output .= '
		<tr>
			<td>' . $name . '</td>
			<td>' . $details["birthyear"] . '</td>
			<td>' . $details["fav_band"] . '</td>
			<td>' . $details["shoe_size"] . '</td>
		</tr>';
	}

	if($output != '') {
		$output .= '
		<table>
			<tr>
				<td>Name</td>
				<td>Year of birth</td>
				<td>Favorite band</td>
				<td>Show size</td>
			</tr>
		' . $output . '</table>';
	}
	else {
		$output = '<p>It looks like we do not have any data to display.</p>';
	}

	return $output;
}

print parse_data('data.txt');
?>