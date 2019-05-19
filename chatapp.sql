-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2016 at 02:47 AM
-- Server version: 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `chatapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `bannedword1`
--

CREATE TABLE IF NOT EXISTS `bannedword1` (
  `bword1` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bannedword1`
--

INSERT INTO `bannedword1` (`bword1`) VALUES
('test'),
('rahul'),
('ram');

-- --------------------------------------------------------

--
-- Table structure for table `bannedword2`
--

CREATE TABLE IF NOT EXISTS `bannedword2` (
  `bword2` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bannedword2`
--

INSERT INTO `bannedword2` (`bword2`) VALUES
('hey');

-- --------------------------------------------------------

--
-- Table structure for table `bannedword3`
--

CREATE TABLE IF NOT EXISTS `bannedword3` (
  `bword3` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bannedword3`
--

INSERT INTO `bannedword3` (`bword3`) VALUES
('hello');

-- --------------------------------------------------------

--
-- Table structure for table `banword_count`
--

CREATE TABLE IF NOT EXISTS `banword_count` (
  `id` bigint(10) NOT NULL,
  `type1` int(10) DEFAULT NULL,
  `type2` int(10) DEFAULT NULL,
  `type3` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banword_count`
--

INSERT INTO `banword_count` (`id`, `type1`, `type2`, `type3`) VALUES
(7, 3, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `blockuser`
--

CREATE TABLE IF NOT EXISTS `blockuser` (
`id` int(100) NOT NULL,
  `from_id` int(100) NOT NULL,
  `to_id` int(100) NOT NULL,
  `block_count` int(10) NOT NULL,
  `block_status` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blockuser`
--

INSERT INTO `blockuser` (`id`, `from_id`, `to_id`, `block_count`, `block_status`) VALUES
(14, 1, 7, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `familybanned`
--

CREATE TABLE IF NOT EXISTS `familybanned` (
  `famword3` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `familybanned`
--

INSERT INTO `familybanned` (`famword3`) VALUES
('you');

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
`id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `is_confirm` int(10) NOT NULL,
  `relationship` varchar(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `from_id`, `to_id`, `is_confirm`, `relationship`) VALUES
(8, 1, 7, 1, 'friends'),
(9, 2, 1, 1, 'family');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `time` varchar(50) NOT NULL,
  `sender_id` varchar(50) NOT NULL,
  `receiver_id` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=366 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `name`, `message`, `time`, `sender_id`, `receiver_id`) VALUES
(362, 'Tejas Desai', 'hey ram', '6:48:13', '7', '1'),
(363, 'Tejas Desai', 'fdfdf', '6:51:8', '7', '1'),
(364, 'Tejas Desai', 'h**', '6:59:50', '7', '1'),
(365, 'Tejas Desai', 'r**** and r**', '7:1:0', '7', '1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(100) NOT NULL,
  `user_firstname` varchar(50) NOT NULL,
  `user_lastname` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `address` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `phone` bigint(10) NOT NULL,
  `profilepicture` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_firstname`, `user_lastname`, `email`, `password`, `address`, `birthdate`, `gender`, `phone`, `profilepicture`) VALUES
(1, 'Rahul', 'Lad', 'rahulad_502@rediffmail.com', 'Test', 'Mumbai', '1990-12-07', 'Male', 9029345704, 'IMG_20150514_135452.jpg'),
(2, 'Nikhil', 'Pawar', 'pawar_nikhil@rediffmail.com', 'demo', 'Thane', '1992-12-12', 'Male', 8082457896, 'IMG_20150612_093014782.jpg'),
(3, 'Arun', 'Savekar', 'arun.savekar@gmail.com', 'demo', 'Mumbai', '1990-02-02', 'Male', 9920568932, 'IMG_20150612_165710156.jpg'),
(4, 'Pankaj', 'Thorat', 'pankaj.thorat@gmail.com', 'demo', 'Mumbai', '1990-02-02', 'Male', 9495875698, 'IMG_20150110_021104162.jpg'),
(5, 'Kiran', 'Salte', 'kiran.salte@gmail.com', 'demo', 'Navi Mumbai', '1990-03-02', 'Male', 8888888888, 'IMG_20141121_132226830.jpg'),
(6, 'Rohini', 'Lad', 'rohini.lad@gmail.com', 'test', 'Mumbai', '2015-09-16', 'Female', 9874563210, 'IMG_20141220_205858862.jpg'),
(7, 'Tejas', 'Desai', 'tejas.desai@gmail.com', 'tejas', 'Thane', '2015-09-03', 'Male', 9995557778, 'images.jpg'),
(8, 'Amita', 'Bapat', 'amit.bapat@gmail.com', 'test123', 'Dhule', '2015-10-08', 'Female', 9874563210, 'IMG_20141119_221657584.jpg'),
(9, 'Abhijeet', 'Powar', 'abhi.powar@mail.com', 'test', 'kolhapur', '2015-10-07', 'Male', 9874524555, 'IMG_20150514_135743.jpg'),
(10, 'Nilesh', 'chowgule', 'nil.chowgule@mail.com', 'test', 'Ajara', '2016-02-02', 'Male', 8082564789, 'IMG_20150515_153159.jpg'),
(11, 'Ramesh', 'Gore', 'ramesh.gore@gmail.com', '123', 'kapashi', '2015-11-04', 'Male', 9874521459, 'IMG_20150612_145431.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `wall_update`
--

CREATE TABLE IF NOT EXISTS `wall_update` (
`post_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `post_message` varchar(255) NOT NULL,
  `time` varchar(25) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wall_update`
--

INSERT INTO `wall_update` (`post_id`, `from_id`, `to_id`, `post_message`, `time`) VALUES
(47, 7, 7, 'hey\n', '1446288514'),
(48, 7, 1, 'hello Rahul', '1446288534'),
(49, 1, 1, 'hi', '1446288607'),
(50, 1, 7, 'hi desai', '1446288649'),
(51, 7, 7, 'hi', '1452908184');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banword_count`
--
ALTER TABLE `banword_count`
 ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `blockuser`
--
ALTER TABLE `blockuser`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`,`phone`);

--
-- Indexes for table `wall_update`
--
ALTER TABLE `wall_update`
 ADD PRIMARY KEY (`post_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blockuser`
--
ALTER TABLE `blockuser`
MODIFY `id` int(100) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=366;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(100) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `wall_update`
--
ALTER TABLE `wall_update`
MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=52;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
