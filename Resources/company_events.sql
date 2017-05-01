-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 24, 2017 at 07:12 PM
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
-- Table structure for table `company_events`
--

CREATE TABLE `company_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company_events`
--

INSERT INTO `company_events` (`id`, `title`, `description`, `address`, `type`) VALUES
(1, 'Title event', 'This is a description of the event', '1234 Street Ames, IA 50014', 'Concert'),
(2, 'Event #2', 'Description ', 'Address of event', 'Restaurant'),
(3, 'Event #3', 'THIS IS A DESCRIPTION OF EVENT', 'ADDRESS', 'MARATHON'),
(4, 'test', 'test', 'test', 'test');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_events`
--
ALTER TABLE `company_events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_events`
--
ALTER TABLE `company_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
