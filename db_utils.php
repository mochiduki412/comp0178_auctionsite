<?php
    function printnl($text)
    {
        echo nl2br("$text\n");
    }

    $SERVER = "localhost";
    $USER = getenv('DBUSR') ?: "db";
    $PASS = getenv('DBPWD') ?: "db";
    $DB = getenv('DB') ?: "db";

    function get_conn(){
        global $conn, $SERVER, $USER, $PASS, $DB;
        $conn = new mysqli($SERVER, $USER, $PASS);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $conn->select_db($DB);
        return $conn;
    }

    function prepare_bind_excecute($sql, $col_types, ...$cols){
        $conn = get_conn();
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Preparation failed: " . $conn->error);
        $stmt->bind_param($col_types, ...$cols);
        if (!$stmt->execute()) die("Execution failed: " . $stmt->error);
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        return $result;
    }
?>