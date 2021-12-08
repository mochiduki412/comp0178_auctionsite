-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 30, 2021 at 12:13 PM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Auction DBF`
--

-- --------------------------------------------------------

--
-- Table structure for table `Auction`
--

CREATE TABLE `Auction` (
  `auctionId` int(255) NOT NULL,
  `sellerId` int(255) NOT NULL,
  `reservePrice` int(255) NOT NULL,
  `startingPrice` int(255) NOT NULL,
  `itemName` varchar(1000) NOT NULL,
  `itemDescription` varchar(1000) NOT NULL,
  `itemCat` varchar(500) NOT NULL,
  `endDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Auction`
--

INSERT INTO `Auction` (`auctionId`, `sellerId`, `reservePrice`, `startingPrice`, `itemName`, `itemDescription`, `itemCat`, `endDate`) 
VALUES (1, 1, 100, 50, 'Apple', 'Delicious apple', 'Fruit', '2021-11-24 17:40:58');
INSERT INTO `Auction` (`auctionId`, `sellerId`, `reservePrice`, `startingPrice`, `itemName`, `itemDescription`, `itemCat`, `endDate`) 
VALUES (2, 2, 100, 50, 'Bike', 'Fast bicycle', 'Leisure', '2021-11-24 17:40:58');
INSERT INTO `Auction` (`auctionId`, `sellerId`, `reservePrice`, `startingPrice`, `itemName`, `itemDescription`, `itemCat`, `endDate`) 
VALUES (3, 3, 100, 50, 'Car', 'Expensive BMW', 'Vehicle', '2021-11-24 17:40:58');
INSERT INTO `Auction` (`auctionId`, `sellerId`, `reservePrice`, `startingPrice`, `itemName`, `itemDescription`, `itemCat`, `endDate`) 
VALUES (4, 4, 100, 50, 'Desktop PC', 'Good Computer', 'Electronics', '2021-11-24 17:40:58');
INSERT INTO `Auction` (`auctionId`, `sellerId`, `reservePrice`, `startingPrice`, `itemName`, `itemDescription`, `itemCat`, `endDate`) 
VALUES (5, 5, 100, 50, 'Elephant', 'Wild elephant', 'Animal', '2021-11-24 17:40:58');
INSERT INTO `Auction` (`auctionId`, `sellerId`, `reservePrice`, `startingPrice`, `itemName`, `itemDescription`, `itemCat`, `endDate`) 
VALUES (6, 6, 100, 50, 'Feather', 'Pack of 50 feathers', 'Animal', '2021-11-24 17:40:58');

-- --------------------------------------------------------

--
-- Table structure for table `Bid`
--

CREATE TABLE `Bid` (
  `bidIds` int(255) NOT NULL,
  `bidPrice` int(255) NOT NULL,
  `bidTime` time(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Finished Auction`
--

CREATE TABLE `Finished Auction` (
  `winnerId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `userId` int(255) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `buyerSeller` varchar(10) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Watch`
--

CREATE TABLE `Watch` (
  `watchedItemId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Auction`
--
ALTER TABLE `Auction`
  ADD PRIMARY KEY (`auctionId`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;