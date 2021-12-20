<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php 
    foreach (glob("includes/*.php") as $filename) {
    require_once($filename);
};?>

<div class="container">

<h2 class="my-3">Recommendations for you</h2>

<?php
  // This page is for showing a buyer recommended items based on their bid 
  // history. It will be pretty similar to browse.php, except there is no 
  // search bar. This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  
  
  // TODO: Check user's credentials (cookie/session).
  if(!is_login()){
    redirect("index.php", 'You are not logged in.');
  }
  $user_id = $_SESSION['user'];
  
  // TODO: Perform a query to pull up auctions they might be interested in.

  // Reference sources:
  // https://stackoverflow.com/questions/2440826/collaborative-filtering-in-mysql
  // https://www.codeproject.com/Articles/5300620/Collaborative-Filtering-in-MySQL-A-Tutorial
  // https://en.wikipedia.org/wiki/Collaborative_filtering
  // It ranks similar users placing bids on same auctions first, (1)
  // Then add up common books from these similar users.  (2)
  // This algorithm is naive in terms of both (1) and (2) have ranking weight of 1.
  // I regret that I did not read much on View.
  $sql = "
  create temporary table similar_users 
  select similar.bidderId, count(*) rank
  from Bid target 
  join Bid similar on target.auctionId = similar.auctionId and target.bidderId != similar.bidderId
  where target.bidderId = '61a3a4aba0c3a'
  group by similar.bidderId;
  
  create temporary table similar_auctions
  select SUM(similar_users.rank) total_rank, similar.auctionId
  from similar_users
  join Bid similar on similar_users.bidderId = similar.bidderId 
  left join Bid target on target.bidderId = '61a3a4aba0c3a' and target.auctionId = similar.auctionId
  where target.auctionId is null
  group by similar.auctionId;

  create temporary table similar_auctions_2 as
  SELECT total_rank, similar_auctions.auctionId, itemName, itemDescription, endDate
  FROM similar_auctions
  JOIN Auction ON similar_auctions.auctionId = Auction.auctionId
  order by total_rank desc;
  
  create temporary table Bid_with_cnt_max as
  SELECT id, B1.auctionId, bidderId, bidPrice, createdDate, bidMax, bidCnt FROM
  Bid B1
  JOIN
  (SELECT auctionId, max(bidPrice) as bidMax, COUNT(bidPrice) as bidCnt 
  FROM `Bid` 
  GROUP BY auctionId) B2
  ON B1.auctionId = B2.auctionId AND bidMax = bidPrice;
  
  SELECT total_rank, A.auctionId, itemName, itemDescription, bidMax, bidCnt, endDate FROM
  similar_auctions_2 A
  JOIN
  Bid_with_cnt_max B
  ON A.auctionId = B.auctionId
  ORDER BY total_rank DESC
  ";

  $results = query_database($sql);
  while($row = $results->fetch_assoc()){
    print_listing_li(
      $row['auctionId'], 
      $row["itemName"],
      $row['itemDescription'],
      $row['bidMax'],
      $row['bidCnt'],
      new DateTime($row['endDate'])
    );
  }

  // TODO: Loop through results and print them out as list items.
  
?>