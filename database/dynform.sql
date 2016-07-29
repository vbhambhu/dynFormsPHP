-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jul 29, 2016 at 05:45 PM
-- Server version: 5.5.42
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dynform`
--

-- --------------------------------------------------------

--
-- Table structure for table `cubes`
--

CREATE TABLE `cubes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `tbl_name` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cubes`
--

INSERT INTO `cubes` (`id`, `name`, `description`, `tbl_name`, `created_at`) VALUES
(1, 'erewrwr rw wrwrw rwr', 'r ewrwrwrwrw', 'helloworld', '2016-07-29 11:06:30'),
(2, 'dsdada', 'dsaa', '', '2016-07-29 13:53:52'),
(3, 'dssdasdasdadsa', 'dsa', '', '2016-07-29 16:54:42'),
(4, 'Untitled', 'Write description here ...', '', '2016-07-29 16:57:37'),
(5, 'Untitled', 'Write description here ...', '', '2016-07-29 17:22:47');

-- --------------------------------------------------------

--
-- Table structure for table `cube_attributes`
--

CREATE TABLE `cube_attributes` (
  `id` int(11) NOT NULL,
  `identifier` varchar(150) NOT NULL,
  `label` varchar(150) NOT NULL,
  `type` varchar(50) NOT NULL,
  `is_required` tinyint(1) NOT NULL,
  `default_value` text,
  `help_text` varchar(255) DEFAULT NULL,
  `validation` tinyint(1) NOT NULL DEFAULT '0',
  `validation_rule` text,
  `cube_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cube_attributes`
--

INSERT INTO `cube_attributes` (`id`, `identifier`, `label`, `type`, `is_required`, `default_value`, `help_text`, `validation`, `validation_rule`, `cube_id`) VALUES
(1, '2189b62b-4a24-adc1-af61', 'Name', 'text', 1, 'dsaadad', 'dsadadad', 0, '1', 1),
(2, '8f080327-5005-bbb9-7f81', 'Patient address', 'text', 1, '', 'Enter address of patient.', 0, '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `helloworld`
--

CREATE TABLE `helloworld` (
  `id` int(9) NOT NULL,
  `cube_id` int(11) NOT NULL,
  `2189b62b-4a24-adc1-af61` varchar(255) NOT NULL,
  `8f080327-5005-bbb9-7f81` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `helloworld`
--

INSERT INTO `helloworld` (`id`, `cube_id`, `2189b62b-4a24-adc1-af61`, `8f080327-5005-bbb9-7f81`) VALUES
(1, 1, 'vinod', 'sffdsfasa aad ada');

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

CREATE TABLE `keys` (
  `id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`) VALUES
(1, 'Vinod', 'Kumar'),
(2, 'Hello', 'World');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cubes`
--
ALTER TABLE `cubes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cube_attributes`
--
ALTER TABLE `cube_attributes`
  ADD UNIQUE KEY `identifier` (`identifier`);

--
-- Indexes for table `helloworld`
--
ALTER TABLE `helloworld`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cubes`
--
ALTER TABLE `cubes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `helloworld`
--
ALTER TABLE `helloworld`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `keys`
--
ALTER TABLE `keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
