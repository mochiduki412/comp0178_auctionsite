<?php include_once("header.php")?>
<?php require_once("db_utils.php")?>

<div class="container my-5">
<?php
    session_start();
    if(!$_SESSION['logged_in']){
        echo('<div class="text-center">You are not logged in. Please log in before starting an auction</div>');
        header("refresh:5;url=index.php");
    } else{
        // TODO: Link the right FK from the User.  May need to more securely identify the seller.
        $sql = "INSERT INTO Auction (title, sellerID, reservePrice, startingPrice, 
        itemDescription, itemCat, endDate) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $seller = $_SESSION['user']; // the user id as identifier for now.
        prepare_bind_excecute($sql, "ssiisss", 
            $_POST['auctionTitle'], $seller, $_POST['auctionReservePrice'], 
            $_POST['auctionStartPrice'], $_POST['auctionDetails'], 
            $_POST['auctionCategory'], $_POST['auctionEndDate']);

        // auction created
        echo('<div class="text-center">Auction is successfully created! <a href="mylistings.php">View your new listing.</a></div>');
    }
?>
</div>


<?php include_once("footer.php")?>