-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 23, 2013 at 11:29 AM
-- Server version: 5.5.31
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fitbit_2014`
--
CREATE DATABASE IF NOT EXISTS `fitbit_2014` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `fitbit_2014`;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `steps` int(11) DEFAULT NULL,
  `floors` int(11) DEFAULT NULL,
  `calories` int(11) DEFAULT NULL,
  `active_score` int(11) DEFAULT NULL,
  `distance` int(11) DEFAULT NULL,
  `elevation` int(11) DEFAULT NULL,
  `min_sedentary` int(11) DEFAULT NULL,
  `min_lightlyactive` int(11) DEFAULT NULL,
  `min_fairlyactive` int(11) DEFAULT NULL,
  `min_veryactive` int(11) DEFAULT NULL,
  `activity_calories` int(11) DEFAULT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `badge`
--

CREATE TABLE IF NOT EXISTS `badge` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `badge_pic` varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `min_points` int(11) NOT NULL DEFAULT '0',
  `description` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `challenge`
--

CREATE TABLE IF NOT EXISTS `challenge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `description` varchar(500) CHARACTER SET utf8 NOT NULL,
  `points` int(11) NOT NULL,
  `badge_pic` varchar(400) CHARACTER SET utf8 NOT NULL DEFAULT 'http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/assets/img/challenge.gif',
  `thread_id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `steps_value` int(11) NOT NULL,
  `floor_value` int(11) NOT NULL,
  `sleep_value` int(11) NOT NULL,
  `sleep_time` time NOT NULL,
  `quota` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `challenge`
--

INSERT INTO `challenge` (`id`, `title`, `description`, `points`, `badge_pic`, `thread_id`, `category`, `start_time`, `end_time`, `steps_value`, `floor_value`, `sleep_value`, `sleep_time`, `quota`) VALUES
(1, 'Home-school-home, nowhere else', 'Clock 6000 steps', 10, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-2.png', 1, 1, '00:00:00', '23:59:59', 6000, 0, 0, '00:00:00', 10),
(2, 'Fitbit says I nailed it', 'Clock 10000 steps', 20, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-3.png', 2, 1, '00:00:00', '23:59:59', 10000, 0, 0, '00:00:00', 30),
(3, 'Do you live in Pasir Ris?', 'Clock 15000 steps', 30, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-4.png', 3, 1, '00:00:00', '23:59:59', 15000, 0, 0, '00:00:00', 9999),
(4, 'Long March', 'Clock 20000 steps', 50, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-5.png', 4, 1, '00:00:00', '23:59:59', 20000, 0, 0, '00:00:00', 9999),
(5, 'The Great Migration', 'Clock 25000 steps', 75, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-6.png', 5, 1, '00:00:00', '23:59:59', 25000, 0, 0, '00:00:00', 9999),
(6, 'POP loh!', 'Clock 30000 steps', 100, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-9.png', 6, 1, '00:00:00', '23:59:59', 30000, 0, 0, '00:00:00', 9999),
(13, 'Burning Midnight Oil', 'Sleep for 6 hours', 10, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-15.png', 13, 2, '00:00:00', '23:59:59', 0, 0, 6, '00:00:00', 10),
(14, 'Army days', 'Sleep for 7 hours', 20, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-16.png', 14, 2, '00:00:00', '23:59:59', 0, 0, 7, '00:00:00', 30),
(15, 'Spent 1/3 of life asleep', 'Sleep for 8 hours', 30, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-17.png', 15, 2, '00:00:00', '23:59:59', 0, 0, 8, '00:00:00', 9999),
(16, 'REMming', 'Sleep for 9 hours', 30, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-19.png', 16, 2, '00:00:00', '23:59:59', 0, 0, 9, '00:00:00', 9999),
(17, 'I love my Bed', 'Sleep for 10 hours', 30, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-20.png', 17, 2, '00:00:00', '23:59:59', 0, 0, 10, '00:00:00', 9999),
(18, 'My Bed Loves Me', 'Sleep for 11 hours', 30, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-21.png', 18, 2, '00:00:00', '23:59:59', 0, 0, 11, '00:00:00', 9999),
(19, 'Luxury night', 'Sleeping before 10pm', 30, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-22.png', 19, 2, '00:00:00', '23:59:59', 0, 0, 0, '22:00:00', 9999),
(20, 'Hitting the sack', 'Sleeping before 11pm', 20, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-23.png', 20, 2, '00:00:00', '23:59:59', 0, 0, 0, '23:00:00', 9999),
(21, 'Don''t be late tomorrow', 'Sleeping before 12am', 10, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-25.png', 21, 2, '00:00:00', '23:59:59', 0, 0, 0, '23:59:59', 9999),
(22, 'Leaving the pastures behind', 'Clock 450 steps and 2 floors from Science Bus Stop to MD6 from 0800 onwards to 0830', 15, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-27.png', 23, 0, '08:00:00', '08:30:00', 450, 2, 0, '00:00:00', 9999),
(24, 'I could roll down to school', 'Clock 450 steps and 1 floor required from KE Hall to MD6 from 0800 to 0830', 15, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-28.png', 24, 0, '08:00:00', '08:30:00', 450, 1, 0, '00:00:00', 9999),
(25, 'Getting to a good Start', 'Clock 600 steps and 5 floors required from KR MRT station to MD6 from 0730 onwards to 0830. Stairs must be used.', 30, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-29.png', 25, 0, '07:30:00', '08:30:00', 600, 5, 0, '00:00:00', 9999),
(26, 'Ain''t got no time to hang around', 'Clock 600 steps corresponding to going to kent ridge MRT within the window of 1300-1310, right after lectures end.', 10, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-30.png', 26, 0, '13:00:00', '13:10:00', 600, 0, 0, '00:00:00', 9999),
(27, 'I stay just over this hill!', 'Clock 480 steps and 10 floors equivalent to walking back to KE7 from LT35 within 1300-1315', 20, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-24.png', 27, 0, '13:00:00', '13:15:00', 480, 10, 0, '00:00:00', 9999),
(28, 'The grass is greener on the other side', 'Challenge for those who clock 450 steps equivalent to going from LT35 to Science Busstop within 1300-1315', 5, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-7.png', 28, 0, '13:00:00', '13:15:00', 450, 0, 0, '00:00:00', 9999),
(29, 'Food Dash', 'Clock 280 steps corresponding to going to the science canteen from 1030-1033.', 20, 'http://hep.d2.comp.nus.edu.sg/assets/img/badges/Layer-18.png', 29, 0, '10:30:00', '10:33:00', 280, 0, 0, '00:00:00', 9999);

-- --------------------------------------------------------

--
-- Table structure for table `challengeparticipant`
--

CREATE TABLE IF NOT EXISTS `challengeparticipant` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `complete_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `progress` double DEFAULT '0',
  `category` int(11) DEFAULT NULL,
  `inactive` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx2` (`user_id`,`start_time`,`end_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `emailmessage`
--

CREATE TABLE IF NOT EXISTS `emailmessage` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `message` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forumthread`
--

CREATE TABLE IF NOT EXISTS `forumthread` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `challenge_id` smallint(11) NOT NULL DEFAULT '-1',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creator_id` int(11) NOT NULL,
  `message` varchar(2000) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `tutor_only` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `challenge_id` (`challenge_id`),
  KEY `archived` (`archived`,`tutor_only`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `forumthread`
--

INSERT INTO `forumthread` (`id`, `challenge_id`, `create_time`, `creator_id`, `message`, `archived`, `tutor_only`) VALUES
(1, 1, '2013-02-09 08:33:07', 0, 'Home-school-home, nowhere else', 0, 0),
(2, 2, '2013-02-09 08:33:22', 0, 'Fitbit says I nailed it', 0, 0),
(3, 3, '2013-02-09 08:33:46', 0, 'Do you live in Pasir Ris?', 0, 0),
(4, 4, '2013-02-09 08:33:59', 0, 'Long March', 0, 0),
(5, 5, '2013-02-09 08:34:26', 0, 'The Great Migration', 0, 0),
(6, 6, '2013-02-09 08:34:41', 0, 'POP loh!', 0, 0),
(13, 13, '2013-02-09 08:36:09', 0, 'Burning Midnight Oil', 0, 0),
(14, 14, '2013-02-09 08:36:20', 0, 'Army days', 0, 0),
(15, 15, '2013-02-09 08:36:59', 0, 'Spent 1/3 of life asleep', 0, 0),
(16, 16, '2013-02-09 08:37:10', 0, 'REMming', 0, 0),
(17, 17, '2013-02-09 08:37:29', 0, 'I love my Bed', 0, 0),
(18, 18, '2013-02-09 08:37:41', 0, 'My Bed Loves Me', 0, 0),
(19, 19, '2013-02-09 08:37:53', 0, 'Sleeping before 10pm', 0, 0),
(20, 20, '2013-02-09 08:38:24', 0, 'Hitting the sack', 0, 0),
(21, 21, '2013-02-09 08:38:34', 0, 'Don''t be late tomorrow', 0, 0),
(24, 24, '2013-02-09 08:39:12', 0, 'I could roll down to school', 0, 0),
(25, 25, '2013-02-09 08:39:26', 0, 'Getting to a good Start', 0, 0),
(26, 26, '2013-02-09 08:39:39', 0, 'Ain''t got no time to hang around', 0, 0),
(27, 27, '2013-02-09 08:39:54', 0, 'I stay just over this hill!', 0, 0),
(28, 28, '2013-02-09 08:40:10', 0, 'The grass is greener on the other side', 0, 0),
(29, 29, '2013-02-09 08:40:31', 0, 'Food Dash', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `house`
--

CREATE TABLE IF NOT EXISTS `house` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `picture` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `house`
--

INSERT INTO `house` (`id`, `name`, `picture`) VALUES
(-1, 'Tutor', NULL),
(1, '1', 'http://hep.d2.comp.nus.edu.sg/assets/img/houses/house_pic1.png'),
(2, '2', 'http://hep.d2.comp.nus.edu.sg/assets/img/houses/house_pic2.png'),
(3, '3', 'http://hep.d2.comp.nus.edu.sg/assets/img/houses/house_pic3.png'),
(4, '4', 'http://hep.d2.comp.nus.edu.sg/assets/img/houses/house_pic4.png'),
(5, '5', 'http://hep.d2.comp.nus.edu.sg/assets/img/houses/house_pic5.png'),
(6, '6', 'http://hep.d2.comp.nus.edu.sg/assets/img/houses/house_pic6.png'),
(7, '7', 'http://hep.d2.comp.nus.edu.sg/assets/img/houses/house_pic7.png'),
(8, '8', 'http://hep.d2.comp.nus.edu.sg/assets/img/houses/house_pic8.png'),
(9, '9', 'http://hep.d2.comp.nus.edu.sg/assets/img/houses/house_pic9.png'),
(10, '10', 'http://hep.d2.comp.nus.edu.sg/assets/img/houses/house_pic10.png');

-- --------------------------------------------------------

--
-- Table structure for table `intradayactivity`
--

CREATE TABLE IF NOT EXISTS `intradayactivity` (
  `user_id` int(11) NOT NULL,
  `activity_time` datetime NOT NULL,
  `steps` int(11) DEFAULT NULL,
  `calories` float DEFAULT NULL,
  `calories_level` int(11) DEFAULT NULL,
  `floors` int(11) DEFAULT NULL,
  `elevation` float DEFAULT NULL,
  PRIMARY KEY (`user_id`,`activity_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invalidperiod`
--

CREATE TABLE IF NOT EXISTS `invalidperiod` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message` varchar(500) DEFAULT NULL,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) CHARACTER SET utf8 NOT NULL,
  `url` varchar(400) CHARACTER SET utf8 NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_facebook` tinyint(1) NOT NULL DEFAULT '0',
  `post_pic` varchar(400) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `postsubscription`
--

CREATE TABLE IF NOT EXISTS `postsubscription` (
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`thread_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sleep`
--

CREATE TABLE IF NOT EXISTS `sleep` (
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `total_time` int(11) DEFAULT NULL,
  `time_asleep` int(11) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `awaken_count` int(11) DEFAULT NULL,
  `min_awake` int(11) DEFAULT NULL,
  `min_to_asleep` int(11) DEFAULT NULL,
  `min_after_wakeup` int(11) DEFAULT NULL,
  `efficiency` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `survey`
--

CREATE TABLE IF NOT EXISTS `survey` (
  `userid` int(11) NOT NULL,
  `q0` varchar(1) DEFAULT NULL,
  `q1` varchar(1) DEFAULT NULL,
  `q2` varchar(1) DEFAULT NULL,
  `q3` varchar(1) DEFAULT NULL,
  `q4` varchar(1000) DEFAULT NULL,
  `q5` varchar(1000) DEFAULT NULL,
  `q6` varchar(1000) DEFAULT NULL,
  `q7` varchar(1000) DEFAULT NULL,
  `q8` varchar(1) DEFAULT NULL,
  `q9` varchar(1000) DEFAULT NULL,
  `q10` varchar(1) DEFAULT NULL,
  `q11` varchar(1000) DEFAULT NULL,
  `q12` varchar(1000) DEFAULT NULL,
  `q13` varchar(1000) DEFAULT NULL,
  `q14` varchar(1) DEFAULT NULL,
  `q10yes` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `threadpost`
--

CREATE TABLE IF NOT EXISTS `threadpost` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `commenter_id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `comment` varchar(2000) CHARACTER SET utf8 NOT NULL,
  `comment_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE IF NOT EXISTS `updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `update` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `time_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `last_name` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `fitbit_id` varchar(10) CHARACTER SET utf8 NOT NULL,
  `oauth_token` varchar(40) CHARACTER SET utf8 NOT NULL,
  `oauth_secret` varchar(40) CHARACTER SET utf8 NOT NULL,
  `profile_pic` varchar(200) CHARACTER SET utf8 NOT NULL,
  `gender` varchar(10) CHARACTER SET utf8 NOT NULL,
  `house_id` int(11) DEFAULT NULL,
  `username` varchar(20) CHARACTER SET utf8 NOT NULL,
  `email` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `phantom` tinyint(1) NOT NULL DEFAULT '0',
  `staff` tinyint(1) NOT NULL DEFAULT '0',
  `leader` tinyint(1) NOT NULL DEFAULT '0',
  `points` int(11) DEFAULT '0',
  `fb` tinyint(1) NOT NULL DEFAULT '0',
  `badge_email_unsub` tinyint(1) NOT NULL DEFAULT '0',
  `daily_email_unsub` tinyint(1) NOT NULL DEFAULT '0',
  `challenge_email_unsub` tinyint(1) NOT NULL DEFAULT '0',
  `hide_progress` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fitbit_id` (`fitbit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `fitbit_id`, `oauth_token`, `oauth_secret`, `profile_pic`, `gender`, `house_id`, `username`, `email`, `admin`, `phantom`, `staff`, `leader`, `points`, `fb`, `badge_email_unsub`, `daily_email_unsub`, `challenge_email_unsub`, `hide_progress`) VALUES
(1, 'Minqi', 'Chen', '28VXDX', '9c072a81568d321e4edfa5a9fdb3c82c', 'd473be811e83e8e9336345595b10e964', 'http://cache.fitbit.com/C40708CD-3EA6-0A27-9B1B-2529F1545C6E_profile_100_square.jpg', 'MALE', -1, 'Minqi', 'cnchenminqi@gmail.com', 1, 0, 1, 0, 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `userbadge`
--

CREATE TABLE IF NOT EXISTS `userbadge` (
  `user_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`user_id`,`badge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
