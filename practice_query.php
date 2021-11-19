<?php require_once('database_utilities.php') ?>
<?php

// Establish connection
$conn = connect_db();

// insert user into database
$insert_query = "INSERT INTO user (firstName, lastName)" .
    "VALUES ('Matt','Damon')";
$insert_result = mysqli_query($conn, $insert_query)
    or die('Error making query' . mysqli_error($conn));
printnl($insert_result);

// retrieve & display user
$select_query = "SELECT firstName, lastName FROM user";
$select_result = mysqli_query($conn, $select_query)
    or die('Error making query' . mysqli_error($conn));
printnl($select_result);

// fetch rows and display in table
echo '<table border="1">';
while (
    $row = mysqli_fetch_array($select_result)
) {
    echo '<tr><td>' . $row['first_name'] . '</td><td>' . $row['family_name'] . '</td></tr>';
}
echo '</table>';





/*
    ----------------------------- Michael's Notes:
    Beware of browser caching - no live changes, must force reload or clear cache manually
    Create a php file for utility functions
    Example - open connection, execute query, close connection
    Just import - include 'database.php'
    include powers through errors, may expose private info in worst case
    without error recovery, use require - stop on errors
    require _once = don't import stuff multiple times
    default arguments supported 
    */
?>