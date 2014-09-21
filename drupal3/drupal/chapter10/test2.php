<?php

//Include the database.php to retrieve the database connectivity.
include('database.php');

// We're setting up a function to pull together notices to the user and display them.
function notice($text, $action = 'add') {
    static $notices;
    if ($action == 'add') {
        $notices[] = $text;
    } elseif ($action == 'get') {
        if (count($notices) > 0) {
            $output = '<strong><ul><li>' . implode('</li><li>', $notices) . '</li></ul></strong>';
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

    //Include the database.php to retrieve the database connectivity inside the function.
    include('database.php');
    $output = '';
    $sql_queryString = "SELECT * FROM people ORDER BY name ASC";
    //Prepare the SQL statement to be executed.
    $sql = $pdo->prepare($sql_queryString);
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
            <td><a href="test2.php?action=delete&username=' . $row['username'] . '">Delete</a></td>
      </tr>';
    }
    if($output != '') {
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
    }
    else {
        $output = '<p>There are no people to display</p>';
    }

    return '<p><a href="test2.php?action=add_form">Add person</a></p>' . $output;
}

// Creates a form to add entries into our 'people' table.
function add_form() {
    return '
    <form action="test2.php?action=add" method="post">
      <p>First name: <input type="text" name="first" /></p>
      <p>Birth year: <input type="text" name="birth_year" /></p>
      <p>Favorite band: <input type="text" name="fav_band" /></p>
      <p>Shoe size: <input type="text" name="shoe_size" /></p>
      <p>Username: <input type="text" name="username" /></p>
      <p>Password: <input type="text" name="password" /></p>
      <p><input type="submit" value="Add entry" /></p>
    </form>';
}

$output = '';

//Process the form. There's only one 'case' right now, but we know that there will be more later.
if(isset($_GET['action'])) {
    switch ($_GET['action']) {

        case 'delete':
            $sql_deleteQuery = "DELETE FROM people where username = '" . $_GET['username'] . "'";
            //Prepare the SQL statement to be executed.
            $sql_delete = $pdo->prepare($sql_deleteQuery);
            //Execute the SQL query.
            $sql_delete->execute();
            notice('The user ' . $_GET['username'] . ' was deleted');
            break;

        case 'add_form':
            $output .= add_form();
            break;

        case 'add':
            //Create a loop to store the values.
            $inputs = array('first', 'birth_year', 'fav_band', 'shoe_size', 'username', 'password');
            foreach($inputs as $key => $input_name) {
                $inputs[$key] = $_POST[$input_name];
            }

            //Let's pull all the values together.
            $values = "'" . implode("','", $inputs) . "'";
            $sql_insertQuery = "INSERT INTO people (name, birth_year, favorite_band, shoe_size, username, password) VALUES (" . $values . ")";
            //Prepare the SQL statement to be executed.
            $sql_insert = $pdo->prepare($sql_insertQuery);
            //Execute the SQL query.
            try {
                $result_insert = $sql_insert->execute();
            } catch (PDOException $e) {
                notice($e->getMessage());
            }

            //Store the SQL statement inside the notices array.
            notice($sql_insertQuery);

            if ($result_insert) {
                notice('The person was added successfully.');
            }
    }
}

// If we didn't specify what we wanted to display, show the list of people.
if ($output == '') {
    $output = people_display();
}

print get_notices() . $output;

?>