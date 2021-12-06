-- phpMyAdmin SQL Dump
-- version 5.1.1deb3+bionic1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 06, 2021 at 12:13 PM
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
  `curBidPrice` int(11) UNSIGNED DEFAULT NULL,
  `curBidderId` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL
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
-- Table structure for table `Finished Auction`
--

CREATE TABLE `Finished Auction` (
  `id` int(11) UNSIGNED NOT NULL,
  `auctionId` int(11) NOT NULL,
  `winnerId` varchar(30) NOT NULL
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Auction`
--
ALTER TABLE `Auction`
  ADD PRIMARY KEY (`auctionId`),
  ADD KEY `sellerId` (`sellerId`),
  ADD KEY `fk_curBidderId_User_userId` (`curBidderId`);

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
  ADD CONSTRAINT `Auction_ibfk_1` FOREIGN KEY (`sellerId`) REFERENCES `User` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_curBidderId_User_userId` FOREIGN KEY (`curBidderId`) REFERENCES `User` (`userId`);

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