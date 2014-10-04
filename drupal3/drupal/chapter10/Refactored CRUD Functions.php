<?php

include('database.php');

$username = "testcustom2";

//Run the function
//echo selectQueryDisplayAllRows();
//echo verifyUsernameExists($username);
//echo updateUserData($username, "pa55word", "George", "1990", "The Beatles", "9");
echo deleteUser($username);
//print_r (getUsernameRowData($username));
//echo insertRowInPeopleTable("tfirstname2", "1990", "band", "10", "testcustom2", "pa55word");
//echo insertRowInPeopleTable("George", "1990", "band", "10", "homestar", "pa55word");
//echo insertRowInPeopleTable("Sally", "1980", "band", "7", "uniquestar", "pa55word");
//echo insertRowInPeopleTable("admin", "1980", "band", "10", "admin", "pa55word");

//Function to insert a row in the "people" table.
function insertRowInPeopleTable($first_name, $birth_year, $favorite_band, $shoe_size, $username, $password) {

    //Set $pdo as global so that the database object can be used here.
    global $pdo;

    //Check to see if the username exists in the database table.
    if(verifyUsernameExists($username)) {
        throw new Exception("Username, '" . $username . "' already exists in the 'people' table.");
    }

    //INSERT QUERY.
    $sql_insertQuery = "INSERT INTO people (name, birth_year, favorite_band, shoe_size, username, password)
                        VALUES (:first_name, :birth_year, :favorite_band, :shoe_size, :username, :password)";

    //Prepare the SQL statement to be executed.
    $sql_insert = $pdo->prepare($sql_insertQuery);

    try {

        //Execute the SQL statement, and store the row count.
        $insert_rows_count = $sql_insert->execute(array(':first_name' => $first_name, ':birth_year' => $birth_year,
            ':favorite_band' => $favorite_band, ':shoe_size' => $shoe_size, ':username' => $username, ':password' => $password));

        //If the row count is more than 0, return true otherwise return false.
        return $insert_rows_count > 0 ? true : false;

    }
    catch (PDOException $e) {

        //Print the exception message.
        print $e->getMessage();

    }
}

//Function to delete a user in the "people" table.
function deleteUser($username) {

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
        return $sql_delete->execute(array($username));

    }
    catch (PDOException $e) {
        //Print the exception.
        print $e->getMessage();
    }

}

//Function to update a row in the "people" table using the "username" value.
function updateUserData($username, $password, $first_name, $birth_year, $fav_band, $shoe_size) {

    //Set $pdo as global so that the database object can be used here.
    global $pdo;

    //Check to see if the username exists in the database table.
    if(!verifyUsernameExists($username)) {
        throw new Exception("Username, '" . $username . "' does not exist in the database table.");
    }

    //UPDATE QUERY.
    $sql_updateQuery = "UPDATE people
                        SET name = ?, birth_year = ?, favorite_band = ?, shoe_size = ?, password = ?
                        WHERE username = ?";

    //return $pdo->query($sql_updateQuery)->execute();

    //Prepare the SQL statement to be executed.
    $sql_update = $pdo->prepare($sql_updateQuery);

    try {

        //Execute the SQL statement, and store the row count.
        $updated_rows_count = $sql_update->execute(array($first_name, $birth_year, $fav_band, $shoe_size, $password, $username));

        //If the row count is more than 0, return true otherwise return false.
        return $updated_rows_count > 0 ? true : false;

    }
    catch (PDOException $e) {

        //Print the exception message.
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
            //return "Sorry, the username, '" . $username . "' is already in use. Please choose another username.";
            return true;
        }
        else {
            //If the row count is 0, then that user does not exist in the database.
            //return "The username, '" . $username . "' is available.";
            return false;
        }
    }
    catch (PDOException $e) {
        print $e->getMessage();
    }
}

//Function to display all rows in the "people" table.
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
            </tr>';
        }

        //Return the final value.
        return $output;
    }
    catch (PDOException $e) {
        print $e->getMessage();
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
    //return $pdo->query($sql_getRowFromUsernameQuery)->fetch(PDO::FETCH_ASSOC);

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

?>