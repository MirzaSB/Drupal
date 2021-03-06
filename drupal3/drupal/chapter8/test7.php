<?php

// So we can use $_SESSION variables.
session_start();

// We'll be gathering information to display to the user about thier actions in this array.
$notices = array();

// An array of users with access.
$users = array(
   'admin' => 'pa55word',
   'someone' => '28f3hj2',
   'homestar' => 'RUNNING',
 );

// Process form submissions.
if (isset($_REQUEST['action'])) {
  switch ($_REQUEST['action']) {
    
    case 'logout':
      session_destroy();
      session_start();
      $notices[] = 'You have been logged out';
      break;
    
    case 'login':
      foreach ($users as $user => $pass) {
        if ($_POST['username'] == $user && $_POST['password'] == $pass) {
          $_SESSION['username'] = $user;
          break;
        }
      }
      if (!isset($_SESSION['username'])) {
        $notices[] = 'Ah, sorry, either the username or password was incorrect.';
      } else {
        $notices[] = 'You have been logged in.';
      }
      break;
    
  }
}

// Generate output if logged in or not logged in.
if (isset($_SESSION['username'])) {
  $output = '
    <p><a href="test7.php?action=logout">Log out</a></p>
    <h1>Welcome, ' . $_SESSION['username'] . '</h1>
    <p>You are logged in, enjoy!</p>';
} else {
  $output = '
  <form action="test7.php" method="post">
    <p>Username: <input type="text" name="username" /></p>
    <p>Password: <input type="password" name="password" /></p>
    <p><input type="submit" value="Log in" />
    <input type="hidden" name="action" value="login" />
  </form>';
}

// Render notices as HTML.
$notices_output = '';
if (count($notices) > 0) {
  $notices_output = '
    <div style="border:1px solid #333;background:#666;color:white;font-weight:bold;padding:5px;margin:5px;">
      <ul><li>' . implode('</li><li>', $notices) . '</li></ul>
    </div>';
}

print $notices_output . $output;

?>