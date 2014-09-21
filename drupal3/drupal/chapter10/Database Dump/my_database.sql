-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Sep 13, 2014 at 05:33 AM
-- Server version: 5.5.34
-- PHP Version: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `my_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `people`
--
/*
CREATE TABLE `people` (
  `name` varchar(100) NOT NULL,
  `shoe_size` int(2) NOT NULL,
  `birth_year` int(4) NOT NULL,
  `favorite_band` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
*/
--
-- Dumping data for table `people`
--

INSERT INTO `people` (`name`, `shoe_size`, `birth_year`, `favorite_band`, `username`, `password`) VALUES
('George', 10, 1972, 'The Cure', 'admin', 'pa55word'),
('Sally', 8, 1975, 'Coldplay', 'someone', '28f3hj2'),
('Deepak', 10, 1969, 'Beach Boys', 'homestar', 'RUNNING');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
