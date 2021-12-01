<?php include_once('header.php');?>
<?php 
    foreach (glob("includes/*.php") as $filename) {
    require_once($filename);
};?>

<?php
// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.
    $INACTIVE = 0;
    $ACTIVE = 1;

    // TODO: Guard against POST request later!
    $item_id = $_POST['itemId']; // We do not distinguish item and auction in DB for now.
    $url_back = 'listing.php?item_id=' . $item_id;
    if(!is_login()) redirect($url_back, 'You are not logged in.');

    $user = $_SESSION['user'];
    $price = (int) $_POST['bidPrice'];

    $sql = "SELECT status, endDate, curBidPrice, curBidderId FROM Auction WHERE auctionId = ?";
    try{
        $res = prepare_bind_excecute($sql, 's', $item_id);
    } catch(Exception $e){
        error_log($e);
        redirect($url_back, 'Failed to place bid, please try again later.');
    }
    if($res === null){
        redirect($url_back, "Auction does not exist.");
    }
    $row = $res->fetch_assoc();

    // validate auction status
    $date_cur = new Datetime('now');
    $date_end = Datetime::createFromFormat('Y-m-d H:i:s', $row['endDate']);
    if($row['status'] == $INACTIVE){
        redirect($url_back, "Auction is not active.");
    } 
    // Found an expired auction but status is yet to change! We manually update the db.
    elseif($date_cur > $date_end){
        set_auction_inactive($item_id);
        $sql = "INSERT INTO `Finished Auction` (`id`, `auctionId`, `winnerId`) VALUES (NULL, '?', '?')";
        // put into finished auction as well, we may want to delete the record from Auction later but now I just leave it for the discussion in next meeting
        prepare_bind_excecute($sql, 'is', $auctionId, $row['curBidderId']);
        redirect($url_back, "Auction is not active.");
    }
    
    // If bid is not higher than the current bid, refuse.
    $bid_cur = $row['curBidPrice'];
    if($price < $bid_cur){
        redirect($url_back, "Please specify a bid higher than the current bid");
    }

    // OK, place bid and update Auction accordingly
    $sql = "INSERT INTO `Bid` (`auctionId`, `bidderId`, `bidPrice`) VALUES (?, ?, ?);
            UPDATE Auction Set curBidderId=?, curBidPrice=? WHERE auctionId = ?;";
    try{
        prepare_bind_excecute($sql, 'isiis', $item_id, $user, $price, $user, $price, $item_id);
    } catch(Exception $e){
        error_log($e);
        redirect($url_back, 'Failed to place bid, please try again later.');
        die();
    }

    print_msg("Create bid successfully! <a href='mybids.php'>View my bids</a>.");
?>

<?php include_once('footer.php');?>
