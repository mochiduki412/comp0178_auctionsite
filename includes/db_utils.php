<?php
    // ===== DB connection relateds =====
    $SERV = getenv('SERV') ?: "localhost";
    // $USER = getenv('USER') ?: "root";
    $USER = 'root';
    $PASS = getenv('PASS') ?: "";
    $DB = getenv('DB') ?: "comp0178db";
   
    class DBException extends Exception {}

    function get_conn($auto_commit = true){
        // IMRPOVE: Consider pooling
        global $SERV, $USER, $PASS, $DB;
        $conn = new mysqli($SERV, $USER, $PASS);
        $conn->autocommit($auto_commit);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $conn->select_db($DB);
        return $conn;
    }

    function prepare_bind_excecute($sql, $col_types, ...$cols){
        $conn = get_conn();
        $stmt = $conn->prepare($sql);
        if (!$stmt) throw new DBException("Preparation failed: " . $conn->error); //IMPROVE: raise error instead
        $stmt->bind_param($col_types, ...$cols);
        if (!$stmt->execute()) throw new DBException("Execution failed: " . $stmt->error);
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        return $result;
    }

    function query_database($sql) {
        $conn = get_conn();
        $result = mysqli_query($conn, $sql);
        if (!$result){
            throw new DBException("Preparation failed: " . mysqli_error($conn));
            die('Error making query' . mysqli_error($conn)); #preserve original behavior even if exception is handled.
        }
        mysqli_close($conn);
        return $result;
    }
?>