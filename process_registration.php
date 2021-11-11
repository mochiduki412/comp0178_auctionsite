<?php
require_once('database_utilities.php');
require_once('interface_utilities.php');
// 1. Extract $_POST variables, check they're OK
    // print_r($_POST);

// 2. attempt to create an account
$conn = connect_db();

// attempt to insert user into database
$insert_query = "INSERT INTO user (firstName, lastName, email, password) " . 
                "VALUES ('$_POST[firstName]', 
                        '$_POST[lastName]', 
                        '$_POST[email]', 
                        '$_POST[password]')";  
// printnl($insert_query);
$insert_result = mysqli_query($conn, $insert_query)
    or die('Error inserting user into DB' . mysqli_error($conn));

// end connection
mysqli_close($conn);

// Set session variables
session_start();
$_SESSION['logged_in'] = true;
$_SESSION['username'] = "$_POST[firstName]";

// 3. Notify user of success/failure and redirect/give navigation options
echo "<script>
    alert('Successfully created account');
    window.location.href='index.php';
</script>";