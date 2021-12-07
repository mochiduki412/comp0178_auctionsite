<?php
    // ===== DB connection relateds =====
    $SERV = getenv('SERV') ?: "localhost";
    $USER = getenv('USER') ?: "root";
    $PASS = getenv('PASS') ?: "";
    $DB = getenv('DB') ?: "comp0178db";

    function get_conn(){
        // IMRPOVE: Consider pooling
        global $SERV, $USER, $PASS, $DB;
        $conn = new mysqli($SERV, $USER, $PASS);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $conn->select_db($DB);
        return $conn;
    }

    function prepare_bind_excecute($sql, $col_types, ...$cols){
        $conn = get_conn();
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Preparation failed: " . $conn->error); //IMPROVE: raise error instead
        $stmt->bind_param($col_types, ...$cols);
        if (!$stmt->execute()) die("Execution failed: " . $stmt->error);
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        return $result;
    }
?>