<?php
    // TODO: Extract $_POST variables, check they're OK, and attempt to login.
    // Notify user of success/failure and redirect/give navigation options.
    require_once("db_utils.php");

    function verify_user($email, $pass){
        static $sql = "SELECT `email`, `password` FROM `User` WHERE `email`=?";
        $result = prepare_bind_excecute($sql, "s", $email);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($rows as $row) { //shall has 0 or 1 row. Not sure where to put assertion yet.
            $pass_hashed = $row["password"];
            if(password_verify($pass, $pass_hashed)) return true;
        }
        return false;
    }

    $email = $_POST["email"];
    $pass = $_POST["password"];
    if(!verify_user($email, $pass)){
        echo('<div class="text-center">Failed to login.</div>');
        header("refresh:5;url=index.php");
    } else{
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $email; 
        $_SESSION['account_type'] = "buyer"; //Our DB ignores type col for now. Need to discuss if we want to add later.

        echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');
        header("refresh:5;url=index.php");
    }
?>