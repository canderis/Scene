-- phpMyAdmin SQL Dump
-- version 4.6.5
-- https://www.phpmyadmin.net/
--
-- Host: mysql.shuffman.com
-- Generation Time: Apr 13, 2017 at 12:24 PM
-- Server version: 5.6.25-log
-- PHP Version: 7.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sceneapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `user_opinion`
--

CREATE TABLE `user_opinion` (
  `id` int(11) NOT NULL,
  `user_info_id` int(11) NOT NULL,
  `scene_id` varchar(255) NOT NULL,
  `scene_user_interest` int(1) NOT NULL DEFAULT '0' COMMENT '1-interested, 2-not interested',
  `scene_user_like` int(1) NOT NULL DEFAULT '0' COMMENT '1-liked, 2-disliked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_opinion`
--
ALTER TABLE `user_opinion`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_opinion`
--
ALTER TABLE `user_opinion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
