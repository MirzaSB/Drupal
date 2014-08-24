<?php

//Bring the data array.
include('data.php');

//Example of absolute path. The error will tell us where PHP is looking.
//include_once('/data.php');

//Looking up one level in the file system.
//include_once('../data.php');

//Looking up 3 levels in the file system.
//include_once('../../../data.php');

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