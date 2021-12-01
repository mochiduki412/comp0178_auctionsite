<?php 
    foreach (glob("includes/*.php") as $filename) {
    require_once($filename);
};?>

<?php
    // TODO: Extract $_POST variables, check they're OK, and attempt to create
    // an account. Notify user of success/failure and redirect/give navigation 
    // options.
    function is_all_fields_set(){
        if(isset($_POST['accountType']) and isset($_POST['email']) and 
        isset($_POST['password']) and isset($_POST['passwordConfirm'])){
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
    * @return 13 chars based on sys time. Not very reliable but for simplicity now.
    */
    function get_uuid(){
        return uniqid('', false);
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
    $pass_con = $_POST['passwordConfirm'];
    $fname = $_POST['firstName'];
    $lname = $_POST['lastName'];

    if (!validate_email_input($email)) die('incorrect email input.');
    if (exists_email($email)) die('email already exists');
    if ($pass != $pass_con) die('passwords must be the same.');
    if (!validate_password_strength($pass)){ die('password must contains at least 
        1 uppercase character,1 lowercase character, 1 number and 1 special character.');}

    //validated, start to insert
    $uuid = get_uuid();
    $pass_hashed = hash_pass($pass);
    $sql = 'INSERT INTO `User` (`userId`, `firstname`, `lastname`, `email`, `password`, `type`) VALUES (?, ?, ?, ?, ?, ?);';
    try{
        prepare_bind_excecute($sql, "ssssss", $uuid, $fname, $lname, $email, $pass_hashed, $type);
    } catch(Exception){
        error_log($e);
        print_msg("Internal error, please try later.");
        die();
    }
    
    redirect('index.php', 'Account created');
?>
