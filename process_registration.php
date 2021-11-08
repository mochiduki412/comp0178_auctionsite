
<?php
    // TODO: Extract $_POST variables, check they're OK, and attempt to create
    // an account. Notify user of success/failure and redirect/give navigation 
    // options.
    require_once("db_utils.php");
    

    function validate_email_input($email)
    {
        static $REGEX = "/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]+$/";
        if (preg_match($REGEX, $email)) {
            return true;
        }
        return false;
    }

    function exists_email($email){
        static $sql = "SELECT email FROM User WHERE email=?";
        $result = prepare_bind_excecute($sql, "s", $email);
        if ($result->num_rows == 0) {
            return false;
        }
        return true;
    }

    /**
    * Might change after more reading on PHP
    * @return 23 hexidecimal digits string based on sys time.
    */
    function get_uuid(){
        return uniqid('', true);
    }

    /**
    * @return fixed 60 bits string.
    */
    function hash_pass($pass){
        //Not to provide salt, use the default generated salt instad.
        //src: https://www.php.net/manual/en/function.password-hash.php
        return password_hash($pass, PASSWORD_BCRYPT);
    }

    if (!isset($_POST['accountType']) or !isset($_POST['email']) or 
        !isset($_POST['password']) or !isset($_POST['passwordConfirmation'])) {
        die('All fields are required.');
    }

    $type = $_POST['accountType'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $pass_con = $_POST['passwordConfirmation'];

    if (!validate_email_input($email)) die('incorrect email input.');
    if ($pass != $pass_con) die('passwords must be the same.');
    if (exists_email($email)) die('email already exists');

    //validated, start to insert
    //generate UUID and hash the password. Change the table's structure accordingly!
    $uuid = get_uuid();
    $pass_hashed = hash_pass($pass);
    $sql = 'INSERT INTO `User` (`userId`, `email`, `password`) VALUES (?, ?, ?);';
    prepare_bind_excecute($sql, "sss", $uuid, $email, $pass_hashed);
    
    echo('<div class="text-center">Account created</div>');
    header("refresh:5;url=index.php")
?>