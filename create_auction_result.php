<?php include_once("header.php")?>
<?php  
    foreach (glob("includes/*.php") as $filename) {
        require_once($filename);
}?>

<div class="container my-5">
<?php
    $ACTIVE = 1;

    session_start();
    if(!is_login()){
        redirect('index.php', 'Please log in before starting an auction.');
    } elseif(!is_seller()){
        redirect($_SERVER['HTTP_REFERER'] or 'index.php', 'You are not a seller.');
    } else{
        $seller = $_SESSION['user']; // the user id as identifier.

        $sql = "INSERT INTO Auction (title, sellerID, reservePrice, startingPrice, 
                itemDescription, itemCat, endDate, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        try{
            prepare_bind_excecute(
                $sql, "ssiisssi", 
                $_POST['auctionTitle'], 
                $seller, 
                $_POST['auctionReservePrice'], 
                $_POST['auctionStartPrice'], 
                $_POST['auctionDetails'], 
                $_POST['auctionCategory'], 
                $_POST['auctionEndDate'],
                $ACTIVE
            );
        } catch(Exception $e){
            print_msg('Internal error, please try again.');
        }

        // auction created
        print_msg('Auction is successfully created! <a href="mylistings.php">View your new listing.</a>');
        // redirect('create_auction.php'); // prevent refresh to re-post
    }
?>
</div>


<?php include_once("footer.php")?>