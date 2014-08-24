<?php

//Parse data from a file.
function parse_data_file($data_file, $array_labels, $record_divider = "\n", $data_divider = ",") {

	ob_start();
	include($data_file);
	$data = ob_get_contents();
	ob_end_clean();

	$data_array = explode($record_divider, $data);

	foreach($data_array as $string) {
		$array = explode($data_divider, $string);
		foreach ($array as $key => $value) {
			$item[$array_labels[$key]] = trim($value);
		}
		$items[] = $item;
	}

	return $items;
}

//Takes array of values and output it as a table.
function output_as_table($items) {

	//Handle an empty data set.
	if(count($items) == 0) {
		return '<p>It looks like we do not have any data to display.</p>';
	}

	//Get header.
	$table_heading = '';
	foreach($items[0] as $heading => $value) {
		$table_heading .= '<th>' . $heading . '</th>';
	}
	$th = '<tr>' . $table_heading . '</tr>';

	//Get table content
	$table_body = '';
	foreach ($items as $item_array) {
		$row = '';
		foreach($item_array as $value) {
			$row .= '<td>' . $value . '</td>';
		}
		$table_body .= '<tr>' . $row . '</tr>';
	}

	$output = '<table>' . $table_heading . $table_body . '</table>';
	return $output;
}

//Creates the table from our previous example.
$data_file = 'data.txt';
$array_labels = array('Name', 'Birth year', 'Favorite Band', 'Shoe size');
$items = parse_data_file($data_file, $array_labels);
print output_as_table($items);

//Demonstrates how we can use output_table_table() on a different set of data.
$items = array();
$items[] = array(
	'First' => 'Frankie',
	'Instrument' => 'Sax',
	"Years of Experience" => 4,
	);
$items[] = array(
	'First' => 'Tracy',
	'Instrument' => 'Tuba',
	"Years of Experience" => 2,
	);

print output_as_table($items);

?>