-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 03, 2014 at 01:41 PM
-- Server version: 5.5.38
-- PHP Version: 5.3.10-1ubuntu3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `testnet`
--

-- --------------------------------------------------------

--
-- Table structure for table `watch_incoming`
-- A temporary storage area used while processing transactions. Each transaction's data should be deleted after recording
-- the transaction in the archived_deposits table and recorded on the shared server
-- If a transaction record remains here it needs investigation as to why it failed.

CREATE TABLE IF NOT EXISTS `watch_incoming` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `account` int(10) NOT NULL,
  `address` varchar(34) NOT NULL,
  `amount` decimal(34,29) NOT NULL,
  `category` varchar(10) NOT NULL,
  `confirmations` int(4) NOT NULL,
  `blockhash` varchar(60) NOT NULL,
  `blockindex` int(10) NOT NULL,
  `blocktime` int(12) NOT NULL,
  `txid` varchar(255) NOT NULL,
  `walletconflicts` text NOT NULL,
  `time` int(12) NOT NULL,
  `timereceived` int(12) NOT NULL,
  `flag` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;
