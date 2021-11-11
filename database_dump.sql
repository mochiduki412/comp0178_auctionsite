CREATE TABLE `Auction` (
  `auctionId` int(255) NOT NULL AUTO_INCREMENT,
  `sellerId` int(255) NOT NULL,
  `reservePrice` int(255) NOT NULL,
  `startingPrice` int(255) NOT NULL,
  `itemDescription` varchar(1000) NOT NULL,
  `itemCat` varchar(500) NOT NULL,
  `endDate` datetime NOT NULL,
  PRIMARY KEY (auctionId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Buyer` (
  `bidId` varchar(200) NOT NULL,
  `catOfPrevBids` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Finished Auction` (
  `winnerId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `User` (
  `userId` int(255) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(20) NOT NULL,
  PRIMARY KEY (userId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
