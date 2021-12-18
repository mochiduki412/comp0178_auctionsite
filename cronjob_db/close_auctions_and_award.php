<?php  
  foreach (glob("includes/*.php") as $filename) {
    require_once($filename);}
?>

<?php
    # DO NOT SERVE THIS ON WEB FOLDER
    # Update just expired auction info.
    # Have to manually cron it.
    # Maybe better to impl it majorly in PHP so that more maintainable.
    $sql = 
    "
    START TRANSACTION;

    INSERT INTO `Finished Auction` (auctionId, winnerId, bidPrice) 
    SELECT Bid.auctionId, bidderId, MAX(bidPrice) FROM Bid 
    INNER JOIN 
    (SELECT * FROM Auction WHERE Auction.status = 1 AND Auction.endDate <= CURRENT_DATE) AuctionExpired 
    ON Bid.auctionId = AuctionExpired.auctionId 
    WHERE bidPrice > reservePrice GROUP BY Bid.auctionId, bidderId;

    COMMIT;
    ";
    prepare_bind_excecute($sql, '');
?>