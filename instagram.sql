-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.46-0ubuntu0.14.04.2 - (Ubuntu)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for instagram
DROP DATABASE IF EXISTS `instagram`;
CREATE DATABASE IF NOT EXISTS `instagram` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `instagram`;


-- Dumping structure for table instagram.account
DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `user_id` int(11) NOT NULL COMMENT 'Foreign Key',
  `password` varchar(250) NOT NULL,
  `state` int(1) NOT NULL DEFAULT '1' COMMENT '1 - Account Is Activated, 0 - Account Is Deactivated',
  `thedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK__user_account` (`user_id`),
  CONSTRAINT `FK__user_account` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table instagram.account: ~5 rows (approximately)
DELETE FROM `account`;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` (`id`, `user_id`, `password`, `state`, `thedate`) VALUES
	(1, 1, '$2a$08$gKRw93FzFFwjyFddK/EBK.1HnNIFmrFWJfWoFUFrZXjuOdY4TX8u.', 1, '2015-11-26 13:54:15'),
	(2, 3, '$2a$08$Hx5le05pRX0awmIO5TSMqed6IfVXOg7LZbcp.dQqFaA0k2AjUn.nO', 1, '2015-11-26 13:57:24'),
	(3, 4, '$2a$08$rrBAlTVAaZ9Cn36Dov6i5ewMlZbgSWDdP4uDzf8sssoEL/zGeH5rC', 1, '2015-12-02 13:53:14'),
	(4, 5, '$2a$08$JbPLinw7Hb.Xa85kZkdpnOYRtmn0OxETTAriDr/ZuGTqYTRAlVvni', 1, '2015-12-04 18:07:58'),
	(5, 11, '$2a$08$HJzsSxb8DSIUrMLEF2HKROaJr2k3f2HKlsDByO5O65lxxUa0svX7G', 1, '2015-12-04 18:11:25');
/*!40000 ALTER TABLE `account` ENABLE KEYS */;


-- Dumping structure for table instagram.comment
DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `user_id` int(11) NOT NULL COMMENT 'Foreign Key',
  `post_id` int(11) NOT NULL COMMENT 'Foreign Key',
  `body` varchar(200) NOT NULL,
  `thedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK__user_comment` (`user_id`),
  KEY `FK__post_comment` (`post_id`),
  CONSTRAINT `FK__post_comment` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__user_comment` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table instagram.comment: ~3 rows (approximately)
DELETE FROM `comment`;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` (`id`, `user_id`, `post_id`, `body`, `thedate`) VALUES
	(6, 3, 11, 'This is a test run of the comments module', '2015-12-04 17:03:40'),
	(7, 3, 11, 'This is comment number two.', '2015-12-04 17:03:58'),
	(8, 11, 10, 'baaah humbumg', '2015-12-04 18:16:08');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;


-- Dumping structure for table instagram.follower
DROP TABLE IF EXISTS `follower`;
CREATE TABLE IF NOT EXISTS `follower` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `following` int(11) NOT NULL COMMENT 'Foreign Key',
  `follower` int(11) NOT NULL COMMENT 'Foreign Key',
  `thedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK__user_following` (`following`),
  KEY `FK_follower_user` (`follower`),
  CONSTRAINT `FK_follower_user` FOREIGN KEY (`follower`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__user_following` FOREIGN KEY (`following`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table instagram.follower: ~0 rows (approximately)
DELETE FROM `follower`;
/*!40000 ALTER TABLE `follower` DISABLE KEYS */;
/*!40000 ALTER TABLE `follower` ENABLE KEYS */;


-- Dumping structure for table instagram.like
DROP TABLE IF EXISTS `like`;
CREATE TABLE IF NOT EXISTS `like` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `user_id` int(11) NOT NULL COMMENT 'Foreign Key',
  `post_id` int(11) NOT NULL COMMENT 'Foreign Key',
  `thedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK__user_like` (`user_id`),
  KEY `FK__post_like` (`post_id`),
  CONSTRAINT `FK__post_like` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__user_like` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- Dumping data for table instagram.like: ~9 rows (approximately)
DELETE FROM `like`;
/*!40000 ALTER TABLE `like` DISABLE KEYS */;
INSERT INTO `like` (`id`, `user_id`, `post_id`, `thedate`) VALUES
	(2, 3, 10, '2015-12-03 10:27:11'),
	(6, 3, 9, '2015-12-03 12:27:20'),
	(7, 1, 7, '2015-12-03 12:30:45'),
	(9, 3, 11, '2015-12-04 17:48:39'),
	(10, 3, 4, '2015-12-04 17:50:18'),
	(11, 3, 1, '2015-12-04 17:50:37'),
	(13, 1, 1, '2015-12-04 17:51:25'),
	(14, 11, 11, '2015-12-04 18:45:49'),
	(15, 11, 10, '2015-12-04 18:49:16');
/*!40000 ALTER TABLE `like` ENABLE KEYS */;


-- Dumping structure for table instagram.post
DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'Foreign Key. id of the user who posted',
  `name` varchar(100) NOT NULL COMMENT 'name of the image/video posted',
  `caption` varchar(200) NOT NULL,
  `numberoflikes` int(11) NOT NULL DEFAULT '0',
  `numberofcomments` int(11) NOT NULL DEFAULT '0',
  `thedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK__user_post` (`user_id`),
  CONSTRAINT `FK__user_post` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Dumping data for table instagram.post: ~9 rows (approximately)
DELETE FROM `post`;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` (`id`, `user_id`, `name`, `caption`, `numberoflikes`, `numberofcomments`, `thedate`) VALUES
	(1, 3, '../images/posts/3/152611102935Webcam-1445585283.jpeg', 'My first IG photo', 2, 0, '2015-12-04 17:51:25'),
	(2, 3, '../images/posts/3/152611102935Webcam-1445585283.jpeg', 'My first IG photo', 0, 0, '2015-11-27 10:35:22'),
	(4, 3, '../images/posts/3/152611103648Webcam-1445585282.jpeg', 'Testing 1 2 3', 1, 0, '2015-12-04 17:50:18'),
	(5, 3, '../images/posts/3/Webcam-1445585240.jpeg', 'Chilled Out', 0, 0, '2015-11-27 11:17:39'),
	(6, 3, '../images/posts/3/IMG_20150120_225235.jpg', 'Baby Taraji. Day One', 0, 0, '2015-11-27 11:22:34'),
	(7, 3, '../images/posts/3/IMG_20150121_172708.jpg', 'Baby Taraji. Day Three', 1, 0, '2015-12-03 12:30:45'),
	(9, 3, '../images/posts/3/IMG_20131107_032920.jpg', 'Robert Ndung\'u is Gay', 1, 0, '2015-12-03 12:27:20'),
	(10, 3, '../images/posts/3/.facebook_-1643674159.jpg', 'jhkjkhjk', 2, 1, '2015-12-04 18:16:08'),
	(11, 4, '../images/posts/4/IMG_20131015_192203.jpg', 'iyygigyiygiyiyi', 6, 2, '2015-12-04 18:45:49');
/*!40000 ALTER TABLE `post` ENABLE KEYS */;


-- Dumping structure for table instagram.post_details
DROP TABLE IF EXISTS `post_details`;
CREATE TABLE IF NOT EXISTS `post_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `post_id` int(11) NOT NULL COMMENT 'Foreign Key',
  `size` int(5) NOT NULL COMMENT 'Size of the post in KB',
  `orientation` varchar(12) NOT NULL COMMENT 'Portrait or Landscape',
  `dimensions` varchar(50) NOT NULL COMMENT 'dimensionX x dimensionY',
  `extension` varchar(50) NOT NULL,
  `resolution` varchar(50) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'Image' COMMENT 'Image or Video',
  PRIMARY KEY (`id`),
  KEY `FK__post_post_details` (`post_id`),
  CONSTRAINT `FK__post_post_details` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Dumping data for table instagram.post_details: ~7 rows (approximately)
DELETE FROM `post_details`;
/*!40000 ALTER TABLE `post_details` DISABLE KEYS */;
INSERT INTO `post_details` (`id`, `post_id`, `size`, `orientation`, `dimensions`, `extension`, `resolution`, `type`) VALUES
	(1, 2, 10612, 'Portrait', '320X240', '.jpeg', '72', 'image'),
	(3, 5, 11128, 'Portrait', '320X240', '.jpeg', '72', 'Image'),
	(4, 6, 1137886, 'Portrait', '1807X1807', '.jpg', '72', 'Image'),
	(5, 7, 586376, 'Portrait', '1536X1536', '.jpg', '72', 'Image'),
	(7, 9, 1216520, 'Portrait', '1536X2048', '.jpg', '72', 'Image'),
	(8, 10, 38003, 'Portrait', '481X720', '.jpg', '72', 'Image'),
	(9, 11, 718716, 'Portrait', '1536X2048', '.jpg', '72', 'Image');
/*!40000 ALTER TABLE `post_details` ENABLE KEYS */;


-- Dumping structure for table instagram.tag
DROP TABLE IF EXISTS `tag`;
CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `thedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK__user_tag` (`user_id`),
  KEY `FK__post_tag` (`post_id`),
  CONSTRAINT `FK__post_tag` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__user_tag` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table instagram.tag: ~0 rows (approximately)
DELETE FROM `tag`;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;


-- Dumping structure for table instagram.user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `second_name` varchar(50) NOT NULL,
  `avatar` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `description` varchar(500) NOT NULL,
  `thedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Dumping data for table instagram.user: ~5 rows (approximately)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `username`, `email`, `phone`, `first_name`, `second_name`, `avatar`, `dob`, `description`, `thedate`) VALUES
	(1, 'Ronini', 'ronnie@example.com', '123456789', 'Ronnie', 'Nyaga', '../images/avatar/152611014715Webcam-1445585283.jpe', '0000-00-00', 'Geek. Gamer. Gambler. Lover of Japanese Anime and Lover of the Mystique Arts', '2015-11-26 23:01:55'),
	(3, 'ronnie_nyaga', 'ronnienyaga@gmail.com', '123456789', 'Ronnie', 'Nyaga', '../images/avatar/152611014715Webcam-1445585283.jpe', '0000-00-00', 'Geek. Gamer. Gambler. Lover of Japanese Anime and Lover of the Mystique Arts', '2015-11-26 23:01:59'),
	(4, 'kaari', 'sharonkaari@gmail.com', '123456789', 'sharon', 'Kaari', '../images/avatar/IMG_20131015_192159.jpg', '0000-00-00', 'mfkshfkshdf', '2015-12-02 13:53:14'),
	(5, 'WilfredTheNerd', '', '', 'Wilfred', 'Kareithi', '', '0000-00-00', '', '2015-12-04 18:07:58'),
	(11, 'WilfredTheNerd1', 'kareithiwilfred@gmail.com', '123456789', 'Wilfred', 'Kareithi', '../images/avatar/Webcam-1448263283.jpeg', '0000-00-00', 'I am an Asshole. I will ruin your life in every way known to man.', '2015-12-04 18:11:24');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
