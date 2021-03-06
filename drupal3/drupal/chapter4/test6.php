<?php

// First, we organize our information into an array.
$people = array(

  'George' => array(
    'birthyear' => 1972,
    'fav_band' => 'The Cure',
    'shoe_size' => 10,
  ),
  'Sally' => array(
    'birthyear' => 1975,
    'fav_band' => 'Coldplay',
    'shoe_size' => 8,
  ),
  'Deepak' => array(
    'birthyear' => 1969,
    'fav_band' => 'Beach Boys',
    'shoe_size' => 10,
  ),

);

// Initialize our $output variable to prevent any errors.
$output = '';

// Now we loop through each item in the array and build some content.
foreach ($people as $name => $details) {
  $output .= '
    <tr>
      <td>'. $name .'</td>
      <td>'. $details['birthyear'] . '</td>
      <td>'. $details['fav_band'] . '</td>
      <td>'. $details['shoe_size'] . '</td>
    </tr>';
}

// If there's content, let's wrap a table around it. If not, diplay placeholder text.
if ($output != '') {
  $output = '
    <table>
      <tr>
        <th>Name</th>
        <th>Birth year</th>
        <th>Favorite band</th>
        <th>Shoe size</th>
      </tr>
      '. $output .'
    </table>';
}
else {
  $output = '<p>It looks like we don\'t have any data to display.</p>';
}
  
print $output;

?>