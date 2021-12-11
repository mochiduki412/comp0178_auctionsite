<?php  
foreach (glob("../includes/*.php") as $filename) {
  require_once($filename);}
?>

<?php
  $sql = "SELECT * FROM Auction WHERE Auction.status = 1 AND Auction.endDate <= CURRENT_DATE";   # get just expired auctions
  $expired_auctions = query_database($sql)->fetch_all(MYSQLI_ASSOC);
  foreach($expired_auctions as $ea){
    $sql = "SELECT * FROM Bid INNER JOIN 
            (SELECT * FROM Auction WHERE auctionId = ?) Auction
            ON Bid.auctionId = Auction.auctionId ORDER BY bidPrice DESC";

    $res = prepare_bind_excecute($sql, 'i', $ea['auctionId']);
    if($row = $res->fetch_assoc()){ # Row with highest bid
      if(!$row or $row['bidPrice'] <= $row['reservePrice']){
        # No bid or low bid, email seller

      } else{ # Deal!
        $sql = "INSERT INTO `Finished Auction` (`auctionId`, `winnerId`, `bidPrice`) VALUES (?, ?, ?)";
        prepare_bind_excecute($sql, 'isi', $row['auctionId'], $row['bidderId'], $row['bidPrice']);
        # email winner of results

      }
    }
    
    # email failed bidders
    while($row = $res->fetch_assoc()){
      #email
    }
  }

  # close expired auctions
  $sql = "UPDATE Auction SET status = 0 WHERE status = 1 AND endDate <= CURRENT_DATE";
  query_database($sql);
?>