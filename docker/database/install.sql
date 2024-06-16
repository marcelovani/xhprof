-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:33067
-- Generation Time: Nov 19, 2016 at 09:13 PM
-- Server version: 5.5.48-37.8-log
-- PHP Version: 5.3.29

-- SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xhprof`
--

-- --------------------------------------------------------

--
-- Table structure for table `details`
--

CREATE TABLE IF NOT EXISTS `details` (
  `id` char(17) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `c_url` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `server name` varchar(64) DEFAULT NULL,
  `perfdata` mediumblob,
  `type` tinyint(4) DEFAULT NULL,
  `cookie` blob,
  `post` blob,
  `get` blob,
  `pmu` int(11) unsigned DEFAULT NULL,
  `wt` int(11) unsigned DEFAULT NULL,
  `cpu` int(11) unsigned DEFAULT NULL,
  `server_id` char(3) NOT NULL DEFAULT 't11',
  `aggregateCalls_include` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for table `details`
--
ALTER TABLE `details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `url` (`url`),
  ADD KEY `c_url` (`c_url`),
  ADD KEY `cpu` (`cpu`),
  ADD KEY `wt` (`wt`),
  ADD KEY `pmu` (`pmu`),
  ADD KEY `timestamp` (`timestamp`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

