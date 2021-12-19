<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php  
    foreach (glob("includes/*.php") as $filename) {
        include_once($filename);
}?>

<!-- 
  // This page is for showing a buyer recommended items based on their bid 
  // history. It will be pretty similar to browse.php, except there is no 
  // search bar. This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.

  // TODO: Check user's credentials (cookie/session).
  // TODO: Perform a query to pull up auctions they might be interested in.
  // TODO: Loop through results and print them out as list items.
 -->
<div class="container">
<h3 class="my-3">Recommended Auctions</h3>

<div id="searchSpecs">
<form method="get" action="browse.php" id="form">
  <div class="row">
  <!-- --------------------------- KEYWORD SEARCH ---------------------------- -->
    <div class="col-md-5 pr-0">
      <div class="form-group">
        <label for="keyword" class="sr-only">Search for a keyword:</label>
	    <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text bg-transparent pr-0 text-muted">
              <i class="fa fa-search"></i>
            </span>
          </div>
          <!-- Define value to content of GET here to maintain keyword used after reload -->
          <input type="text" class="form-control border-left-0" id="keyword" name="keyword" placeholder="Search for anything" 
            value="<?php echo $_GET['keyword'] ?: '' ?>"
          >
        </div>
      </div>
    </div>

    <!-- --------------------------- CATEGORY FILTER --------------------------- -->
    <div class="col-md-3 pr-0">
      <div class="form-group">
        <label for="cat" class="sr-only">Search within:</label>
        <select class="form-control" id="cat" name="cat" onchange="handleSelectCat();">
          <?php 
              $item_categories = get_item_categories();
              echo ("<option id='all' value='all'>All categories</option>");
              while($row = mysqli_fetch_array($item_categories)) {
                echo ("<option id='{$row['itemCat']}' value='{$row['itemCat']}'> {$row['itemCat']} </option>");
              }
          ?>
        </select>
        </script>
      </div>
    </div>
    <!-- Script to preserve chosen category after reload -->
    <script type='text/javascript'>
      if (localStorage.getItem('cat')) {
        document.getElementById("<?php echo $_GET['cat'] ?>").selected = true;
      }

      function handleSelectCat() {
        document.getElementById('form').submit();
        localStorage.setItem('cat', "<?php echo $_GET['cat'] ?>");
      }
    </script>

    <!-- ---------------------------- OTHER SORTING ---------------------------- -->
    <div class="col-md-3 pr-0">
      <div class="form-inline">
        <!-- <label class="mx-2" for="order_by">Sort by:</label> -->
        <select class="form-control" id="order_by" name='order_by' onchange="handleSelectOrder();">
          <option value="pricelow" id='pricelow'>Price (low to high)</option>
          <option value="pricehigh" id='pricehigh'>Price (high to low)</option>
          <option value="datesoon" id='datesoon'>Expiry (soonest to latest)</option>
          <option value="datelate" id='datelate'>Expiry (latest to soonest)</option>
        </select>
      </div>
    </div>
    <!-- Script to preserve chosen sorting ordering after reload -->
    <script type='text/javascript'>
      if (localStorage.getItem('order_by')) {
        document.getElementById("<?php echo $_GET['order_by'] ?>").selected = true;
      }

      function handleSelectOrder() {
        document.getElementById('form').submit();
        localStorage.setItem('order_by', "<?php echo $_GET['order_by'] ?>");
      }
    </script>

    <!-- ---------------------------- SEARCH BUTTON ---------------------------- -->
    <div class="col-md-1 px-0">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>
</form>
</div> 

<!-- --------------------------- END SEARCH BAR ---------------------------- -->
</div>

<?php
  // Null coalescing operator - sets to a default value if null or undefined
  $keyword = $_GET['keyword'] ?? "";
  $cat = $_GET['cat'] ?? "all";
  $ordering = $_GET['order_by'] ?? "pricelow";
  $curr_page = $_GET['page'] ?? 1;

  /* 
  Use above values to construct a query to retrieve auctions from database
  Use appropriate default values when no filters specified
  */

  $my_auctions_view_sql = 
  "CREATE OR REPLACE VIEW my_auctions AS
  SELECT A.auctionId
  FROM Bid as B, Auction as A
  WHERE B.auctionId = A.auctionId
    AND bidderId = '{$_SESSION['user']}'";
  // print_h3($my_auctions_view_sql);
  query_database($my_auctions_view_sql);

  $related_bidders_view_sql = 
  "CREATE OR REPLACE VIEW related_bidders AS
  SELECT bidderId, COUNT(bidderId) AS commonBids
  FROM Bid
  WHERE auctionId IN (SELECT * FROM my_auctions)
    AND bidderId != '{$_SESSION['user']}'
  GROUP BY bidderId
  HAVING commonBids >= 3";
  query_database($related_bidders_view_sql);

  $recommended_auctions_view_sql = 
  "CREATE OR REPLACE VIEW recommended_auctions AS
  SELECT A.auctionId
  FROM Auction as A, Bid as B
  WHERE bidderId IN 
    (SELECT bidderId FROM related_bidders)";
  query_database($recommended_auctions_view_sql);

  $recommended_auctions_query = 
  "SELECT A1.auctionId, A1.itemName, A1.itemDescription, SQ.maxBidPrice, SQ.numBids, A1.endDate
  FROM Auction as A1,
      (
      SELECT B.auctionId, MAX(bidPrice) as maxBidPrice, COUNT(DISTINCT B.id) as numBids
      FROM Auction as A2, Bid as B
      GROUP BY B.auctionId
      ) as SQ
  WHERE A1.auctionId = SQ.auctionId
  AND A1.auctionId IN 
      (
      SELECT * 
      FROM recommended_auctions
      )
  ";

  // Add keyword filter to SQL query
  $recommended_auctions_query .= " AND (itemName LIKE '%{$keyword}%' OR itemDescription LIKE '%{$keyword}%')";
  // Add category filter to SQL query
  if ($cat != 'all') {
    $recommended_auctions_query .= " AND (itemCat = '{$cat}')";
  }
  // Add ordering filter to SQL query
  switch ($ordering) {
    case 'pricelow': 
      $recommended_auctions_query .= ' ORDER BY maxBidPrice ASC';
      break;
    case 'pricehigh':
      $recommended_auctions_query .= ' ORDER BY maxBidPrice DESC';
      break;
    case 'datesoon':
      $recommended_auctions_query .= ' ORDER BY A1.endDate ASC';
      break;
    case 'datelate':
      $recommended_auctions_query .= ' ORDER BY A1.endDate DESC';
      break;
  }
  
  // Add pagination filter to SQL query
  $num_results = mysqli_num_rows(query_database($recommended_auctions_query));
  $results_per_page = 10;
  $max_page = ceil($num_results / $results_per_page);
  $page_start_index = ($curr_page-1) * $results_per_page;  
  $recommended_auctions_query .= " LIMIT " . $page_start_index . ',' . $results_per_page;
  
  // Execute Final Query
  // print_h3($recommended_auctions_query);
  $recommended_auctions = query_database($recommended_auctions_query);

?>

<div class="container mt-5">

<!-- TODO: If result set is empty, print an informative message. Otherwise... -->

<!-- ------------------ DISPLAY FOUND AUCTIONS ------------------- -->
<ul class="list-group">
<?php
  // print like this - print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);
  while($row = mysqli_fetch_array($recommended_auctions)) {
    $num_bids = get_num_bid_by_auction($row['auctionId']);
    $highest_bid = get_max_bid_price_by_auction($row['auctionId']);
    print_listing_li($row['auctionId'], $row['itemName'], $row['itemDescription'], $highest_bid, $num_bids, $row['endDate']);
  } 
?>
</ul>

<!-- ------------------- PAGINATION UI ------------------- -->
<nav aria-label="Search results pages" class="mt-5">
  <ul class="pagination justify-content-center">
  
<?php
  // Copy any currently-set GET variables to the URL.
  $querystring = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page") {
      $querystring .= "$key=$value&amp;";
    }
  }
  
  $high_page_boost = max(3 - $curr_page, 0);
  $low_page_boost = max(2 - ($max_page - $curr_page), 0);
  $low_page = max(1, $curr_page - 2 - $low_page_boost);
  $high_page = min($max_page, $curr_page + 2 + $high_page_boost);
  
  // If not first page, show left and right button options 
  if ($curr_page != 1) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
  }
  
  for ($i = $low_page; $i <= $high_page; $i++) {
    if ($i == $curr_page) {
      // Highlight the link
      echo('
    <li class="page-item active">');
    }
    else {
      // Non-highlighted link
      echo('
    <li class="page-item">');
    }
    
    // Do this in any case
    echo('
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
  }
  
  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
?>

  </ul>
</nav>


</div>



<?php include_once("footer.php")?>