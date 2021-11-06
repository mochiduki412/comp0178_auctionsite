<?php include_once("header.php")?>
<?php require_once("db_utils.php")?>

<div class="container my-5">
<?php
    $sql = "INSERT INTO Auction (title, sellerID, reservePrice, startingPrice, 
    itemDescription, itemCat, endDate) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $conn = get_conn();
    $stmt = $conn->prepare($sql);
    if(!$stmt) die("Statement prepare failed: " . $conn->error);

    // TODO: Securely identify the seller and link the right FK from the User.
    $sellerID = 100;
    $stmt->bind_param("siiisss", $_POST['auctionTitle'], $sellerID, $_POST['auctionReservePrice'],
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