<?php
    /*
    * Wenyong: Will refactor after this file gathers some.
    */
    $SERVER = "localhost";
    $USER = getenv('DBUSR') ?: "db";
    $PASS = getenv('DBPWD') ?: "db";
    $DB = getenv('DB') ?: "db";

    function printnl($text)
    {
        echo nl2br("$text\n");
    }

    function get_conn(){
        global $SERVER, $USER, $PASS, $DB;
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
        if (!$stmt) die("Preparation failed: " . $conn->error); //TODO: raise error instead
        $stmt->bind_param($col_types, ...$cols);
        if (!$stmt->execute()) die("Execution failed: " . $stmt->error); //TODO: raise error instead
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        return $result;
    }

    function print_msg($msg){
        echo(sprintf('<div class="text-center">%s</div>', $msg));
    }

    function redirect($url = "index.php", $msg = '', $after_seconds = 3){
        print_msg($msg);
        header(sprintf("refresh:%d;url=%s", $after_seconds, $url));
        die();
    }

    function is_login(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return (isset($_SESSION['logged_in']) and $_SESSION['logged_in'] == true) ? true: false;
    }

   /*
    * Simple display for sql query results into html table.
    */
    function display_HTML_table_from($results){
        echo "<div class='container'>";
        echo "<table class='table'>";
        if($row = $results->fetch_assoc()){
            // display attributes
            echo "<thead>";
            echo "<tr>";
            foreach($row as $key => $val){
                echo "<th>" . $key . "</th>";
            }
            echo "</tr>";
            echo "</thead>";

            // display query data
            echo "<tbody>";
            do{
                echo "<tr>";
                foreach($row as $key => $val){
                    echo "<td>" . $val . "</td>";
                }
                echo "</tr>";
            } while($row = $results->fetch_assoc());
            echo "</tbody>";
        }
        echo "</table>";
        echo "</div>";
    }

    function display_list_from($results){
        return null;
    }
?>