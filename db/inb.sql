-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 17, 2012 at 12:09 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `inb`
--

-- --------------------------------------------------------

--
-- Table structure for table `billitems`
--

DROP TABLE IF EXISTS `billitems`;
CREATE TABLE IF NOT EXISTS `billitems` (
  `billItemID` int(11) NOT NULL AUTO_INCREMENT,
  `billID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `stockID` int(11) DEFAULT NULL,
  `Rate` double DEFAULT '0',
  `individualDiscountPercentage` double DEFAULT '0',
  `Quantity` int(11) DEFAULT '0',
  `LineTotal` double DEFAULT '0',
  `ProductName` varchar(255) NOT NULL,
  PRIMARY KEY (`billItemID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `billitems`
--

INSERT INTO `billitems` (`billItemID`, `billID`, `productID`, `stockID`, `Rate`, `individualDiscountPercentage`, `Quantity`, `LineTotal`, `ProductName`) VALUES
(3, 101, 1, 1, 1700.25, 0, 1, 1700.25, 'Multi-Color Designer Sarees'),
(4, 101, 1, 3, 1400.1, 0, 2, 2800.2, 'Multi-Color Designer Sarees'),
(5, 102, 1, 1, 1700.25, 0, 10, 17002.5, 'Multi-Color Designer Sarees'),
(6, 103, 1, 1, 1700.25, 0, 1, 1700.25, 'Multi-Color Designer Sarees'),
(7, 103, 1, 2, 1300, 0, 24, 31200, 'Multi-Color Designer Sarees');

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

DROP TABLE IF EXISTS `bills`;
CREATE TABLE IF NOT EXISTS `bills` (
  `billID` int(11) NOT NULL AUTO_INCREMENT,
  `customerName` varchar(255) NOT NULL,
  `customerID` int(11) NOT NULL,
  `billDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `rateBeforeTax` double NOT NULL,
  `taxPercentage` double DEFAULT '0',
  `NetAmount` double NOT NULL,
  `DiscountPercentage` double DEFAULT '0',
  `totalAmount` double NOT NULL,
  `returnAmount` double DEFAULT NULL,
  `BillAddress` text,
  `PhoneNumber` varchar(25) NOT NULL,
  `useForTax` tinyint(1) DEFAULT '1',
  `billedBy` int(11) DEFAULT NULL,
  `posSystemIP` varchar(50) DEFAULT NULL,
  `printed` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `archived` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`billID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=104 ;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`billID`, `customerName`, `customerID`, `billDate`, `rateBeforeTax`, `taxPercentage`, `NetAmount`, `DiscountPercentage`, `totalAmount`, `returnAmount`, `BillAddress`, `PhoneNumber`, `useForTax`, `billedBy`, `posSystemIP`, `printed`, `status`, `archived`) VALUES
(101, 'Daniel', 1, '2012-06-22 13:14:20', 4275.43, 4, 4446.45, 5, 4500.45, NULL, '', '9962061539', 1, NULL, NULL, 0, 1, 1),
(102, 'Daniel', 1, '2012-06-22 13:24:44', 16662.45, 4, 17328.95, 2, 17002.5, 0, '', '9962061539', 1, NULL, NULL, 0, 1, 1),
(103, 'Daniel', 1, '2012-06-22 13:34:15', 15102.86, 4, 15706.97, 5, 15897.75, 17002.5, '', '9962061539', 1, 1, NULL, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(200) NOT NULL,
  `status` smallint(6) DEFAULT '1',
  `misc1` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`categoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryID`, `categoryName`, `status`, `misc1`) VALUES
(1, 'Sarees1224', 1, NULL),
(2, 'Shirts', 1, NULL),
(3, 'Sarees', 1, NULL),
(4, 'test', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `customerID` int(11) NOT NULL AUTO_INCREMENT,
  `customerName` varchar(255) NOT NULL,
  `customerPhone1` varchar(15) NOT NULL,
  `customerPhone2` varchar(15) DEFAULT NULL,
  `customerEmail1` varchar(255) DEFAULT NULL,
  `customerEmail2` varchar(255) DEFAULT NULL,
  `postalAddress` text,
  `lastPurchasedOn` datetime DEFAULT NULL,
  `status` smallint(6) DEFAULT '1',
  PRIMARY KEY (`customerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customerID`, `customerName`, `customerPhone1`, `customerPhone2`, `customerEmail1`, `customerEmail2`, `postalAddress`, `lastPurchasedOn`, `status`) VALUES
(1, 'Daniel', '9962061539', '', '', '', '', NULL, 1),
(2, 'Paul', '54623135', '5485226', 'daniel@daniel.com', NULL, NULL, NULL, 0),
(3, 'Kumar', '305468435', NULL, 'kaksdfkl@asdfjl.xcs', 'alsdjals@kole.com', NULL, NULL, 1),
(4, 'Rini esther', '9176061539', '', 'rini@daniepaul.com', '', 'dasd asd asdas d, sad asd ..as dasd asdasdasd', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_extended`
--

DROP TABLE IF EXISTS `customer_extended`;
CREATE TABLE IF NOT EXISTS `customer_extended` (
  `customerID` int(11) NOT NULL,
  `referencedBy` int(11) DEFAULT NULL,
  `customerPreference` text NOT NULL,
  PRIMARY KEY (`customerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `productID` int(11) NOT NULL AUTO_INCREMENT,
  `productName` varchar(500) NOT NULL,
  `Description` text NOT NULL,
  `subCategoryID` int(11) NOT NULL,
  `isCategoryDirect` tinyint(1) DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `uniqueReference` varchar(25) NOT NULL,
  `vendorID` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `addedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`productID`),
  UNIQUE KEY `uniqueReference` (`uniqueReference`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productID`, `productName`, `Description`, `subCategoryID`, `isCategoryDirect`, `image`, `uniqueReference`, `vendorID`, `status`, `addedOn`) VALUES
(1, 'Multi-Color Designer Sarees', '', 1, 0, NULL, 'DS001', 1, 1, '2012-06-19 12:08:27'),
(4, 'Crocodile Shirts', '', 3, 0, NULL, 'FS002', NULL, 1, '2012-06-19 12:11:04'),
(5, 'test', 'askdhaksdh kasdhk asdhas', 5, 0, NULL, 'PR0005', 3, 1, '2012-07-17 12:02:32');

-- --------------------------------------------------------

--
-- Table structure for table `purchasevoucher`
--

DROP TABLE IF EXISTS `purchasevoucher`;
CREATE TABLE IF NOT EXISTS `purchasevoucher` (
  `purchaseID` int(11) NOT NULL AUTO_INCREMENT,
  `vendorID` int(11) NOT NULL,
  `billNo` varchar(150) NOT NULL,
  `totalAmount` double DEFAULT '0',
  `discount` double DEFAULT NULL,
  `taxPercentage` double DEFAULT '0',
  `noOfItems` int(11) DEFAULT '0',
  `arrivedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`purchaseID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `purchasevoucher`
--

INSERT INTO `purchasevoucher` (`purchaseID`, `vendorID`, `billNo`, `totalAmount`, `discount`, `taxPercentage`, `noOfItems`, `arrivedOn`) VALUES
(10, 1, '6586', 12000, 0, 4, 1, '2012-07-16 07:00:10'),
(11, 2, 'BR7839', 54500, 2, 4, 2, '2012-07-16 07:04:53'),
(13, 2, 'sdfzg', 1250, 0, 4, 1, '2012-07-16 08:30:09'),
(14, 3, '51345', 12000, 0, 4, 1, '2012-07-17 12:04:17');

-- --------------------------------------------------------

--
-- Table structure for table `returnbill`
--

DROP TABLE IF EXISTS `returnbill`;
CREATE TABLE IF NOT EXISTS `returnbill` (
  `returnID` int(11) NOT NULL AUTO_INCREMENT,
  `LineItemID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `ReturnedOn` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `returnedBillID` int(11) NOT NULL,
  PRIMARY KEY (`returnID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `returnbill`
--

INSERT INTO `returnbill` (`returnID`, `LineItemID`, `Quantity`, `ReturnedOn`, `returnedBillID`) VALUES
(1, 5, 10, '2012-06-22 13:34:16', 103);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `type` varchar(1) DEFAULT 'C',
  `mandate` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `mandate`) VALUES
(1, 'Company Name', 'Abinaya Garments', 'C', 1),
(3, 'Company Address', 'Balaji Nagar, Velachery, Chennai', 'C', 1),
(4, 'Company TIN', '3434345', 'C', 1),
(5, 'Company PAN', 'sdfsdf345', 'C', 0),
(6, 'Company Email', 'abiniya.garments@gmail.com', 'C', 0),
(7, 'Company Phone', '044-25698563', 'C', 0),
(8, 'Company Phone', '', 'C', 0),
(9, 'Company Fax', '', 'C', 0),
(10, 'VAT', '4', 'F', 0),
(11, 'Service Tax', '', 'F', 0),
(12, 'Max. days for Exchange', '15', 'F', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
CREATE TABLE IF NOT EXISTS `stocks` (
  `stockID` int(11) NOT NULL AUTO_INCREMENT,
  `productID` int(11) NOT NULL,
  `vendorID` int(11) NOT NULL,
  `arrivedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `quantity` int(11) NOT NULL,
  `CPRate` double NOT NULL,
  `purchaseID` int(11) DEFAULT '0',
  `SPRate` double NOT NULL,
  `MaximumDiscountPercentage` double DEFAULT NULL,
  PRIMARY KEY (`stockID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`stockID`, `productID`, `vendorID`, `arrivedOn`, `quantity`, `CPRate`, `purchaseID`, `SPRate`, `MaximumDiscountPercentage`) VALUES
(1, 1, 1, '2012-06-20 10:23:03', 14, 1526.25, 0, 1700.25, NULL),
(2, 1, 1, '2012-06-20 11:04:16', 0, 1256.2, 0, 1300, NULL),
(3, 1, 1, '2012-06-20 11:04:58', 24, 1346.2, 0, 1400.1, NULL),
(4, 4, 1, '2012-07-16 07:00:10', 15, 800, 10, 900, NULL),
(5, 4, 2, '2012-07-16 07:04:53', 10, 700, 11, 850, NULL),
(6, 1, 2, '2012-07-16 07:04:53', 25, 1900, 11, 2105, NULL),
(9, 1, 2, '2012-07-16 08:30:10', 5, 250, 13, 500, NULL),
(10, 5, 3, '2012-07-17 12:04:17', 15, 600, 14, 700, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
CREATE TABLE IF NOT EXISTS `subcategories` (
  `subCategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `subCategoryName` varchar(250) NOT NULL,
  `categoryID` int(11) NOT NULL,
  `status` smallint(6) DEFAULT '1',
  `misc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`subCategoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`subCategoryID`, `subCategoryName`, `categoryID`, `status`, `misc`) VALUES
(1, 'Silk Sarees', 1, 1, NULL),
(2, 'Designer Sarees', 1, 1, NULL),
(3, 'Half Sleeve Shirts', 2, 1, NULL),
(4, 'Full Sleeve Shorts', 2, 1, NULL),
(5, 'General', 4, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(500) DEFAULT NULL,
  `lastLogin` datetime DEFAULT NULL,
  `loginType` varchar(255) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `name`, `username`, `password`, `email`, `lastLogin`, `loginType`, `status`) VALUES
(1, 'Daniel', 'daniepaul', '123', NULL, NULL, 'SUPER', 1),
(2, 'Alex', 'alex', 'alex', '', NULL, 'Vendors-1', 1),
(4, 'test', 'daniepaul@gmail.com', 'asdasd', '', NULL, 'Products-1;Bills-1;Reports-1', 1),
(5, 'Poornima', 'poornima', 'poornima', '', NULL, 'Bills-1', 1),
(6, 'Poornima', 'PoornimaP02', '12345', '', NULL, 'Customers-1;Vendors-1;Products-1;Purchases-1;Bills-1;Reports-1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

DROP TABLE IF EXISTS `vendor`;
CREATE TABLE IF NOT EXISTS `vendor` (
  `vendorID` int(11) NOT NULL AUTO_INCREMENT,
  `vendorName` varchar(500) NOT NULL,
  `vendorPhone1` varchar(25) NOT NULL,
  `vendorPhone2` varchar(25) DEFAULT NULL,
  `vendorEmail1` varchar(255) NOT NULL,
  `vendorEmail2` varchar(255) DEFAULT NULL,
  `postalAddress` text NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`vendorID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`vendorID`, `vendorName`, `vendorPhone1`, `vendorPhone2`, `vendorEmail1`, `vendorEmail2`, `postalAddress`, `status`) VALUES
(1, 'sample textile', '564546131', '', '', '', '', 1),
(2, 'New line garments pvt ltd', '5648965464', '', 'newline@newline.com', 'newline@newline.com', '', 1),
(3, 'Misc', '9896565632', '', '', '', '', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
