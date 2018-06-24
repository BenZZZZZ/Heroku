-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 24, 2018 at 01:26 PM
-- Server version: 5.5.55-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `krds`
--

-- --------------------------------------------------------

--
-- Table structure for table `libraries`
--

CREATE TABLE IF NOT EXISTS `libraries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `isbn` int(100) DEFAULT NULL,
  `description` text,
  `type` int(11) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `libraries`
--

INSERT INTO `libraries` (`id`, `name`, `isbn`, `description`, `type`, `cid`, `cdate`, `mdate`, `status`) VALUES
(1, 'book1', 12345, NULL, 1, 1, '2018-06-24 00:00:00', '2018-06-24 00:00:00', 1),
(2, 'book2', 12346, NULL, 1, 1, '2018-06-24 00:00:00', '2018-06-24 00:00:00', 1),
(3, 'Video1', 0, 'fsafsd', 2, 15, '2018-06-24 11:01:21', '2018-06-24 11:01:21', 1),
(4, 'Game1', 0, 'fsadsfscadsd', 3, 15, '2018-06-24 11:01:51', '2018-06-24 11:01:51', 1),
(8, 'test', 0, 'fsdasdfsa', 2, 15, '2018-06-24 12:50:34', '2018-06-24 12:58:50', 1),
(9, 'test111', 0, 'sfdfsdfs', 3, 15, '2018-06-24 12:58:11', '2018-06-24 12:59:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mylib`
--

CREATE TABLE IF NOT EXISTS `mylib` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) DEFAULT NULL,
  `libraries_id` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `mylib`
--

INSERT INTO `mylib` (`id`, `users_id`, `libraries_id`, `cdate`, `mdate`, `status`) VALUES
(12, 15, 1, '2018-06-24 13:22:51', '2018-06-24 13:22:51', 1),
(13, 15, 2, '2018-06-24 13:22:54', '2018-06-24 13:22:54', 1),
(14, 15, 4, '2018-06-24 13:22:58', '2018-06-24 13:22:58', 1),
(15, 15, 3, '2018-06-24 13:23:01', '2018-06-24 13:23:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `perms`
--

CREATE TABLE IF NOT EXISTS `perms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `perms`
--

INSERT INTO `perms` (`id`, `name`, `code`, `cdate`, `mdate`, `status`) VALUES
(1, 'Admin', 'admin', '2018-03-13 16:19:04', '2018-03-13 16:19:04', 1),
(2, 'Super Admin', 'superadmin', '2018-03-08 14:00:57', '2018-03-08 14:00:57', 1),
(3, 'User', 'user', '2018-03-08 14:01:07', '2018-03-08 14:01:07', 1),
(4, 'Super User', 'superuser', '2018-03-08 14:01:18', '2018-03-08 14:01:18', 1),
(5, 'Customer', 'customer', '2018-03-08 14:01:37', '2018-03-08 14:01:37', 1);

-- --------------------------------------------------------

--
-- Table structure for table `permscat`
--

CREATE TABLE IF NOT EXISTS `permscat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `permscat`
--

INSERT INTO `permscat` (`id`, `name`, `cdate`, `mdate`, `status`) VALUES
(1, 'Admin', '2018-03-02 19:43:24', '2018-03-02 19:43:24', 1),
(2, 'User', '2018-03-02 19:43:37', '2018-03-02 19:43:37', 1),
(3, 'Super Admin', '2018-03-08 14:36:01', '2018-03-08 14:36:01', 1),
(4, 'Super User', '2018-03-08 14:36:10', '2018-03-08 14:36:10', 1),
(5, 'Customer', '2018-03-08 14:36:20', '2018-03-08 14:36:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roleperms`
--

CREATE TABLE IF NOT EXISTS `roleperms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roles_id` varchar(100) DEFAULT NULL,
  `perms_id` varchar(100) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `roleperms`
--

INSERT INTO `roleperms` (`id`, `roles_id`, `perms_id`, `cdate`, `mdate`, `status`) VALUES
(1, '1', '3', '2018-03-13 16:20:23', '2018-03-13 16:20:23', 1),
(2, '1', '4', '2018-03-13 16:20:23', '2018-03-13 16:20:23', 1),
(3, '2', '1', '2018-03-13 16:20:37', '2018-03-13 16:20:37', 1),
(4, '2', '2', '2018-03-13 16:20:37', '2018-03-13 16:20:37', 1),
(5, '2', '3', '2018-03-13 16:20:37', '2018-03-13 16:20:37', 1),
(6, '2', '4', '2018-03-13 16:20:37', '2018-03-13 16:20:37', 1),
(7, '2', '5', '2018-03-13 16:20:37', '2018-03-13 16:20:37', 1),
(8, '3', '1', '2018-03-13 16:20:56', '2018-03-13 16:20:56', 1),
(9, '3', '3', '2018-03-13 16:20:56', '2018-03-13 16:20:56', 1),
(10, '4', '3', '2018-03-13 16:21:02', '2018-03-13 16:21:02', 1),
(11, '5', '5', '2018-03-13 16:21:07', '2018-03-13 16:21:07', 1),
(19, '1', '5', '2018-06-23 22:33:31', '2018-06-23 22:33:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `code`, `cdate`, `mdate`, `status`) VALUES
(1, 'Super User', 'superuser', '2018-03-26 19:58:04', '2018-03-26 19:58:04', 1),
(2, 'Super Admin', 'superadmin', '2018-03-02 19:28:24', '2018-03-02 19:28:24', 1),
(3, 'Admin', 'admin', '2018-03-02 19:28:37', '2018-03-02 19:28:37', 1),
(4, 'User', 'user', '2018-03-02 19:28:52', '2018-03-02 19:28:52', 1),
(5, 'Customer', 'customer', '2018-03-02 19:29:02', '2018-03-02 19:29:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `userperms`
--

CREATE TABLE IF NOT EXISTS `userperms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` varchar(100) DEFAULT NULL,
  `perms_id` varchar(100) DEFAULT NULL,
  `addrm` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `userroles`
--

CREATE TABLE IF NOT EXISTS `userroles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` varchar(100) DEFAULT NULL,
  `roles_id` varchar(100) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `userroles`
--

INSERT INTO `userroles` (`id`, `users_id`, `roles_id`, `cdate`, `mdate`, `status`) VALUES
(9, '8', '1', '2018-03-15 15:15:21', '2018-03-15 15:15:21', 1),
(10, '8', '2', '2018-03-15 15:15:21', '2018-03-15 15:15:21', 1),
(11, '8', '3', '2018-03-15 15:15:21', '2018-03-15 15:15:21', 1),
(12, '8', '4', '2018-03-15 15:15:21', '2018-03-15 15:15:21', 1),
(13, '8', '5', '2018-03-15 15:15:21', '2018-03-15 15:15:21', 1),
(29, '1', '4', '2018-06-23 22:32:53', '2018-06-23 22:32:53', 1),
(30, '2', '3', '2018-06-23 22:33:03', '2018-06-23 22:33:03', 1),
(31, '2', '4', '2018-06-23 22:33:03', '2018-06-23 22:33:03', 1),
(32, '5', '3', '2018-06-23 22:33:15', '2018-06-23 22:33:15', 1),
(33, '5', '4', '2018-06-23 22:33:16', '2018-06-23 22:33:16', 1),
(34, '15', '4', '2018-06-24 09:04:38', '2018-06-24 09:04:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varbinary(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varbinary(100) DEFAULT NULL,
  `pwd` varbinary(100) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `fbid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `pwd`, `cdate`, `mdate`, `status`, `fbid`) VALUES
(1, 'user', '9894422909', 'user@gmail.com', 'user', '2018-03-04 09:08:10', '2018-03-04 09:08:10', 1, NULL),
(2, 'admin', '98944229091', 'admin@gmail.com', 'admin', '2018-03-02 17:52:47', '2018-03-02 17:52:47', 1, NULL),
(5, 'user1', '98944229092', 'user1@gmail.com', 'user1', '2018-01-29 20:27:50', '2018-01-29 20:27:50', 1, NULL),
(6, 'user3', '98944229093', 'user3@gmail.com', 'user3', '2018-02-02 19:07:33', '2018-02-02 19:07:33', 1, NULL),
(7, 'superuser', '7864948373', 'superuser@gmail.com', 'superuser', '2018-03-05 19:47:05', '2018-03-05 19:47:05', 1, NULL),
(8, 'Super Admin', '7578474636', 'superadmin@gmail.com', 'superadmin', '2018-03-19 19:34:25', '2018-03-19 19:34:25', 1, NULL),
(9, 'customer', '7647878474', 'customer@gmail.com', 'customer', '2018-03-08 13:59:57', '2018-03-08 13:59:57', 1, NULL),
(15, 'Ben Samuel', NULL, 'bensamuel87@gmail.com', NULL, '2018-06-24 09:04:38', '2018-06-24 09:04:38', 1, '10156360644122970');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
