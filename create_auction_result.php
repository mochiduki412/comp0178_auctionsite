<?php include_once("header.php")?>
<?php require_once("db_utils.php")?>

<div class="container my-5">
<?php
    session_start();
    if(!is_login()){
        redirect('index.php', 'Please log in before starting an auction.');
    } else{
        $seller = $_SESSION['user']; // the user id as identifier.

        $sql = "INSERT INTO Auction (title, sellerID, reservePrice, startingPrice, 
                itemDescription, itemCat, endDate) VALUES (?, ?, ?, ?, ?, ?, ?)";
        try{
            prepare_bind_excecute($sql, "ssiisss", $_POST['auctionTitle'], 
                                $seller, $_POST['auctionReservePrice'], 
                                $_POST['auctionStartPrice'], $_POST['auctionDetails'], 
                                $_POST['auctionCategory'], $_POST['auctionEndDate']);
        } catch(Exception $e){
            // TODO: log the error later
            echo('<div class="text-center">Internal error, please try again.</div>');
        }

        // auction created
        echo('<div class="text-center">Auction is successfully created! <a href="mylistings.php">View your new listing.</a></div>');
        // redirect('create_auction.php'); // prevent refresh to re-post
    }
?>
</div>


<?php include_once("footer.php")?>