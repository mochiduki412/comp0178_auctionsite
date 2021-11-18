
<?php
    // TODO: Extract $_POST variables, check they're OK, and attempt to create
    // an account. Notify user of success/failure and redirect/give navigation 
    // options.
    require_once("db_utils.php");
    
    function is_all_fields_set(){
        if(isset($_POST['accountType']) and isset($_POST['email']) and 
        isset($_POST['password']) and isset($_POST['passwordConfirmation'])){
            return true;
        }
        return false;
    }

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
    * @return 13 hexidecimal digits string based on sys time.
    */
    function get_uuid(){
        return uniqid('');
    }

    /**
    * @return fixed 60 bits string.
    */
    function hash_pass($pass){
        //Recommended to use the default salt generated from the function.
        //src: https://www.php.net/manual/en/function.password-hash.php
        return password_hash($pass, PASSWORD_BCRYPT);
    }

    function validate_password_strength($pass){
        // Modified from
        // src: https://www.muhlenberg.edu/offices/oit/about/policies_procedures/strong-passwords.html
        // src: https://www.codexworld.com/how-to/validate-password-strength-in-php/
        $uppercase = preg_match('@[A-Z]@', $pass);
        $lowercase = preg_match('@[a-z]@', $pass);
        $number    = preg_match('@[0-9]@', $pass);
        $specialChars = preg_match('@[^\w]@', $pass);
        $length = strlen($pass) >= 8 ? true : false;

        if($uppercase && $lowercase && $number && $specialChars && $length){
            return true;
        }
        return false;
    }

    if (!is_all_fields_set()) {
        die('All fields are required.');
    }

    $type = $_POST['accountType'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $pass_con = $_POST['passwordConfirmation'];

    if (!validate_email_input($email)) die('incorrect email input.');
    if ($pass != $pass_con) die('passwords must be the same.');
    if (exists_email($email)) die('email already exists');
    if (!validate_password_strength($pass)){ die('password must contains at least 
        1 uppercase character,1 lowercase character, 1 number and 1 special character.');}

    //validated, start to insert
    //generate UUID and hash the password.
    $uuid = get_uuid();
    $pass_hashed = hash_pass($pass);
    $sql = 'INSERT INTO `User` (`userId`, `email`, `password`, `type`) VALUES (?, ?, ?, ?);';
    prepare_bind_excecute($sql, "ssss", $uuid, $email, $pass_hashed, $type);
    
    redirect('index.php', 'Account created');
?>