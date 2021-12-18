<?php
    // ===== page relateds =====
    function print_msg($msg){
        echo(sprintf('<div class="text-center">%s</div>', $msg));
    }

    function print_h3($msg){
        echo('<h3 class="text-center">' . $msg . '</h3>');
    }

    function alert($text) {
        echo('<script type="text/javascript">alert("' . $text . '")</script>');
    }

    function redirect($url = "index.php", $msg = '', $after_seconds = 3){
        print_msg($msg);
        echo(sprintf("<script>alert(%s);</script>", $msg));
        header(sprintf("refresh:%d;url=%s", $after_seconds, $url));
        die();
    }

    function is_login(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return (isset($_SESSION['logged_in']) and $_SESSION['logged_in'] == true) ? true: false;
    }

    function is_seller(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return (isset($_SESSION['account_type']) and $_SESSION['account_type'] == 'seller') ? true: false;
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
?>