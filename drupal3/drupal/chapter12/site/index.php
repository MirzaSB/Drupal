<?php

// Start the session.
session_start();

// Moved functions to their own file so we can use them in page files.
include('includes/functions.php');

// Connect to the database.
db_connect();

// If this is index.php, we won't get a path, so we need to set it.
$path = isset($_GET['path']) ? $_GET['path'] : 'home.php';

// Render featured products.
$featured_product_output = render_products(get_setting('featured_product_ids'));

// Produce some variables to use in the template.
$company_name = get_setting('company_name');
$year = date('Y');

// Process form submissions.
if (isset($_REQUEST['action'])) {

    switch ($_REQUEST['action']) {

        case 'logout':
            session_destroy();
            session_start();
            notice('You have been logged out');
            break;

        case 'login':
            //Create the SQL query to get data using username and password values.
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

// Show log in / log out links.
$login_logout = '<a href="login.php">Log in</a>';
if (isset($_SESSION['user'])) {
  $login_logout = '<a href="' . url('login.php') . '">My account</a> | <a href="' . url('login.php') . '?action=logout">Log out</a>';
}

// Include the file that matches the path name.
include('pages/' . $path);

$notices = get_notices();

// Get admin.css if it's an admin page.
$additional_css_files = '';
if (isset($_GET['path'])) {
    $arr = explode('/', $_GET['path']);
    $getAdmin = array_shift($arr);
  //if (array_shift(explode('/', $_GET['path'])) == 'admin') {
  if ($getAdmin == 'admin') {
    $additional_css_files .= '<link type="text/css" rel="stylesheet" media="all" href="' . url('styles/admin.css') . '" />';
  }
}

include('includes/page-template.php');