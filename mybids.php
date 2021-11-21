<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php require_once("db_utils.php")?>


<div class="container">

<h2 class="my-3">My bids</h2>

<?php
  // This page is for showing a user the auctions they've bid on.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.

  // TODO: Check user's credentials (cookie/session).
  session_start();
  if(!is_login()) redirect($_SERVER['HTTP_REFERER'] or 'index.php', 'You are not logged in.');

  // TODO: Perform a query to pull up the auctions they've bidded on.
  $sql = "SELECT * FROM `Bid` LEFT JOIN `Auction` ON Bid.auctionId = Auction.auctionId WHERE `bidderId` = ?";
  try{
    $results = prepare_bind_excecute($sql, 's', $_SESSION['user']);
  } catch(Exception $e){
    error_log($e);
    print_msg("Internal error, please try later.");
  }
  
  // TODO: Loop through results and print them out as list items.
  // display_HTML_table_from($results);
  while($row = $results->fetch_assoc()){
    print_listing_li(
      $row['auctionId'], 
      $row['title'],
      $row['itemDescription'],
      $row['bidPrice'],
      get_num_bid_by_auction($row['auctionId']),
      new DateTime($row['endDate'])
    );
  }
?>

<?php include_once("footer.php")?>