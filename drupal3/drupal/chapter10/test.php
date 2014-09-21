<?php

//Include the database.php to retrieve the database connectivity.
include('database.php');

//We're setting up a function to pull together notices to the user and display them.
function notice($text, $action = 'add') {
    static $notices;
    if($action == 'add') {
        $notices[] = $text;
    }
    elseif ($action == 'get') {
        if(count($notices) > 0) {
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
            <td><a href="test.php?action=delete&username=' . $row['username'] . '">Delete</a></td>
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

    return $output;
}

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
    }
}

print get_notices() . people_display();

?>