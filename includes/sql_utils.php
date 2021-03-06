<?php
    // ===== SQL helpers =====
    function get_max_bid_price_by_auction($auctionId){
        static $sql = "SELECT MAX(bidPrice) FROM `Bid` WHERE `auctionId`= ?";
        $res = prepare_bind_excecute($sql, 'i', $auctionId);
        return (int) $res->fetch_row()[0];
    }

    function get_max_bid_info_by_auction($auctionId){
        // may be faster to use "ORDER BY * LIMIT 1" instead;
        static $sql = "SELECT * FROM `Bid` WHERE bidPrice = (SELECT MAX(bidPrice) FROM Bid WHERE auctionId = ?)";
        $res = prepare_bind_excecute($sql, 'i', $auctionId);
        return $res;
    }

    function get_bids_by_auction($auctionId){
        static $sql = "SELECT * FROM `Bid` WHERE `auctionId`=? ORDER BY bidPrice DESC, createdDate";
        return prepare_bind_excecute($sql, 'i', $auctionId);
    }

    function get_bids_by_user($user_id){
        static $sql = "SELECT * FROM `Bid` LEFT JOIN `Auction` ON Bid.auctionId = Auction.auctionId WHERE `bidderId` = ?";
        return prepare_bind_excecute($sql, 's', $user_id);
    }

    function get_num_bid_by_auction($auctionId){
        static $sql = "SELECT COUNT(*) FROM `Bid` WHERE auctionId = ?";
        return (int) prepare_bind_excecute($sql, 'i', $auctionId)->fetch_row()[0];
    }

    function set_auction_inactive($auctionId){
        static $INACTIVE = 0;
        static $sql = 'UPDATE Auction SET status = ? WHERE auctionId = ?';
        return prepare_bind_excecute($sql, 'ii', $INACTIVE, $auctionId);
    }

    function get_new_expired_auctions(){
        static $sql = "SELECT * FROM Auction WHERE status = 1 AND endDate <= CURRENT_DATE";
        return prepare_bind_excecute($sql, '');
    }

    function get_new_expired_auctions_join_bids(){
        static $sql = 
            "
            SELECT * FROM Bid INNER JOIN 
            (SELECT * FROM Auction WHERE Auction.status = 1 
            AND Auction.endDate <= CURRENT_DATE) AuctionExpired 
            ON Bid.auctionId = AuctionExpired.auctionId
            ";
        return prepare_bind_excecute($sql, '');
    }

    function get_item_categories(){
        static $sql = 'SELECT DISTINCT itemCat FROM Auction';
        return query_database($sql);
    }

    function get_name_by_user_id($user_id){
        static $sql = 'SELECT firstName, lastName FROM `User` WHERE User.userId = ?';
        $res = prepare_bind_excecute($sql, 's', $user_id)->fetch_assoc();
        $name = $res['firstName'] . ' ' . $res['lastName'];
        return $name;
    }
?>

    