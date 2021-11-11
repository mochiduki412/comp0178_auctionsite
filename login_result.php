<?php
require_once('database_utilities.php');
// Extract $_POST variables, check they're OK, and attempt to login.
// print_r($_POST);
$conn = connect_db();

$get_user = "SELECT * FROM user WHERE email = '$_POST[email]' AND password = '$_POST[password]'";
// printnl($get_user);
$get_user_result = mysqli_query($conn, $get_user)
    or die('Error logging in ' . mysqli_error($conn));


mysqli_close($conn);
$user = mysqli_fetch_array($get_user_result);
printnl($user['firstName']);

// Set session variables and redirect
session_start();
$_SESSION['logged_in'] = true;
$_SESSION['username'] = $user['firstName'];
    
echo "<script>
    alert('Successfully logged in');
    window.location.href='index.php';
</script>";
