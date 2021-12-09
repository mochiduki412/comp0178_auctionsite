-- phpMyAdmin SQL Dump
-- version 5.1.1deb3+bionic1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 09, 2021 at 08:04 PM
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
  `itemName` varchar(255) NOT NULL,
  `reservePrice` int(11) UNSIGNED NOT NULL,
  `startingPrice` int(11) UNSIGNED NOT NULL,
  `itemDescription` text NOT NULL,
  `itemCat` varchar(127) NOT NULL,
  `endDate` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Auction`
--

INSERT INTO `Auction` (`auctionId`, `sellerId`, `itemName`, `reservePrice`, `startingPrice`, `itemDescription`, `itemCat`, `endDate`, `status`) VALUES
(193, '61a751bc08292', 'fake_Honda', 201, 17, 'Hot near reason day west.', 'Convertible, Coupe', '2021-03-24', 1),
(194, '61a3a51e58f52', 'fake_Mercury', 392, 18, 'Act military when.', 'SUV', '2022-11-07', 1),
(195, '61a3a4aba0c3a', 'fake_Nissan', 245, 81, 'Else physical order.', 'Pickup', '2022-11-09', 1),
(196, '61a751bc08292', 'fake_Ford', 376, 35, 'Material right think single leader happy both.', 'Van/Minivan', '2022-05-17', 1),
(197, '61a7506728d11', 'fake_Nissan', 340, 31, 'Public before contain structure.', 'SUV', '2022-09-28', 1),
(198, '61a7506728d11', 'fake_Plymouth', 332, 91, 'News green guess per their mention least quickly.', 'SUV', '2021-07-25', 1),
(199, '61a3a4aba0c3a', 'fake_GMC', 312, 30, 'Network stuff discover.', 'Pickup', '2021-05-02', 1),
(200, '61a7506728d11', 'fake_Chevrolet', 310, 50, 'Bed care drug. Quickly hold manager chair this.', 'Sedan', '2021-09-09', 1),
(201, '61a3a4aba0c3a', 'fake_Hyundai', 310, 66, 'How social suggest. Others keep hold off show in.', 'Coupe', '2022-06-14', 1),
(202, '61a7506728d11', 'fake_Chevrolet', 279, 97, 'Then season vote I organization young white.', 'Coupe, Convertible', '2022-08-23', 1),
(203, '61a3a4aba0c3a', 'fake_Mercury', 287, 81, 'Unit another however hair.', 'Sedan', '2022-03-27', 1),
(204, '61a7506728d11', 'fake_Oldsmobile', 286, 63, 'Effect war pretty time agree lot form.', 'SUV', '2021-09-03', 1),
(205, '61a751bc08292', 'fake_Chevrolet', 327, 96, 'Trade wear line.', 'Sedan', '2021-07-09', 1),
(206, '61a3a51e58f52', 'fake_GMC', 270, 63, 'Bar community produce coach wear action.', 'Wagon', '2021-05-14', 1),
(207, '61a3a4aba0c3a', 'fake_Subaru', 338, 100, 'Everybody whole behavior similar defense.', 'Sedan', '2021-07-31', 1),
(208, '61a3a4aba0c3a', 'fake_Buick', 254, 94, 'Fund bed create body know ok strong.', 'Sedan, Hatchback', '2022-02-10', 1),
(209, '61a751bc08292', 'fake_Cadillac', 264, 23, 'Reach affect available industry.', 'Pickup', '2022-04-29', 1),
(210, '61a7506728d11', 'fake_Dodge', 302, 100, 'Worry positive return material.', 'SUV', '2021-11-23', 1),
(211, '61a3a4aba0c3a', 'fake_Volkswagen', 371, 100, 'Green across reflect foreign prove country.', 'Sedan', '2021-07-23', 1),
(212, '61a751bc08292', 'fake_Saturn', 303, 94, 'This voice operation second event.', 'Sedan', '2021-05-05', 1);

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

--
-- Dumping data for table `Bid`
--

INSERT INTO `Bid` (`id`, `auctionId`, `bidderId`, `bidPrice`, `createdDate`) VALUES
(225, 208, '61a7506728d11', 435, '2021-12-09 12:02:16'),
(226, 205, '61a3a4aba0c3a', 291, '2021-12-09 12:02:16'),
(227, 194, '61a3a4aba0c3a', 392, '2021-12-09 12:02:16'),
(228, 207, '61a3a4aba0c3a', 285, '2021-12-09 12:02:16'),
(229, 206, '61a3a4aba0c3a', 219, '2021-12-09 12:02:16'),
(230, 193, '61a3a4aba0c3a', 453, '2021-12-09 12:02:16'),
(231, 198, '61a7506728d11', 203, '2021-12-09 12:02:16'),
(232, 194, '61a7506728d11', 396, '2021-12-09 12:02:16'),
(233, 211, '61a751bc08292', 290, '2021-12-09 12:02:16'),
(234, 212, '61a3a4aba0c3a', 324, '2021-12-09 12:02:16'),
(235, 193, '61a3a51e58f52', 372, '2021-12-09 12:02:16'),
(236, 209, '61a7506728d11', 440, '2021-12-09 12:02:17'),
(237, 209, '61a751bc08292', 379, '2021-12-09 12:02:17'),
(238, 200, '61a7506728d11', 439, '2021-12-09 12:02:17'),
(239, 208, '61a7506728d11', 180, '2021-12-09 12:02:17'),
(240, 205, '61a751bc08292', 265, '2021-12-09 12:02:17'),
(241, 200, '61a751bc08292', 499, '2021-12-09 12:02:17'),
(242, 201, '61a3a4aba0c3a', 494, '2021-12-09 12:02:17'),
(243, 210, '61a7506728d11', 358, '2021-12-09 12:02:17'),
(244, 201, '61a7506728d11', 124, '2021-12-09 12:02:17'),
(245, 206, '61a3a4aba0c3a', 100, '2021-12-09 12:02:17'),
(246, 202, '61a751bc08292', 469, '2021-12-09 12:02:17'),
(247, 210, '61a3a4aba0c3a', 207, '2021-12-09 12:02:17'),
(248, 209, '61a7506728d11', 333, '2021-12-09 12:02:17'),
(249, 200, '61a3a4aba0c3a', 263, '2021-12-09 12:02:17'),
(250, 197, '61a751bc08292', 433, '2021-12-09 12:02:17'),
(251, 193, '61a751bc08292', 349, '2021-12-09 12:02:17'),
(252, 197, '61a3a51e58f52', 455, '2021-12-09 12:02:17'),
(253, 205, '61a3a4aba0c3a', 156, '2021-12-09 12:02:17'),
(254, 209, '61a7506728d11', 153, '2021-12-09 12:02:17'),
(255, 201, '61a3a4aba0c3a', 160, '2021-12-09 12:02:17'),
(256, 202, '61a751bc08292', 145, '2021-12-09 12:02:17'),
(257, 204, '61a7506728d11', 485, '2021-12-09 12:02:17'),
(258, 208, '61a3a4aba0c3a', 459, '2021-12-09 12:02:17'),
(259, 198, '61a3a51e58f52', 259, '2021-12-09 12:02:17'),
(260, 203, '61a7506728d11', 139, '2021-12-09 12:02:17'),
(261, 201, '61a3a4aba0c3a', 433, '2021-12-09 12:02:17'),
(262, 209, '61a7506728d11', 229, '2021-12-09 12:02:17'),
(263, 194, '61a3a51e58f52', 387, '2021-12-09 12:02:17'),
(264, 204, '61a7506728d11', 432, '2021-12-09 12:02:17');

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

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`userId`, `firstName`, `lastName`, `email`, `password`, `type`) VALUES
('61a3a4aba0c3a', 'hiyori', 'hayasaka', 'hayasakahiyori@gmail.com', '$2y$10$CiIlhR2UdIc7ZFbaORaQweV8f/XMRNqpbIEN15qjMq7QTALUCD7z.', 'buyer'),
('61a3a4ea1a3b3', 'yuki', 'katase', 'kataseyuki@gmail.com', '$2y$10$NCYix4A2B2z1UMUQtG7IGeA8r8OlaqRhnQ9yMDEBo1kBW3aDJe5Sm', 'seller'),
('61a3a51e58f52', 'satuki', 'shindou', 'shindousatuki@gmail.com', '$2y$10$b4gzX/ICpZgsWvcGPuKGj.TZ20.1bpebc11RbIGsg7jmgBbOPMnWy', 'buyer'),
('61a7506728d11', 'kaede', '', 'kaede@gmail.com', '$2y$10$xzZvPJvh9/WpmGUA3M5HvuhXoeUVKtqgG9XBbaa4HUc1uNtcpjZeG', 'buyer'),
('61a751bc08292', 'hello', 'world', 'test@gmail.com', '$2y$10$T2rwzYyEvZjCmmsTqCe5X.nhPOr94aoUjkQmgad7lATek0TmNNYye', 'buyer');

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
  MODIFY `auctionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;

--
-- AUTO_INCREMENT for table `Bid`
--
ALTER TABLE `Bid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=265;

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