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

    $user_id = $_SESSION['user'];
    $price = (int) $_POST['bidPrice'];

    $sql = "SELECT * FROM Auction WHERE auctionId = ?";
    $res = prepare_bind_excecute($sql, 's', $item_id);
    if($res === null)redirect($url_back, "Auction does not exist.");
    $row = $res->fetch_assoc();

    // validate auction status
    $date_cur = new Datetime('now');
    $date_end = Datetime::createFromFormat('Y-m-d H:i:s', $row['endDate']);
    if($row['status'] == $INACTIVE){
        redirect($url_back, "Auction is not active.");
    }
    
    // If bid is not higher than the current bid, refuse.
    if((int) $price <= get_max_bid_price_by_auction($item_id)){
        redirect($url_back, "Please specify a bid higher than the current bid");
    }

    // OK, place bid and update Auction accordingly
    $sql = "INSERT INTO `Bid` (`auctionId`, `bidderId`, `bidPrice`) VALUES (?, ?, ?);";
    try{
        prepare_bind_excecute($sql, 'isi', $item_id, $user_id, $price);
    } catch(Exception $e){
        redirect($url_back, 'Failed to place bid, please try again later.');
    }

    print_msg("Create bid successfully! <a href='mybids.php'>View my bids</a>.");
?>

<?php include_once('footer.php');?>
