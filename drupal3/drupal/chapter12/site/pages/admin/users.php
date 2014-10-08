<?php

$title = 'Administer users';

//Include the database.php file so that the "pdo" object can be accessed.
include('./includes/database.php');

//Display people in the database as a table with a delete link.
function admin_users_list() {

    //Set $pdo as global so that the database object can be used here.
    global $pdo;

    $output = '';

    //SELECT query to get all users.
    $sql_queryString = "SELECT * FROM users ORDER BY username ASC";
    //Prepare the SQL statement to be executed.
    $sql = $pdo->prepare($sql_queryString);
    try {

        //Execute the SQL query.
        $sql->execute();
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $output .= '
                      <tr>
                        <td>' . $row['uid'] . '</td>
                        <td>' . $row['username'] . '</td>
                        <td><a href="' . url('admin/users.php') . '?action=delete&uid=' . $row['uid'] . '">Delete</a></td>
                        <td><a href="' . url('admin/users.php') . '?action=edit_form&uid=' . $row['uid'] . '">Edit</a></td>
                      </tr>';
        }

        if ($output != '') {
            $output = '
                  <table>
                    <tr>
                      <th>UID</th>
                      <th>Username</th>
                      <th>Delete</th>
                      <th>Edit</th>
                    </tr>
                    ' . $output . '
                  </table>';
        }
        else {
            $output = '<p>There are no users.</p>';
        }

        return '<p><a href="' . url('admin/users.php') . '?action=add_form">Add user</a></p>' . $output;
    }
    catch (PDOException $e) {
        print $e->getMessage();
    }
}

// Creates a form to add entries into our 'people' table.
function admin_users_add_edit_form($uid = '') {

    //Set $pdo as global so that the database object can be used here.
    global $pdo;
  
    //Populate $row so we can reference it in the form without error.
    $row = array('uid' => '', 'username' => '', 'password' => '');

    //If the form has been submitted.
    if (isset($_POST['form_id']) && $_POST['form_id'] == 'admin_users_add_edit') {
        $values = $_POST;
    }
    //If the edit form is being loaded for the first time.
    elseif ($uid != '') {
        $sql_queryString = "SELECT * FROM users WHERE uid = '" . $uid . "'";
        $values = $pdo->query($sql_queryString)->fetch(PDO::FETCH_ASSOC);
    }
    //If this is an add form, set the values to empty.
    else {
        $values = array('uid' => '', 'username' => '', 'password' => '');
    }

    $edit_text = '';
    $submit_text = 'Add entry';
    $action = 'add_user';
    $title = 'Add user';

    if ($values['uid'] != '') {
        $title = 'Edit user ' . $values['username'];
        $submit_text = 'Save changes';
        $action = 'edit_user';
    }

    return '
        <h1>' . $title . '</h1>
        <form action="' . url('admin/users.php') . '" method="post">
          <p>Username: <input type="text" name="username" value="' . $values['username'] . '" /></p>
          <p>Password: <input type="text" name="password" value="' . $values['password'] . '" /></p>
          <p><input type="submit" value="' . $submit_text . '" /></p>
          <input type="hidden" name="form_id" value="admin_users_add_edit" />
          <input type="hidden" name="action" value="' . $action . '" />
          <input type="hidden" name="uid" value="' . $values['uid'] . '" />
        </form>';
}

//Run through validation functions
function admin_users_add_edit_form_validate($values) {

    //Set $pdo as global so that the database object can be used here.
    global $pdo;

    $errors = array();

    //Required validation.
    $required = array('username', 'password');
    foreach($required as $input_name) {
        if (trim($values[$input_name]) == '') {
          $errors[] = 'Please enter a value for ' . $input_name . '.';
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
    if ($values['uid'] != '' || $values['username'] != '') {
        //If it's their own username, it's okay.
        //$sql_queryString = "SELECT uid FROM users WHERE username = '" . $values['username'] . "' AND uid != '" . $values['uid'] . "'";
        $sql_queryString = "SELECT uid FROM users WHERE username = '" . $values['username'] . "'";
        $rowCount = $pdo->query($sql_queryString)->rowCount();
        if ($rowCount > 0) {
          $errors[] = 'Sorry, it looks like that username is already in use.';
        }
    }

    return $errors;
}


// Process the add and edit forms.
function admin_users_add_edit_form_process($values, $action) {

    //Set $pdo as global so that the database object can be used here.
    global $pdo;

    $errors = admin_users_add_edit_form_validate($values, $action);

    //If there's any errors, add a notice
    if (count($errors) > 0) {
        notice(array_to_list($errors));
        return admin_users_add_edit_form($values['uid']);

    }
    //If no errors, go ahead and add the person.
    else {
        // Changed name of variable here.
        $input_names = array('uid', 'username', 'password');
        foreach ($input_names as $input_name) {
          $clean_values[$input_name] = trim($values[$input_name]);
        }
        //Do an insert if we're adding.
        if ($clean_values['uid'] == '') {
            $add_values = $clean_values;
            unset($add_values['uid']);
            $add_query = "'" . implode("','", $add_values) . "'";
            $sql = "INSERT INTO users (username, password) VALUES (" . $add_query . ")";

        }
        else {
            $sql = "
            UPDATE users
            SET username = '" . $clean_values['username'] . "',
            password = '" . $clean_values['password'] . "'
            WHERE uid = '" . $clean_values['uid'] . "'";
        }

        try {
            $result = $pdo->query($sql)->rowCount();
            notice($sql);
            //$result will return TRUE if it worked. Otherwise, we should show an error to troubleshoot.
            if ($result) {
                notice(($clean_values['uid'] == '') ? 'The user was added.' : 'The user was updated');
            }
        }
        catch (PDOException $e) {
            //If something happened, let's show an error.
            notice($e->getMessage());
        }

    }
}

// Delete a user.
function admin_users_delete_user($uid) {
    //Set $pdo as global so that the database object can be used here.
    global $pdo;
    $pdo->query("DELETE FROM users WHERE uid = '" . $uid . "'")->execute();
    notice('The user with UID ' . $uid . ' was deleted.');
}

$content = '';

if (isset($_REQUEST['action'])) {
  
  switch ($_REQUEST['action']) {
    
    case 'delete':
      admin_users_delete_user($_GET['uid']);
      break;
    
    case 'edit_form':
    case 'add_form':
      $content .= admin_users_add_edit_form((isset($_GET['uid']) ? $_GET['uid'] : ''));
      break;
    
    case 'edit_user':
    case 'add_user':
      $content .= admin_users_add_edit_form_process($_POST, $_REQUEST['action']);
      break;
  }
    
}

// If we didn't specify what we wanted to display, show the list of users.
if ($content == '') {
  $content = admin_users_list();
}