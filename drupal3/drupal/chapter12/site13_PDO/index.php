<?php

//Start the session.
session_start();

//Moved functions to their own file so we can use them in page files.
include('includes/functions.php');

//Connect to the database.
db_connect();

//If this is index.php, we won't get a path, so we need to set it.
$path = isset($_GET['path']) ? $_GET['path'] : 'home.php';


//Render featured products.
//$featured_product_output = render_products(get_setting('featured_product_ids'));
$featured_product_output = render_products(getFeaturedSticksSettingsFromDB());

//Produce some variables to use in the template.
$company_name = get_setting('company_name');
$year = date('Y');

//Process form submissions.
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

//Show log in / log out links.
$login_logout = '<a href="login.php">Log in</a>';
if (isset($_SESSION['user'])) {
  $login_logout = '<a href="' . url('login.php') . '">My account</a> | <a href="' . url('login.php') . '?action=logout">Log out</a>';
}

//Include the file that matches the path name.
$page_path = 'pages/' . $path;
if (file_exists($page_path)) {
  include($page_path);
}
else {
    //Create the SQL query string to get all information from the pages table.
    $sql_queryString = "SELECT * FROM pages WHERE path = '" . $path . "'";
    //Prepare the SQL statement to be executed.
    $sql_pages = $pdo->prepare($sql_queryString);
    try{
        //Execute the SQL query.
        $sql_pages->execute();
        if ($row = $sql_pages->fetch(PDO::FETCH_ASSOC)) {
            $title = $row['title'];
            //To get the content into a variable that allows PHP, we have to do an eval().
            ob_start();
            eval('?> ' . $row['content'] . ' <?php ');
            $content = ob_get_contents();
            ob_end_clean();
        }
    }
    catch (PDOException $e) {
        print $e->getMessage();
    }

}

$notices = get_notices();

//Get admin.css if it's an admin page.
$additional_css_files = '';

//Get the current path, and then split it delimited by "/" to look for the "admin" text.
if (isset($_GET['path'])) {
    $arr_Path = explode('/', $_GET['path']);
  if (array_shift($arr_Path) == 'admin') {
      //If "admin" is found, get the admin.css.
    $additional_css_files .= '<link type="text/css" rel="stylesheet" media="all" href="' . url('styles/admin.css') . '" />';
  }
}

include('includes/page-template.php');