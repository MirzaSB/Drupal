<?php


/**
 * Replace tokens with random items. (it's good to provide examples of how to use parameters)
 * For example:
 * $string = 'This peach is ADVERB ADJECTIVEPUNCTUATION';
 * $replacements = array(
 *  'ADVERB' => array('very', 'kind of', 'super', 'not'),
 *  'ADJECTIVE' => array('good', 'bad', 'ugly'),
 *  'PUNCTUATION' => array('!', '?', '...', '.'),
 *  );
 **/
function replace_tokens_with_randomness($string, $replacements) { 
  
  // Loop through each replacement. We can have as many replacements as we want.
  foreach ($replacements as $token => $replacement_array) {
    $replacement_string = array_rand(array_flip($replacement_array));
    $string = str_replace($token, $replacement_string, $string);
  }
  
  return $string;
}

// Show 3 sets of inputs to begin with.
$num_inputs = 3;

if (isset($_POST['action'])) {
  
  switch ($_POST['action']) {
    
    case 'generate_inputs':
      $num_inputs = $_POST['num'];
      break;
    
    case 'randomize':
      $num_inputs = $_POST['num'];
      for ($i = 1; $i <= $_POST['num']; $i++) {
        $trimmed_replacements = array();
        $replacement_array = explode(',', $_POST['replacements_' . $i]);
        // Let's clean them up, just in case the user used spaces.
        foreach ($replacement_array as $replacement) {
          $trimmed_replacements[] = trim($replacement);
        }
        $replacements[$_POST['token_' . $i]] = $trimmed_replacements;
      }
      print "<strong>" . replace_tokens_with_randomness($_POST['string'], $replacements) . "</strong>";
      break;
  }
  
}

// Loop through number of inputs.
$token_inputs = '';
for ($i = 1; $i <= $num_inputs; $i++) {
  
  // Let's also keep our entered values to make it easier to modify.
  $token_value = isset($_POST['token_' . $i]) ? $_POST['token_' . $i] : '';
  $replacements_value = isset($_POST['replacements_' . $i]) ? $_POST['replacements_' . $i] : '';
  
  $token_inputs .= '<p>Token ' . $i . ': <input type="text" name="token_' . $i . '" value="' . $token_value . '" />, and replacements: <input type="text" size="50" name="replacements_' . $i . '" value="' . $replacements_value . '" /></p>';
}

// Build the full form.
$randomize_form = '
  <form action="test5.php" method="post">
    <p>Enter a string with tokens that will be replaced with some value. For replacements, separate options with commas. For example:</p>
    <p><strong>String: "I am MOOD", token: "MOOD", replacements: "happy,sad,morose,fantastic"</strong></p>
    <p>String with tokens: <input type="text" size="100" name="string" value="' . (isset($_POST['string']) ? $_POST['string'] : '') . '" /></p>
    ' . $token_inputs . '
    <input type="submit" value="Generate randomness!" />
    <input type="hidden" name="action" value="randomize" />
    <input type="hidden" name="num" value="' . $num_inputs . '" />
  </form>';

?>

<form action="test5.php" method="post">
  <p>How many tokens do you want to use? <input type="text" name="num" value="<?php print $num_inputs; ?>" /></p>
  <p><input type="submit" value="Generate token inputs" /></p>
  <input type="hidden" name="action" value="generate_inputs" />
</form>

<?php print $randomize_form; ?>