-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 24, 2017 at 07:14 PM
-- Server version: 5.7.17-log
-- PHP Version: 7.1.3

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
-- Table structure for table `company_info`
--

CREATE TABLE `company_info` (
  `id` int(11) NOT NULL,
  `user_info_id` int(11) DEFAULT NULL COMMENT 'id matches id in user_info',
  `company_name` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_type` int(1) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT 'pending,approved,denied = 0,1,2',
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company_info`
--

INSERT INTO `company_info` (`id`, `user_info_id`, `company_name`, `email_address`, `password`, `account_type`, `status`, `comment`) VALUES
(7, 25, 'Test', 'sydehlin@iastate.edu', '098f6bcd4621d373cade4e832627b4f6', 2, 1, ''),
(8, 0, 'test', 'sydney.ehlinger@gmail.com', '098f6bcd4621d373cade4e832627b4f6', 2, 2, 'test to deny company user'),
(9, 24, 'test', 'test@test.com', '098f6bcd4621d373cade4e832627b4f6', 2, 1, ''),
(10, 0, 'Test', 'test1@test.com', '5f4dcc3b5aa765d61d8327deb882cf99', 2, 0, ''),
(11, 0, 'Test', 'test2@test.com', '6cb75f652a9b52798eb6cf2201057c73', 2, 0, ''),
(12, NULL, 'evatest', 'hello@gmail.com', '5d41402abc4b2a76b9719d911017c592', 2, 0, NULL),
(13, NULL, 'Plant', 'plant@gmail.com', '5d41402abc4b2a76b9719d911017c592', 2, 0, NULL),
(14, 26, 'plan', 'eva44@gmail.com', '5d41402abc4b2a76b9719d911017c592', 2, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_info`
--
ALTER TABLE `company_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_info`
--
ALTER TABLE `company_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
