<?php

//Parses data from a file.
function parse_data_file($data_file, $array_labels, $record_divider = "\n", $data_divider = ",") {

	ob_start();
	include($data_file);
	$data = ob_get_contents();
	ob_end_clean();

	$data_array = explode($record_divider, $data);

	foreach($data_array as $string) {
		$array = explode($data_divider, $string);
		foreach($array as $key => $value) {
			$item[$array_labels[$key]] = trim($value);
		}
		$items[] = $item;
	}

	return $items;
}

//Takes array of values and output it as a table.
//this time, we're adding a new parameter, $search_phrase.
function output_as_table($items, $search_phrase='') {
	
	//Handle an empty data set.
	if(count($items) == 0) {
		return '<p>It looks like we do not have any data to display.</p>';
	}

	//Get header
	$table_heading = '';
	foreach($items[0] as $heading => $value) {
		$table_heading .= '<th>' . $heading . '</th>';
	}
	$th = '<tr>' . $table_heading . '</tr>';

	//Get table content.
	$table_body = '';
	foreach ($items as $item_array) {
		
		//But we're going to use a ternary operator.
		$search_match = ($search_phrase != '') ? FALSE : TRUE;

		$row = '';
		foreach($item_array as $value) {
			if(!$search_match) {
				if(strstr($value, $search_phrase)) {
					$search_match = TRUE;
				}
			}
			$row .= '<td>' . $value . '</td>';
		}
		if ($search_match) {
			$table_body .= '<tr>' . $row . '</tr>';
		}
	}

	$output = '<table>' . $table_heading . $table_body . '</table>';
	return $output;

}

//Another ternary operator example.
$search_phrase = isset($_POST['phrase']) ? $_POST['phrase'] : '';

$data_file = 'data.txt';

$array_labels = array('Name', 'Birth year', 'Favoriate Band', 'Shoe Size');
$items = parse_data_file($data_file, $array_labels, "\n", ",");
print output_as_table($items, $search_phrase);

?>

<form action="test3.php" method="post">
	Search phrase: <input type="text" name="phrase" value="<?php print $search_phrase ?>" /> <input type="submit" value="Search" />
</form>