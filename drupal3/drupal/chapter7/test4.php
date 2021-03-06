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

// Process our form, looping through each token.
if (isset($_POST['action']) && $_POST['action'] == 'randomize') {
  for ($i = 1; $i <= 3; $i++) {
    $trimmed_replacements = array();
    $replacement_array = explode(',', $_POST['replacements_' . $i]);
    // Let's clean them up, just in case the user used spaces.
    foreach ($replacement_array as $replacement) {
      $trimmed_replacements[] = trim($replacement);
    }
    $replacements[$_POST['token_' . $i]] = $trimmed_replacements;
  }
  print "<strong>" . replace_tokens_with_randomness($_POST['string'], $replacements) . "</strong>";
}

?>

<form action="test4.php" method="post">
  <p>Enter a string with tokens that will be replaced with some value. For replacements, separate options with commas. For example:</p>
  <p><strong>String: "I am MOOD", token: "MOOD", replacements: "happy,sad,morose,fantastic"</strong></p>
  <p>String with tokens: <input type="text" size="100" name="string" /></p>
  <p>Token 1: <input type="text" name="token_1" />, and replacements: <input type="text" size="50" name="replacements_1" /></p>
  <p>Token 2: <input type="text" name="token_2" />, and replacements: <input type="text" size="50" name="replacements_2" /></p>
  <p>Token 3: <input type="text" name="token_3" />, and replacements: <input type="text" size="50" name="replacements_3" /></p>
  <input type="submit" value="Generate randomness!" />
  <input type="hidden" name="action" value="randomize" />
</form>