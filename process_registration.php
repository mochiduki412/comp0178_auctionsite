<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.

    function  validate_email_input($email)
    {
        $REGEX = "^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]+$";
        if(preg_match($REGEX, $email)){
            return true;
        }
        return false;
    }

    function exists_email($email){
        $sql = "SELECT email FROM User WHERE email=?";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            return false;
        };
        return true;
    }

    if (!isset($_POST['accountType']) or !isset($_POST['email']) or !isset($_POST['password']) or !isset($_POST['passwordConfirmation'])){
        die('All fields are required.');
    }

    $type = $_POST['accountType'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_con = $_POST['passwordConfirmation'];

    if(!validate_email_input($email)) die('incorrect email');
    if(exists_email($email)) die('email already exists');
    if($pass !== $passCon) die('passwords must be the same.');


?>