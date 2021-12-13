<?php  
foreach (glob("../includes/*.php") as $filename) {
    require_once($filename);}
?>

<?php
    $sql = "SELECT * FROM User INNER JOIN 
        (SELECT * FROM Auction WHERE Auction.status = 1 
        AND Auction.endDate <= CURRENT_DATE) ExpiredAuction 
        on User.userId = ExpiredAuction.sellerId;"; 
    $expired_auctions = query_database($sql)->fetch_all(MYSQLI_ASSOC);
    $mailer = DefaultMailer::get_mailer();
    foreach($expired_auctions as $auction){
        $subject = 'Auction expiration';

        $sql = "SELECT * FROM Bid INNER JOIN 
                (SELECT * FROM Auction WHERE auctionId = ?) Auction
                ON Bid.auctionId = Auction.auctionId ORDER BY bidPrice DESC";
        $res = prepare_bind_excecute($sql, 'i', $auction['auctionId']);
        if($row = $res->fetch_assoc()){ # Row with highest bid
            $seller_name = $auction['lastName'];
            $seller_email = $auction['email'];
            if($row and $row['bidPrice'] > $row['reservePrice']){
                $sql = "INSERT INTO `Finished Auction` 
                        (`auctionId`, `winnerId`, `bidPrice`) 
                        VALUES (?, ?, ?)";
                prepare_bind_excecute($sql, 'isi', $row['auctionId'], $row['bidderId'], $row['bidPrice']);

                $msg  = 'Hello, '. $seller_name . '\n';
                $msg .= 'Your auction ' . $auction['title'] . ' is ended.\n';
                $msg .= 'Winner is.' . $ .'\n';
        }else{ # Deal!
            $msg  = 'Hello, '. $seller_name . '\n';
            $msg .= 'Your auction ' . $auction['title'] . ' is ended.\n';
            $msg .= 'No deal is made.\n';
            $mailer->send($seller_email, $seller_name, $subject, $msg);
        }}

        # email failed bidders
        while($row = $res->fetch_assoc()){
            #email
        }
    }

    # close expired auctions
    $sql = "UPDATE Auction SET status = 0 WHERE status = 1 AND endDate <= CURRENT_DATE";
    query_database($sql);
?>