<?php include_once('header.php');?>
<?php require_once('db_utils.php');?>

<?php
// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.

    // TODO: Guard against POST request later!
    $item_id = $_POST['itemId'];
    if(!is_login()) redirect('listing.php?item_id=' . $item_id, 'You are not logged in.');

    $user = $_SESSION['user'];
    $price = (int) $_POST['bidPrice'];

    // If bid is not higher than the current max bid, refuse.
    $bid_max = get_max_bid_from($item_id);
    if($price < $bid_max){
        print_msg("Please specify a bid higher than the current bid");
        die();
    }

    // OK, place bid
    $sql = "INSERT INTO `Bid` (`auctionId`, `bidderId`, `bidPrice`) VALUES (?, ?, ?)";
    try{
        prepare_bind_excecute($sql, 'isi', $item_id, $user, $price);
    } catch(Exception $e){
        print_msg('Failed to place bid, please try again later.');
        die();
    }

    print_msg("Create bid successfully! <a href='mybids.php'>View my bids</a>.");
?>

<?php include_once('footer.php');?>
