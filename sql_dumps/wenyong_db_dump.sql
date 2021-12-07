-- phpMyAdmin SQL Dump
-- version 5.1.1deb3+bionic1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 07, 2021 at 10:59 PM
-- Server version: 5.7.33-0ubuntu0.18.04.1
-- PHP Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `databasecw`
--

-- --------------------------------------------------------

--
-- Table structure for table `Auction`
--

CREATE TABLE `Auction` (
  `auctionId` int(11) NOT NULL,
  `sellerId` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `reservePrice` int(11) UNSIGNED NOT NULL,
  `startingPrice` int(11) UNSIGNED NOT NULL,
  `itemDescription` text NOT NULL,
  `itemCat` varchar(127) NOT NULL,
  `endDate` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Bid`
--

CREATE TABLE `Bid` (
  `id` int(11) NOT NULL,
  `auctionId` int(11) NOT NULL,
  `bidderId` varchar(31) CHARACTER SET utf8 NOT NULL,
  `bidPrice` int(11) NOT NULL,
  `createdDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Buyer`
--

CREATE TABLE `Buyer` (
  `bidId` varchar(200) NOT NULL,
  `catOfPrevBids` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `ExpiredAuctionRewardInfo`
-- (See below for the actual view)
--
CREATE TABLE `ExpiredAuctionRewardInfo` (
`id` int(11)
,`auctionId` int(11)
,`bidderId` varchar(31)
,`bidPrice` int(11)
,`endDate` date
);

-- --------------------------------------------------------

--
-- Table structure for table `Finished Auction`
--

CREATE TABLE `Finished Auction` (
  `id` int(11) UNSIGNED NOT NULL,
  `auctionId` int(11) NOT NULL,
  `winnerId` varchar(30) NOT NULL,
  `bidPrice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `userId` varchar(50) NOT NULL,
  `firstName` varchar(30) DEFAULT NULL,
  `lastName` varchar(30) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure for view `ExpiredAuctionRewardInfo`
--
DROP TABLE IF EXISTS `ExpiredAuctionRewardInfo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ExpiredAuctionRewardInfo`  AS   (select `Bid`.`id` AS `id`,`Auction`.`auctionId` AS `auctionId`,`Bid`.`bidderId` AS `bidderId`,`Bid`.`bidPrice` AS `bidPrice`,`Auction`.`endDate` AS `endDate` from (`Bid` join `Auction` on((`Bid`.`auctionId` = `Auction`.`auctionId`))) where (`Auction`.`endDate` <= curdate()))  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Auction`
--
ALTER TABLE `Auction`
  ADD PRIMARY KEY (`auctionId`),
  ADD KEY `sellerId` (`sellerId`);

--
-- Indexes for table `Bid`
--
ALTER TABLE `Bid`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bidderId_User_userId` (`bidderId`),
  ADD KEY `fk_auctionId_Auction_auctionId` (`auctionId`);

--
-- Indexes for table `Finished Auction`
--
ALTER TABLE `Finished Auction`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `auctionId` (`auctionId`),
  ADD KEY `fk_FinishedAuction_auctionId_Auction_auctionId` (`auctionId`),
  ADD KEY `fk_FinishedAuction_winnerId_User_userId` (`winnerId`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Auction`
--
ALTER TABLE `Auction`
  MODIFY `auctionId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Bid`
--
ALTER TABLE `Bid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Finished Auction`
--
ALTER TABLE `Finished Auction`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Auction`
--
ALTER TABLE `Auction`
  ADD CONSTRAINT `Auction_ibfk_1` FOREIGN KEY (`sellerId`) REFERENCES `User` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Bid`
--
ALTER TABLE `Bid`
  ADD CONSTRAINT `fk_auctionId_Auction_auctionId` FOREIGN KEY (`auctionId`) REFERENCES `Auction` (`auctionId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bidderId_User_userId` FOREIGN KEY (`bidderId`) REFERENCES `User` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Finished Auction`
--
ALTER TABLE `Finished Auction`
  ADD CONSTRAINT `fk_FinishedAuction_auctionId_Auction_auctionId` FOREIGN KEY (`auctionId`) REFERENCES `Auction` (`auctionId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_FinishedAuction_winnerId_User_userId` FOREIGN KEY (`winnerId`) REFERENCES `User` (`userId`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;