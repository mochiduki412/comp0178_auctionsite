<?php
    // TODO: Extract $_POST variables, check they're OK, and attempt to login.
    // Notify user of success/failure and redirect/give navigation options.
    require_once("db_utils.php");

    function verify_user($email, $pass){
        $sql = sprintf("SELECT `email`, `password` FROM `User` WHERE `email`=?");
        $conn = get_conn();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        if ($result->num_rows == 0) {
            return false;
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($rows as $row) {
            if($row["password"] == $pass) return true;
        }
        return false;
    }

    $email = $_POST["email"];
    $pass = $_POST["password"];
    if(!verify_user($email, $pass)){
        echo('<div class="text-center">Login attempt failed.</div>');
        header("refresh:5;url=index.php");
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