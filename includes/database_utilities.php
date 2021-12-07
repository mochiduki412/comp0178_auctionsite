<?php
    $SERV = getenv('SERV') ?: "localhost";
    $USER = getenv('USER') ?: "root";
    $PASS = getenv('PASS') ?: "";
    $DB = getenv('DB') ?: "comp0178db";

   function printnl($text)
   {
       echo nl2br("$text\n");
   }

    function connect_db()
    {
        global $SERV, $USER, $PASS, $DB;
        // Establish connection
        $connection = mysqli_connect($SERV, $USER, $PASS)
            or die('Error connecting to MySQL server' . mysqli_error($connection));
        // Select database
        mysqli_select_db($connection, $DB);

        return $connection;
    }

    // connect to & query database
    function query_database($query)
    {
        global $SERV, $USER, $PASS, $DB;
        printnl("Querying Database...");
        // Establish connection
        $connection = mysqli_connect($SERV, $USER, $PASS)
            or die('Error connecting to MySQL server' . mysqli_error($connection));

        mysqli_select_db($connection, $DB);
        // Make query
        // Returns mysqli_result object
        $result = mysqli_query($connection, $query)
            or die('Error making query' . mysqli_error($connection));

        // end connection
        mysqli_close($connection);

        return $result;
    }

    // Creates a new database and imports tables & data as specified by database_dump.sql 
    // Note - this appears "not to work" if the database already exists with contents
    function restore_database()
    {
        // Establish connection
        $connection = mysqli_connect('localhost', 'root', '')
            or die('Error connecting to MySQL server' . mysqli_error($connection));
            

        $mysql_database = "comp0178db";

        // Drop database if it exists
        printnl("Dropping old database...");
        $drop_query = "DROP DATABASE IF EXISTS " . $mysql_database . ";";
        mysqli_query($connection, $drop_query)
            or die('Error making query' . mysqli_error($connection));
        printnl("Successfully dropped database!");
        
        // Define create database query
        printnl("Creating Database...");
        $create_query = "CREATE DATABASE IF NOT EXISTS " . $mysql_database . ";";


        // Run create database query
        $create_result = mysqli_query($connection, $create_query)
            or die('Error making query' . mysqli_error($connection));

        // Select database as default & print result
        $selected_db = mysqli_select_db($connection, $mysql_database)
            or die("Error selecting database" . mysqli_error($connection));
        if ($create_result == 1 && $selected_db == 1) {
            printnl("Successfully created '$mysql_database' database!");
        };

        // Load database contents from file
        printnl("Restoring Database...");
        $restore_query = file_get_contents("database_dump.sql");
        // printnl($restore_query);
        $restore_result = mysqli_multi_query($connection, $restore_query);
        // print($restore_result);

        // end connection
        mysqli_close($connection);
        if ($restore_result == 1) {
            printnl("Database successfully restored!");
        };
}
