# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: us-cdbr-iron-east-03.cleardb.net (MySQL 5.5.45-log)
# Database: heroku_c70494bd637bbce
# Generation Time: 2015-10-29 22:16:12 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(4) NOT NULL,
  `comment` varchar(500) NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upVotes` int(5) NOT NULL DEFAULT '0',
  `downVotes` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `post_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL,
  `title` varchar(100) NOT NULL,
  `body` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imageURL` varchar(255) DEFAULT NULL,
  `thumbURL` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table reported_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reported_comments`;

CREATE TABLE `reported_comments` (
  `report_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(4) NOT NULL,
  `comment` varchar(500) NOT NULL DEFAULT '',
  `reporter_id` int(4) NOT NULL,
  `reportReason` varchar(255) NOT NULL,
  `reviewed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table reported_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `reported_users`;

CREATE TABLE `reported_users` (
  `report_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL,
  `username` varchar(40) NOT NULL,
  `fullName` varchar(50) NOT NULL,
  `emailAddress` varchar(30) NOT NULL,
  `reporter_id` int(4) NOT NULL,
  `reportReason` varchar(255) NOT NULL DEFAULT '',
  `reviewed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

LOCK TABLES `reported_users` WRITE;
/*!40000 ALTER TABLE `reported_users` DISABLE KEYS */;

INSERT INTO `reported_users` (`report_id`, `user_id`, `username`, `fullName`, `emailAddress`, `reporter_id`, `reportReason`, `reviewed`)
VALUES
	(2,12,'voltage','Johan Ellis','voltage@gmail.com',2,'His name is copyrighted! ...apparently',0);

/*!40000 ALTER TABLE `reported_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tbl_places
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_places`;

CREATE TABLE `tbl_places` (
  `place_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `place` varchar(160) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `lat` float(15,11) NOT NULL,
  `lng` float(15,11) NOT NULL,
  PRIMARY KEY (`place_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullName` varchar(50) NOT NULL,
  `emailAddress` varchar(30) NOT NULL,
  `registerDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profileImage` varchar(255) NOT NULL DEFAULT 'images/default.png',
  `reputation` int(11) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `reported` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`user_id`, `username`, `password`, `fullName`, `emailAddress`, `registerDate`, `profileImage`, `reputation`, `admin`, `reported`, `banned`)
VALUES
	(2,'emotality','$2y$10$d3ZiDSMhxMxtaKFYMUGNkuwV1W7YziRT9F6BSqTree6jGhBx5A.7O','Jean-Pierre Fourie','emotality@hotmail.com','2015-10-29 21:34:11','images/default.png',0,1,0,0),
	(12,'voltage','$2y$10$cXgYh4pvNKtqc6A26d4/A./maPEyOoBL5.mf6bBgGNMVTXVN.qbga','Johan Ellis','voltage@gmail.com','2015-10-29 21:37:17','images/default.png',0,1,0,0);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table votes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `votes`;

CREATE TABLE `votes` (
  `vote_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `commenter_id` int(4) NOT NULL,
  `voter_id` int(4) NOT NULL,
  `vote` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`vote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
