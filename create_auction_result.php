<?php include_once("header.php")?>

<div class="container my-5">


<?php
    function printnl($text)
    {
        echo nl2br("$text\n");
    }

    $SERVER = "localhost";
    $USER = getenv('DBUSR') ?: "usr";
    $PASS = getenv('DBPWD') ?: "pwd";
    $DB = getenv('DB') ?: "db";
    $TABLE = "Auction";

    $conn = new mysqli($SERVER, $USER, $PASS);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->select_db($DB);

    $sql = "INSERT INTO " . $TABLE ."(title, reservePrice, startingPrice, 
    itemDescription, itemCat, endDate) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if(!$stmt) die("Statement prepare failed: " . $conn->error);

    $stmt->bind_param("siisss", $_POST['auctionTitle'], $_POST['auctionReservePrice'],
    $_POST['auctionStartPrice'], $_POST['auctionDetails'], $_POST['auctionCategory'],
    $_POST['auctionEndDate']);
    if(!$stmt->execute()) die("Execution failed: " . $stmt->error);

    // Successful
    echo('<div class="text-center">Auction successfully created! <a href="FIXME">View your new listing.</a></div>');
?>

</div>


<?php include_once("footer.php")?>