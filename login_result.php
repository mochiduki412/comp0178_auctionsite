<?php 
    foreach (glob("includes/*.php") as $filename)
        require_once($filename);
?>

<?php
    // TODO: Extract $_POST variables, check they're OK, and attempt to login.
    // Notify user of success/failure and redirect/give navigation options.
    class UserNotFoundException extends Exception{};

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

    function get_id_by_email($email){
        $sql = "SELECT userId FROM `User` WHERE `email` = ?";
        $result = prepare_bind_excecute($sql, 's', $email);
        assert($result->num_rows <= 1, "User email is not unique, check your db design");
        if($result->num_rows == 0){
            throw new UserNotFoundException("User not found"); //Shall not trigger in login.
        }
        return $result->fetch_row()[0]; //user id
    }

    $email = $_POST["email"];
    $pass = $_POST["password"];
    if(!verify_user($email, $pass)){
        echo('<div class="text-center">Failed to login.</div>');
        header("refresh:5;url=index.php");
    } else{
        // query user's information.
        try{
            $sql = "SELECT `userId`, `type` FROM `User` WHERE `email` = ?";
            $result = prepare_bind_excecute($sql, 's', $email);
            $row = $result->fetch_assoc();
            $user_id = $row['userId'];
            $type = $row['type'];
        } catch(Exception $e) {
            var_dump($e->getMessage());
        }

        // set sessions accordingly.
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['user'] = $user_id;
        $_SESSION['account_type'] = $type;

        redirect($_SERVER['HTTP_REFERER'] or 'index.php', 'You are now logged in! You will be redirected shortly', 3);
    }
?>
