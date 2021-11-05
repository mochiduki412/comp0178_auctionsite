<?php include_once("header.php")?>
<?php require_once("db_utils.php")?>

<div class="container my-5">
<?php
    $TABLE = "Auction";

    $sql = "INSERT INTO " . $TABLE ."(title, reservePrice, startingPrice, 
    itemDescription, itemCat, endDate) VALUES (?, ?, ?, ?, ?, ?)";
    $conn = get_conn();
    $stmt = $conn->prepare($sql);
    if(!$stmt) die("Statement prepare failed: " . $conn->error);

    $stmt->bind_param("siisss", $_POST['auctionTitle'], $_POST['auctionReservePrice'],
    $_POST['auctionStartPrice'], $_POST['auctionDetails'], $_POST['auctionCategory'],
    $_POST['auctionEndDate']);
    if(!$stmt->execute()) die("Execution failed: " . $stmt->error);

    $stmt->close();
    $conn->close();
    // Successful
    echo('<div class="text-center">Auction successfully created! <a href="FIXME">View your new listing.</a></div>');
?>
</div>


<?php include_once("footer.php")?>