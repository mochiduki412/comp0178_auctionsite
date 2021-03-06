<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php 
    foreach (glob("includes/*.php") as $filename) {
    require_once($filename);
};?>


<div class="container">
<?php
  /*
   / Alias to mybids.php to support user to view others' bids.
   / May want to use this mote general page to replace the default mybids.php instead.
  */
  session_start();
  if(!is_login()) redirect($_SERVER['HTTP_REFERER'] or 'index.php', 'You need to log in to use this page.'); // must login to use it for simplicity.

  // IMPROVE: More properly deal with user not exist and uses username
  $user_id = isset($_GET['user']) ? $_GET['user'] : $_SESSION['user'];
  echo sprintf('<h2 class="my-3">Bids by %s</h2>', get_name_by_user_id($user_id));

  try{
    $results = get_bids_by_user($user_id);
  } catch(Exception $e){
    error_log($e);
    print_msg("Internal error, please try later.");
    die();
  }
  
  while($row = $results->fetch_assoc()){
    print_listing_li(
      $row['auctionId'], 
      $row["itemName"],
      $row['itemDescription'],
      $row['bidPrice'],
      get_num_bid_by_auction($row['auctionId']),
      new DateTime($row['endDate'])
    );
  }
?>

<?php include_once("footer.php")?>