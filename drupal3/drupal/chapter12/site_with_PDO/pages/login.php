<?php

include('./includes/database.php');

//Set $pdo as global so that the database object can be used here.
global $pdo;

$title = 'Log in';

// Process form submissions.
if (isset($_REQUEST['action'])) {
  switch ($_REQUEST['action']) {
    
    case 'logout':
      session_destroy();
      session_start();
      notice('You have been logged out');
      break;
    
    case 'login':
        $sql_queryString = "SELECT * FROM users WHERE username = '" . $_POST['username'] . "' AND password = '" . $_POST['password'] . "'";
        //Prepare the SQL statement to be executed.
        $sql_login = $pdo->prepare($sql_queryString);
        try {
            //Execute the SQL query.
            $sql_login->execute();
            //Check to see if the value entered exists in the table.
            if($row = $sql_login->fetch(PDO::FETCH_ASSOC)) {
                //Destroy the password.
                unset($row['password']);
                //Set the session variable to the user.
                $_SESSION['user'] = $row;
                notice('You have been logged in.');
            }
            else {
                notice('Ah, sorry, either the username or password was incorrect.');
            }
        }
        catch (PDOException $e) {
            print $e->getMessage();
        }
        break;
  }
}

if (isset($_SESSION['user'])) {
  $content = '
    <h1>Welcome, ' . $_SESSION['user']['username'] . '</h1>
    <p>You are logged in, enjoy!</p>';
} else {
  $content = '
  <h1>'. $title . '</h1>
  <form action="login.php" method="post">
    <p>Username: <input type="text" name="username" /></p>
    <p>Password: <input type="password" name="password" /></p>
    <p><input type="submit" value="Log in" />
    <input type="hidden" name="action" value="login" />
  </form>';
}
