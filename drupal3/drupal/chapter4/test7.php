<?php

// 1. Example of a 'while' loop.
// First, set a counter.
$i = 1;
// Set a condition that - if true - continues the loop.
while ($i <= 5) {
  print '<h' . $i . '>This is heading ' . $i . '</h'. $i .'>';
  // Increment the counter (or the loop will never end)
  //$i++; // Or, $i = $i + 1
}

// 2. Example of a 'for' loop, indentical to the while loop above.
for ($i = 1; $i <= 5; $i++) {
  print '<h' . $i . '>This is heading ' . $i . '</h'. $i .'>';
}

?>