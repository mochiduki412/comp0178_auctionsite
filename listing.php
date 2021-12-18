<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php  
  foreach (glob("includes/*.php") as $filename) {
    require_once($filename);
}?>


<?php
  session_start();
  // Get info from the URL:
  $item_id = $_GET['item_id'];
  $user_id = $_SESSION['user'];

  // TODO: Use item_id to make a query to the database.
  $sql = "SELECT * FROM `Auction` WHERE `auctionId` = ?";
  $results = prepare_bind_excecute($sql, 'i', $item_id);
  if(!$row = $results->fetch_assoc()){
    print_msg('Item not found.');
    die();
  }

  $title = $row["itemName"];
  $description = $row['itemDescription'];
  $bid_max_info = get_max_bid_info_by_auction($item_id)->fetch_assoc();
  $bid_max_amount = $bid_max_info['bidPrice'];
  $bid_max_user = $bid_max_info['bidderId'];
  $end_time = new DateTime($row['endDate']);

  // TODO: Note: Auctions that have ended may pull a different set of data,
  //       like whether the auction ended in a sale or was cancelled due
  //       to lack of high-enough bids. Or maybe not.
  
  // Calculate time to auction end:
  $now = new DateTime();
  
  if ($now < $end_time) {
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
  }
  
  // TODO: If the user has a session, use it to make a query to the database
  //       to determine if the user is already watching this item.
  //       For now, this is hardcoded.
  $has_session = false;
  $watching = false;
  if(is_login()){
    $has_session = true;
    $sql = "SELECT * FROM `watchlist` WHERE userId = ? AND auctionId = ?";
    $res = prepare_bind_excecute($sql, 'si', $user_id, $item_id)->fetch_assoc();
    if($res){
      $watching = true;
    }
  }
?>


<div class="container">

<div class="row"> <!-- Row #1 with auction title + watch button -->
  <div class="col-sm-8"> <!-- Left col -->
    <h2 class="my-3"><?php echo($title); ?></h2>
  </div>
  <div class="col-sm-4 align-self-center"> <!-- Right col -->
<?php
  /* The following watchlist functionality uses JavaScript, but could
     just as easily use PHP as in other places in the code */
  if ($now < $end_time and is_login()):
?>
    <div id="watch_nowatch" <?php if ($has_session && $watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist()">+ Add to watchlist</button>
    </div>
    <div id="watch_watching" <?php if (!$has_session || !$watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-success btn-sm" disabled>Watching</button>
      <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist()">Remove watch</button>
    </div>
<?php endif /* Print nothing otherwise */ ?>
  </div>
</div>

<div class="row"> <!-- Row #2 with auction description + bidding info -->
  <div class="col-sm-8"> <!-- Left col with item info -->

    <div class="itemDescription">
    <?php echo($description); ?>
    </div>

  </div>

  <div class="col-sm-4"> <!-- Right col with bidding info -->

    <p>
<?php if ($now > $end_time): ?>
     This auction ended <?php echo(date_format($end_time, 'j M H:i')) ?>
     <!-- TODO: Print the result of the auction here? -->
<?php else: ?>
     Auction ends <?php echo(date_format($end_time, 'j M H:i') . $time_remaining) ?></p>  
    <p class="lead">
      Current bid: £<?php echo(number_format($bid_max_amount, 2)) ?><br>
      <!-- IMPROVEME:Change displaying user id to username  -->
      <small>by <a href="userbids.php?user=<?php echo($bid_max_user)?>"><?php echo($bid_max_user) ?></a></small>
    </p>

    <!-- Bidding form -->
    <form method="POST" action="place_bid.php">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">£</span>
        </div>
	    <input type="number" class="form-control" id="bid" name="bidPrice" value='0'>
      </div>
      <input type="hidden" id="itemId" name="itemId" value=<?php echo $_GET['item_id'] ?>>
      <button type="submit" class="btn btn-primary form-control">Place bid</button>
    </form>
<?php endif ?>

  
  </div> <!-- End of right col with bidding info -->

</div> <!-- End of row #2 -->



<?php include_once("footer.php")?>


<script> 
// JavaScript functions: addToWatchlist and removeFromWatchlist.

function addToWatchlist(button) {
  console.log("These print statements are helpful for debugging btw");

  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'add_to_watchlist', arguments: [<?php echo($item_id);?>]},

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          console.log("sql success");
          $("#watch_nowatch").hide();
          $("#watch_watching").show();
        }
        else {
          console.log(objT);
          var mydiv = document.getElementById("watch_nowatch");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Add to watch failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func

function removeFromWatchlist(button) {
  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'remove_from_watchlist', arguments: [<?php echo($item_id);?>]},

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_watching").hide();
          $("#watch_nowatch").show();
        }
        else {
          var mydiv = document.getElementById("watch_watching");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Watch removal failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func
</script>