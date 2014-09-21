<?php

include ('database.php');

//Initialize an output variable.
$output = '';

//Setup a conditional that goes performs an action.
if (isset($_REQUEST['action'])) {

    //Switch statement that executes appropriate actions.
    switch ($_REQUEST['action']) {

        case 'delete':
            delete_entry($_GET['username']);
            break;

        case 'edit_form':
        case 'add_form':
            $output .= add_form((isset($_GET['username']) ? $_GET['username'] : ''));
            break;


        case 'edit':
        case 'add':
            $output .= add_edit_entry($_POST, $_REQUEST['action']);
            break;
    }
}

//If we didn't specify what we wanted to display, show the list of people.
if ($output == '') {
    $output = people_display();
}

print get_notices() . $output;

//We are setting up a function to pull together notices to the user and display them.
function notice($text, $action = 'add') {

    static $notices;

    if($action == 'add') {
        $notices[] = $text;
    }
    elseif ($action == 'get') {
        if (count($notices) > 0) {
            $output = '<strong>' . array_to_list($notices) . '</strong>';
            unset($notices);
            return $output;
        }
    }
}

//A shortcut function that's more intuitive to use for getting the notices.
function get_notices() {
    return notice('', 'get');
}

//Display people in the database as a table with a delete link.
function people_display() {

    //Return all the values from the "people" table using the selectQueryDisplayAllRows function.
    $output = selectQueryDisplayAllRows();

    //If the output is empty...
    if ($output != '') {
        $output = '
            <table>
                <tr>
                  <th>Name</th>
                  <th>Username</th>
                  <th>Birth year</th>
                  <th>Shoe size</th>
                  <th>Favorite band</th>
                </tr>
                ' . $output . '
            </table>';
    } else {
        $output = '<p>There are no people to display.</p>';
    }

    return '<p><a href="test5.php?action=add_form">Add person</a></p>' . $output;
}

//Create a form to add entries into our 'people' table.
function add_form($username = NULL) {

    //Populate $row so we can reference it in the form without error.
    $row = array('name' => '', 'birth_year' => '', 'favorite_band' => '', 'shoe_size' => '', 'username' => '', 'password' => '');

    $action = 'add';
    $username_input = '<p>Username: <input type="text" name="username" value="' . (isset($username) ? $username : $row['username']) . '" /></p>';
    $edit_text = '';
    $submit_text = 'Add entry';

    if ($username) {
        //Get all the values of that particular row using the "username" value.
        $row = getUsernameRowData($username);
        $edit_text = '<p><strong>You are currently editing ' . $username . '.</strong></p>';
        $action = 'edit';
        $username_input = '<input type="hidden" name="username" value="' . $username . '" />';
        $submit_text = 'Save changes';
    }

    return '
    ' . $edit_text . '
    <form action="test5.php" method="post">
      <p>First name: <input type="text" name="first" value="' . (isset($values['first']) ? $values['first'] : $row['name']) . '" /></p>
      <p>Birth year: <input type="text" name="birth_year" value="' . (isset($values['birth_year']) ? $values['birth_year'] : $row['birth_year']) . '" /></p>
      <p>Favorite band: <input type="text" name="fav_band" value="' . (isset($values['fav_band']) ? $values['fav_band'] : $row['favorite_band']) . '" /></p>
      <p>Shoe size: <input type="text" name="shoe_size" value="' . (isset($values['shoe_size']) ? $values['shoe_size'] : $row['shoe_size']) . '" /></p>
      '. $username_input . '
      <p>Password: <input type="text" name="password" value="' . (isset($values['password']) ? $values['password'] : $row['password']) . '" /></p>
      <p><input type="submit" value="' . $submit_text . '" /></p>
      <input type="hidden" name="action" value="' . $action . '" />
    </form>';
}

//Run through validation functions
function validate_submission($values, $action) {

    $errors = array();

    //Required validation.
    $required = array('first', 'username', 'password');
    foreach($required as $input_name) {
        if (trim($values[$input_name]) == '') {
            $errors[] = 'Please enter a value for ' . $input_name . '.';
        }
    }

    //Numeric validation.
    $numbers = array('birth_year', 'shoe_size');
    foreach ($numbers as $input_name) {
        if (trim($values[$input_name]) != '') {
            if (!is_numeric($values[$input_name])) {
                $errors[] = 'Please enter a number for '. $input_name . '.';
            }
        }
    }

    //Alpha-numeric validation.
    $alphanumeric = array('username', 'password');
    foreach ($alphanumeric as $input_name) {
        if (trim($values[$input_name]) != '') {
            if (!ctype_alnum($values[$input_name])) {
                $errors[] = 'Please enter only numbers or letters for '. $input_name . '.';
            }
        }
    }

    //Check uniqueness of username.
    if ($action == 'add') {
        if (verifyUsernameExists($values['username'])) {
            $errors[] = 'Sorry, it looks like that username is already in use.';
        }
    }

    return $errors;
}

//Convert an array to an HTML list.
function array_to_list($array) {
    return '<ul><li>' . implode('</li><li>', $array) . '</li></ul>';
}

//Process the add and edit forms.
function add_edit_entry($values, $action) {

    //Set $pdo as global so that the database object can be used here.
    global $pdo;

    $errors = validate_submission($values, $action);

    //If there's any errors, add a notice
    if (count($errors) > 0) {
        notice(array_to_list($errors));
        return add_form();
    }
    //If no errors, go ahead and add the person.
    else {

        //Changed name of variable here.
        $input_names = array('first', 'birth_year', 'fav_band', 'shoe_size', 'username', 'password');
        foreach ($input_names as $input_name) {
            $inputs[$input_name] = trim($values[$input_name]);
        }

        //Do an insert if we're adding.
        if ($action == 'add') {
            //Concatenate all inputs.
            $values = "'" . implode("','", $inputs) . "'";
            //Query to insert a row in the table.
            $sql = "INSERT INTO people (name, birth_year, favorite_band, shoe_size, username, password) VALUES (" . $values . ")";
        }
       //Do an update if we're editing.
        else {
            $sql = "
                UPDATE people
                SET name = '" . $inputs['first'] . "',
                  birth_year = '" . $inputs['birth_year'] . "',
                  favorite_band = '" . $inputs['fav_band'] . "',
                  shoe_size = '" . $inputs['shoe_size'] . "',
                  password = '" . $inputs['password'] . "'
                WHERE username = '" . $inputs['username'] . "'";
        }

        //Prepare the SQL statement to be executed.
        $sql_InsertOrUpdate = $pdo->prepare($sql);
        $result = $sql_InsertOrUpdate->execute();
        //Store the SQL Query inside the 'notice' array.
        notice($sql);

        //$result will return TRUE if it worked. Otherwise, we should show an error to troubleshoot.
        if ($result) {
            notice(($action == 'add') ? 'The person was added.' : 'The person was updated');
        }
    }
}


//Function to return an array containing all the column values of a row using the "username" value from the "people" table.
function getUsernameRowData($username) {

    //Set $pdo as global so that the database object can be used here.
    global $pdo;

    //Check to see if the username exists in the database table.
    if(!verifyUsernameExists($username)) {
        throw new Exception("Username, '" . $username . "' does not exist in the database table.");
    }

    //SELECT QUERY.
    $sql_getRowFromUsernameQuery = "SELECT * FROM people WHERE username = '" . $username . "'";
    //Prepare the SQL statement to be executed.
    $sql_getRowFromUsername = $pdo->prepare($sql_getRowFromUsernameQuery);
    try {
        //Execute the SQL query.
        $sql_getRowFromUsername->execute();
        return $sql_getRowFromUsername->fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        print $e->getMessage();
    }
}

function selectQueryDisplayAllRows() {

    //Set $pdo as global so that the database object can be used here.
    global $pdo;

    //Initialize an output variable to store the final data.
    $output = '';

    //SELECT QUERY.
    $sql_queryString = "SELECT * FROM people ORDER BY name ASC";
    //Prepare the SQL statement to be executed.
    $sql = $pdo->prepare($sql_queryString);
    try {
        //Execute the SQL query.
        $sql->execute();
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $output .= '
            <tr>
                <td>' . $row['name'] . '</td>
                <td>' . $row['username'] . '</td>
                <td>' . $row['birth_year'] . '</td>
                <td>' . $row['shoe_size'] . '</td>
                <td>' . $row['favorite_band'] . '</td>
                <td><a href="test5.php?action=delete&username=' . $row['username'] . '">Delete</a></td>
                <td><a href="test5.php?action=edit_form&username=' . $row['username'] . '">Edit</a></td>
            </tr>';
        }

        //Return the final value.
        return $output;
    }
    catch (PDOException $e) {
        print $e->getMessage();
    }
}

//Check to see if a row exists in the "people" table based on the "username" value.
function verifyUsernameExists($username) {

    //Set $pdo as global so that the database object can be used here.
    global $pdo;

    //SELECT QUERY.
    $sql_findUsernameQuery = "SELECT name FROM people WHERE username = '" . $username . "'";
    //Prepare the SQL statement to be executed.
    $sql_findUsername = $pdo->prepare($sql_findUsernameQuery);
    try {
        //Execute the SQL query.
        $sql_findUsername->execute();
        //Get the row count of the data returned.
        if($sql_findUsername->rowCount() > 0) {
            //If the row count is more than 0, that means that the user exists in the database.
            return true;
        }
        else {
            //If the row count is 0, then that user does not exist in the database.
            return false;
        }
    }
    catch (PDOException $e) {
        print $e->getMessage();
    }
}

//Function to delete a user in the "people" table.
function delete_entry($username) {

    //Set $pdo as global so that the database object can be used here.
    global $pdo;

    //Check to see if the username exists in the database table.
    if(!verifyUsernameExists($username)) {
        throw new Exception("Username, '" . $username . "' does not exist in the database table.");
    }

    //DELETE QUERY.
    $sql_deleteQuery = "DELETE FROM people where username = ?";

    //Prepare the SQL statement to be executed.
    $sql_delete = $pdo->prepare($sql_deleteQuery);

    try {
        //Execute the SQL statement.
        if ($sql_delete->execute(array($username))) {
            //If successful, put a message in the 'notice' array.
            notice('The user ' . $username . ' was deleted.');
        }

    }
    catch (PDOException $e) {
        //Print the exception.
        print $e->getMessage();
    }
}

?>