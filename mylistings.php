<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php  
  foreach (glob("includes/*.php") as $filename) {
    require_once($filename);
}?>


<div class="container">

<h2 class="my-3">My listings</h2>

<?php
  // This page is for showing a user the auction listings they've made.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  // TODO: Check user's credentials (cookie/session).
  // TODO: Perform a query to pull up their auctions.
  // TODO: Loop through results and print them out as list items.

  session_start();
  if(!is_login()) redirect('browse.php', 'You are not logged in.');
  
  // logged in
  $sql = "SELECT Auction.auctionId, itemName, itemDescription, max(bidPrice) as maxBid, COUNT(bidPrice) as cnt, endDate 
          FROM `Auction` 
          INNER JOIN `Bid` 
          ON Bid.auctionId = Auction.auctionId
          WHERE sellerId = ?
          GROUP BY auctionId
          ";

  $results = prepare_bind_excecute($sql, 's', $_SESSION['user']);
  while($row = $results->fetch_assoc()){
    print_listing_li(
      $row['auctionId'], 
      $row["itemName"],
      $row['itemDescription'],
      $row['maxBid'],
      $row['cnt'],
      new DateTime($row['endDate'])
    );
  }
?>

<?php include_once("footer.php")?>