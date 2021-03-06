<?php

include('database.php');

/**
 *  Options:
 *  - 'table' - The name of the table
 *  - 'item_name' - The name of the item.
 *  - 'display_columns' - A set of key / value pairs with 'column_name' => 'Column Title'
 *  - 'id_column' - The column that acts as the unique ID for the table
 *  - 'default_sort_column' - The column to sort by by default.
 *  - 'add_edit_columns' - A set of key / value pairs. The key is the column name, and the value is an
 *     array that can contain 'title', 'prefix' and 'validate' (an array)
 */
function data_list($options){

    //Set $pdo as global so that the database project can be used here.
    global $pdo;

    $table_rows = '';

    $sql_queryString = "SELECT * FROM " . $options['table'] . " ORDER BY " . $options['default_sort_column'] . " ASC";

    try {

        //Prepare the SQL statement to be executed.
        $sql = $pdo->prepare($sql_queryString);
        //Execute the SQL query.
        $sql->execute();

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

            if (!isset($table_header)) {
                $table_header = '';
                foreach ($options['display_columns'] as $col_title) {
                    $table_header .= '<th>' . $col_title . '</th>';
                }
                $table_header = '<tr>' . $table_header . '<th>Edit</th><th>Delete</th></tr>';
            }
            $table_row = '';
            foreach ($options['display_columns'] as $col_name => $col_title) {
                $table_row .= '<td>' . $row[$col_name] . '</td>';
            }
            $table_row .= '
      <td><a href="' . url($options['path']) . '?action=edit_form&id=' . $row[$options['id_column']] . '">Edit</a></td>
      <td><a href="' . url($options['path']) . '?action=delete&id=' . $row[$options['id_column']] . '">Delete</a></td>';
            $table_rows .= '<tr>' . $table_row . '</tr>';
        }

        $output = '
    <p><a href="' . url($options['path']) . '?action=add_form">Add ' . $options['item_name'] . '</a></p>
    <table>' . $table_header . $table_rows . '</table>';

        return $output;
    } catch (PDOException $e) {
        print $e->getMessage();
    }

}


//Display an 'add' or 'edit' form, depending on what is passed to the function.
//See $options comments from data_list();
function data_add_edit_form($id, $options){

    //Set $pdo as global so that the database project can be used here.
    global $pdo;

    $form_id = 'admin_add_edit_' . $options['table'];
    if (isset($_POST['form_id']) && $_POST['form_id'] == $form_id) {
        $values = $_POST;
        //If the edit form is being loaded for the first time.
    } elseif ($id != '') {
        $sql = "SELECT * FROM " . $options['table'] . " WHERE " . $options['id_column'] . " = '" . $id . "'";
        $values = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        //If this is an add form, set the values to empty.
    } else {
        //Add unique ID to empty values.
        $values[$options['id_column']] = '';
        foreach ($options['add_edit_columns'] as $column_name => $column_options) {
            $values[$column_name] = '';
        }
    }

    $submit_text = 'Add item';
    $action = 'add_item';
    $title = 'Add ' . $options['item_name'];

    if ($id != '') {
        $title = 'Edit ' . $options['item_name'];
        $submit_text = 'Save changes';
        $action = 'edit_item';
    }

    $form_inputs = '';
    foreach ($options['add_edit_columns'] as $column_name => $column_options) {
        //Set the default to a text field.
        if (!isset($column_options['type'])) {
            $column_options['type'] = 'text';
        }

        $input_id = 'input_' . $options['table'] . '_' . $column_name;

        //Render the input.
        switch ($column_options['type']) {
            case 'text':
                $input_rendered = '<input type="text" id="' . $input_id . '" name="' . $column_name . '" value="' . $values[$column_name] . '" />';
                break;

            case 'textarea':
                $input_rendered = '<textarea id="' . $input_id . '" name="' . $column_name . '">' . htmlentities($values[$column_name]) . '</textarea>';
                break;

            case 'checkbox':
                $input_rendered = '<input type="checkbox" id="' . $input_id . '" name="' . $column_name . '" value="1"' . ($values[$column_name] == 1 ? 'checked="checked"' : '') . ' />';
                break;

            case 'select':
                $input_rendered = '
                <select id="' . $input_id . '" name="' . $column_name . '">
                    <option value="Test1" ' . ($values[$column_name] == 'Test1' ? 'selected' : '') . '>Test1</option>
                    <option value="Test2" ' . ($values[$column_name] == 'Test2' ? 'selected' : '') . '>Test2</option>
                    <option value="Test3" ' . ($values[$column_name] == 'Test3' ? 'selected' : '') . '>Test3</option>
                    <option value="Test4" ' . ($values[$column_name] == 'Test4' ? 'selected' : '') . '>Test4</option>
                </select>
                ';
                break;
        }

        $form_inputs .= '<p>' . $column_options['title'] . ': ' . (isset($column_options['prefix']) ? $column_options['prefix'] : '') . $input_rendered . '</p>';
    }

    return '
    <h1>' . $title . '</h1>
    <form action="' . url($options['path']) . '" method="post">
      ' . $form_inputs . '
      <p><input type="submit" value="' . $submit_text . '" /></p>
      <input type="hidden" name="form_id" value="' . $form_id . '" />
      <input type="hidden" name="action" value="' . $action . '" />
      <input type="hidden" name="id" value="' . $id . '" />
    </form>';
}


//Perform validation on each input.
//See $options comments from data_list();
function data_add_edit_form_validate($options, $values)
{

    $errors = array();

    foreach ($options['add_edit_columns'] as $column_name => $column_options) {

        foreach ($column_options['validate'] as $type) {

            switch ($type) {

                //Required value.
                case 'required':
                    if (trim($values[$column_name]) == '') {
                        $errors[] = 'Please enter a value for ' . $column_name . '.';
                    }
                    break;

                //Is a number
                case 'numeric':
                    if (trim($values[$column_name]) != '') {
                        if (!is_numeric($values[$column_name])) {
                            $errors[] = 'Please enter only numbers for ' . $column_name . '.';
                        }
                    }
                    break;

                //Contains just letters and numbers
                case 'alphanumeric':
                    if (trim($values[$column_name]) != '') {
                        if (!ctype_alnum($values[$column_name])) {
                            $errors[] = 'Please enter only letters or numbers for ' . $column_name . '.';
                        }
                    }
                    break;

                //Contains only 1 and 0.
                case 'checkbox':
                    if (trim($values[$column_name]) != '') {
                        if (!is_numeric($values[$column_name])) {
                            if(($values[$column_name] != 0) || ($values[$column_name] != 1)) {
                                $errors[] = 'Please enter only numbers for ' . $column_name . '.';
                            }
                        }
                    }
                    break;

                //**NEEDS TO BE UPDATED****
                case 'select':
                    //print_r($values);
                    if (trim($values[$column_name]) != '') {
                        if (!ctype_alnum($values[$column_name])) {
                            $errors[] = 'Please enter only letters or numbers for ' . $column_name . '.';
                        }
                    }
                    break;

            }
        }
    }

    return $errors;
}


//Process the add / edit form submission.
//See $options comments from data_list();
function data_add_edit_form_process($values, $options)
{

    //Set $pdo as global so that the database project can be used here.
    global $pdo;

    //Since we're using 'id' for the unique id input, set the actual unique id column value.
    $values[$options['id_column']] = $values['id'];

    $errors = data_add_edit_form_validate($options, $values);

    /*
    print "-</br>";
    print_r($options);
    print "--</br>";
    */

    //If there's any errors, add a notice
    if (count($errors) > 0) {
        notice(array_to_list($errors));
        return data_add_edit_form($values['id'], $options);
    }
    //If no errors, go ahead and add the entry.
    else {
        $clean_columns[] = $options['id_column'];
        foreach ($options['add_edit_columns'] as $column_name => $column_options) {
            $clean_columns[] = $column_name;
        }
        foreach ($clean_columns as $column) {
            $clean_values[$column] = trim($values[$column]);
        }

        //Remove the unique ID column from values and columns so we can use implode() later.
        $update_values = $clean_values;
        unset($update_values[$options['id_column']]);
        $update_columns = $clean_columns;
        //We can do this because we know we added the id column first.
        unset($update_columns[0]);

        //Do an insert if we're adding.
        if ($clean_values[$options['id_column']] == '') {
            $add_query = "'" . implode("','", $update_values) . "'";
            $column_names = implode(',', $update_columns);
            $sql = "INSERT INTO " . $options['table'] . " (" . $column_names . ") VALUES (" . $add_query . ")";
        }
        //Do an update if we're editing.
        else {
            foreach ($update_columns as $column) {
                $update_query_array[] = " " . $column . " = '" . $clean_values[$column] . "' ";
            }
            $update_query = implode(',', $update_query_array);
            $sql = "
                UPDATE " . $options['table'] . "
                SET " . $update_query . "
                WHERE " . $options['id_column'] . " = '" . $clean_values[$options['id_column']] . "'";
        }

        try {
            //Prepare the SQL query to be executed.
            $sql_prepared = $pdo->prepare($sql);
            //Execute the generated SQL query.
            $result = $sql_prepared->execute();

            //$result will return TRUE if it worked. Otherwise, we should show an error to troubleshoot.
            if ($result) {
                notice(($clean_values[$options['id_column']] == '') ? 'The ' . $options['item_name'] . ' was added.' : 'The ' . $options['item_name'] . ' was updated');
            } else {
                //If something happened, let's show an error.
                notice(mysql_error());
            }
        } catch (PDOException $e) {
            print $e->getMessage();
        }

        notice($sql);


    }
}


//Delete an item.
function data_delete($options, $id)
{
    //Set $pdo as global so that the database project can be used here.
    global $pdo;
    //See $options comments from data_list();
    $pdo->query("DELETE FROM " . $options['table'] . " WHERE " . $options['id_column'] . " = '" . $id . "'")->execute();
    notice('The ' . $options['item_name'] . ' with ID ' . $id . ' was deleted.');
}


//Render an administrative page and handle CRUD logic.
//See $options comments from data_list();
function data_administration_page($options)
{

    //Used to create links in the other functions.
    $options['path'] = $_GET['path'];

    $content = '';

    if (isset($_REQUEST['action'])) {

        switch ($_REQUEST['action']) {

            case 'delete':
                data_delete($options, $_GET['id']);
                break;

            case 'edit_form':
            case 'add_form':
                $content .= data_add_edit_form((isset($_GET['id']) ? $_GET['id'] : ''), $options);
                break;

            case 'add_item':
            case 'edit_item':
                $content .= data_add_edit_form_process($_POST, $options);
                break;
        }
    }

    //If we didn't specify what we wanted to display, show the list of data.
    if ($content == '') {
        $content = data_list($options);
    }

    return $content;
}