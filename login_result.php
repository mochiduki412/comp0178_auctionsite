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