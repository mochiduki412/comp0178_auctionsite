<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php require("db_utils.php")?>


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
  if(!is_login()){
    redirect('browse.php', 'You are not logged in.');
    die();
  } else{ // logged in
    $sql = "SELECT * FROM `Auction` WHERE `sellerId` = ?";
    $results = prepare_bind_excecute($sql, 's', $_SESSION['user']);
    while($row = $results->fetch_assoc()){
      // Buggy! We need to change our ER design to:
      // TODO 1: Record the number of bids (I presume the bid history).
      // TODO 2: Record the current bid price.
      print_listing_li($row['auctionId'], $row['title'], $row['itemDescription'],
                        $row['reservePrice'], 1, new DateTime($row['endDate']));
    }
    // display_HTML_table_from($results);
  }
?>

<?php include_once("footer.php")?>