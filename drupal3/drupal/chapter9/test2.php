<?php

//Start a session.
session_start();

//Include the database.php file to connect to the database.
include('database.php');

//Initialize a notices array variable.
$notices = array();

//Initalize variables.
$result = "";

//Process form submissions.
if(isset($_REQUEST['action'])) {

    //Create a switch conditional...
    switch ($_REQUEST['action']) {

        case 'logout':
            session_destroy();
            session_start();
            $notices[] = "You have been logged out";
            break;

        case 'login':
            //Paste in "' OR ''='" as the password to bypass valid login.
            $sql_queryString = "SELECT * FROM people WHERE username='" . $_POST['username'] . "' AND password = '" . $_POST['password'] . "'";
            $notices[] = $sql_queryString;
            //Prepare the SQL statement to be executed.
            $sql = $pdo->prepare($sql_queryString);
            //Execute the SQL query.
            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_ASSOC);
            //Set the username in the session variable.
            if(!empty($result)) {
                $_SESSION['username'] = $result['username'];
            }
            if(!isset($_SESSION['username'])) {
                $notices[] = 'Ah, sorry, either the username or password was incorrect';
            }
            else {
                $notices[] = 'You have been logged in';
            }
            break;
    }
}

//Generate the output if logged in or not logged in.
if(isset($_SESSION['username'])) {
    $output = '
    <p><a href="test2.php?action=logout">Logout</a></p>
    <h1>Welcome, ' . $_SESSION['username'] . '</h1>
    <p>You are logged in, enjoy!</p>';
}
else {
    $output = '
    <form action="test2.php" method="post">
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