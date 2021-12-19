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

    $item_id = $_POST['itemId'];
    $url_back = 'listing.php?item_id=' . $item_id;
    if(!is_login()) redirect($url_back, 'You are not logged in.');

    $user_id = $_SESSION['user'];
    $price = (int) $_POST['bidPrice'];

    $sql = "SELECT * FROM Auction WHERE auctionId = ?";
    $res = prepare_bind_excecute($sql, 's', $item_id);
    if($res === null) redirect($url_back, "Auction does not exist.");
    $row = $res->fetch_assoc();

    // validate auction status
    $date_cur = new Datetime('now');
    $date_end = Datetime::createFromFormat('Y-m-d H:i:s', $row['endDate']);
    if($row['status'] == $INACTIVE){
        redirect($url_back, "Auction is not active.");
    }
    
    // If bid is not higher than the current bid, refuse.
    $bid_info = get_max_bid_info_by_auction($item_id)->fetch_assoc();
    if((int) $price <= $bid_info['bidPrice']){
        redirect($url_back, "Please specify a bid higher than the current bid");
    }

    // OK, place bid, update new max bidder and email.
    $sql = "INSERT INTO `Bid` (`auctionId`, `bidderId`, `bidPrice`) VALUES (?, ?, ?);";
    try{
        prepare_bind_excecute($sql, 'isi', $item_id, $user_id, $price);
    } catch(Exception $e){
        redirect($url_back, 'Failed to place bid, please try again later.');
    }

    $username = get_name_by_user_id($user_id);
    $sql = "SELECT * FROM User WHERE userId = ?";
    $out_bidder_info = prepare_bind_excecute($sql, 's', $bid_info['bidderId'])->fetch_assoc();
    $out_bidder_name = $out_bidder_info['firstName'] . ' ' . $out_bidder_info['lastName'];
    print_msg("Create bid successfully! <a href='mybids.php'>View my bids</a>.");

    # inform out bidder
    if( $username == $out_bidder_name) return; # do not inform myself
    $subject = "You have been out bidded";
    $msg  = "Dear user ". $out_bidder_name . ",\n";
    $msg .= "You have been out bidded by £ " . $price . " from " 
    . $username . " on auction " . $item_id;
    $mailer = Mailer::get_mailer($develop = true);
    $mailer->send($out_bidder_info['email'], $out_bidder_name, $subject, $msg);

    # inform watchlist watcher
    $sql = "SELECT userId FROM `watchlist` WHERE auctionId = ?";
    $watchers = prepare_bind_excecute($sql, 'i', $item_id);
    while($watcher = $watchers->fetch_assoc()){
        $subject = "Your watched auction has status change";
        $watcher_name = $out_bidder_info['firstName'] . ' ' . $out_bidder_info['lastName'];
        $msg  = "Dear user ". $watcher_name . ",\n";
        $msg .= "You have been out bidded by £ " . $price . " from " 
        . $username . " on auction " . $item_id;
        $mailer = Mailer::get_mailer($develop = true);
        $mailer->send($watcher['email'], $watcher_name, $subject, $msg);
    }

    // print_msg("Create bid successfully! <a href='mybids.php'>View my bids</a>.");
?>

<?php include_once('footer.php');?>
