<?php
    // ===== SQL helpers =====
    function get_max_bid_price_by_auction($auctionId){
        static $sql = "SELECT MAX(bidPrice) FROM `Bid` WHERE `auctionId`= ?";
        $res = prepare_bind_excecute($sql, 'i', $auctionId);
        return $res;
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

    function get_item_categories(){
        static $sql = 'SELECT DISTINCT itemCat FROM Auction';
        return query_database($sql);
    }
?>