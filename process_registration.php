
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

    function exists_email($email)
    {
        $sql = sprintf("SELECT email FROM User WHERE email='%s'", $email);
        $conn = get_conn();
        $result = $conn->query($sql);
        $conn->close();
        if ($result->num_rows == 0) {
            return false;
        }
        return true;
    }

    if (!isset($_POST['accountType']) or !isset($_POST['email']) or !isset($_POST['password']) or !isset($_POST['passwordConfirmation'])) {
        die('All fields are required.');
    }

    $type = $_POST['accountType'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $pass_con = $_POST['passwordConfirmation'];

    if (!validate_email_input($email)) die('incorrect email input.');
    if ($pass != $pass_con) die('passwords must be the same.');
    if (exists_email($email)) die('email already exists');

    // validation pass, staring to insert
    $conn = get_conn();
    $sql = 'INSERT INTO `User` (`email`, `password`) VALUES (?, ?);';
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Statement prepare failed: " . $conn->error);
    $stmt->bind_param("ss", $email, $pass);
    if (!$stmt->execute()) die("Execution failed: " . $stmt->error);
    $stmt->close();
    $conn->close();

    // created account. TODO: implment redirection
    echo('<div class="text-center">Account created</div>');

?>