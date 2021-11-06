<?php
    // TODO: Extract $_POST variables, check they're OK, and attempt to login.
    // Notify user of success/failure and redirect/give navigation options.
    require_once("db_utils.php");


    function verify_user($email, $pass){
        $sql = sprintf("SELECT * FROM `User` WHERE `email`='%s'", $email);
        $conn = get_conn();
        $result = $conn->query($sql);
        $conn->close();
        printnl($result);
    }

    $email = $_POST["email"];
    $pass = $_POST["password"];
    if(!verify_user($email, $pass)){
        echo('<div class="text-center">Login attempt failed.</div>');
        header("refresh:3;url=index.php");
    } else{
        // For now, I will just set session variables and redirect.
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = "test";
        $_SESSION['account_type'] = "buyer";

        echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');
        header("refresh:5;url=index.php"); // Redirect to index after 5 seconds
    }

    
?>