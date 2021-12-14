<?php 
    foreach (glob("includes/*.php") as $filename) {
        require_once($filename);}
?>

<?php
    # Use email to notify user now, but can also impl using front-end.
    $DEBUG = true;
    $mailer = Mailer::get_mailer($develop = $DEBUG);
    $subject = "Auction expiration";

    # select expired auction join seller info
    $sql = "SELECT * FROM User INNER JOIN 
            (SELECT * FROM Auction WHERE Auction.status = 1 
            AND Auction.endDate <= CURRENT_DATE) ExpiredAuction 
            on User.userId = ExpiredAuction.sellerId;"; 

    $expired_auctions = query_database($sql)->fetch_all(MYSQLI_ASSOC);
    foreach($expired_auctions as $auction){
        $informed_users_map = array(); # user can raise muliple bids to the same auction, but do not inform user twice.
        $seller_name = $auction["lastName"];
        $seller_email = $auction["email"];

        # select bid ranking join bidder profile related to the expired auction
        $sql = "SELECT bidderId, bidPrice, firstName, lastName, email, type FROM
                (SELECT Bid.auctionId, bidderId, bidPrice FROM Bid 
                INNER JOIN 
                (SELECT Auction.auctionId FROM Auction where Auction.auctionId = ?) Auc
                ON Bid.auctionId = Auc.auctionId 
                ORDER BY bidPrice DESC) BidAuction
                INNER JOIN 
                User 
                on User.userId = BidAuction.bidderId";
        $res = prepare_bind_excecute($sql, "i", $auction["auctionId"]);

        # The first row, ie. with the highest bid. Verify award, then send notifiactions.
        if($row = $res->fetch_assoc()){ 
            if($row and $row["bidPrice"] > $auction["reservePrice"]){ # a bid succeeds, award.
                $winner_name = $row["lastName"];
                $winner_email = $row["email"];
                $sql = "INSERT INTO `Finished Auction` 
                        (`auctionId`, `winnerId`, `bidPrice`) 
                        VALUES (?, ?, ?)";
                if(!$DEBUG){
                    prepare_bind_excecute($sql, "isi", $auction["auctionId"], $row["bidderId"], $row["bidPrice"]);
                }

                $msg  = "Dear seller ". $seller_name . ",\n";
                $msg .= "Auction " . $auction["title"] . " is ended.\n";
                $msg .= "Buyer " . $winner_name . " wins with price " . $row["bidPrice"] . "\n";
                $mailer->send($seller_email, $seller_name, $subject, $msg);

                $msg  = "Dear buyer ". $winner_name . ",\n";
                $msg .= "Auction " . $auction["title"] . " is ended.\n";
                $msg .= "You wins with price " . $row["bidPrice"] ."\n";
                $mailer->send($winner_email, $winner_name, $subject, $msg);
                array_push($informed_users_map, $winner_name);

            }else{ # no bid succeed.
                $msg  = "Dear seller ". $seller_name . ",\n";
                $msg .= "Auction " . $auction["title"] . " is ended.\n";
                $msg .= "No deal is made.\n";
                $mailer->send($seller_email, $seller_name, $subject, $msg);
            }}

        # The other row, ie. Other bidders fail, notify.
        while($row = $res->fetch_assoc()){
            $bidder_name = $row["lastName"];
            $bidder_email = $row["email"];
            if(in_array($bidder_name, $informed_users_map)){
                continue;
            }
            $msg  = "Dear buyer ". $bidder_name . ",\n";
            $msg .= "Auction " . $auction["title"] . " is ended.\n";
            $msg .= "You failed.\n";
            $mailer->send($seller_email, $seller_name, $subject, $msg);
            array_push($informed_users_map, $bidder_name);
        }
    }

    # close expired auctions
    if(!$DEBUG){
        $sql = "UPDATE Auction SET status = 0 WHERE status = 1 AND endDate <= CURRENT_DATE";
        query_database($sql);
    }
?>