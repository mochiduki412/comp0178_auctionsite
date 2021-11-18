<?php require_once('db_utils.php');?>

<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.
    if(!is_login()){
        redirect($_SERVER['HTTP_REFERER'], 'You are not logged in.');
    }
    $auctionId = $_POST['itemId'];
    $user = $_SESSION['user'];
    $price = $_POST['bidPrice'];

    $sql = "INSERT INTO `Bid` (`auctionId`, `bidderId`, `bidPrice`) VALUES (?, ?, ?)";
    try{
        prepare_bind_excecute($sql, 'isi', $auctionId, $user, $price);
    } catch(Exception $e){
        print_msg('Failed to place bid, please try again later.');
        die();
    }

    print_msg("Created bid successfully!");
?>