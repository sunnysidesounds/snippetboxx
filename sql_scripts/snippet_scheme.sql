# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: mysql.twosmallfeet.com (MySQL 5.1.53-log)
# Database: snippetboxx_sniplet
# Generation Time: 2012-01-05 20:52:02 -0800
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table configuration
# ------------------------------------------------------------

DROP TABLE IF EXISTS `configuration`;

CREATE TABLE `configuration` (
  `config_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `config_title` varchar(255) NOT NULL,
  `config_value` varchar(255) NOT NULL,
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table sniplets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sniplets`;

CREATE TABLE `sniplets` (
  `sniplet_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sniplet_title` varchar(255) NOT NULL,
  `sniplet_content` varchar(1000) NOT NULL,
  `sniplet_url` varchar(255) NOT NULL,
  `sniplet_time` varchar(255) NOT NULL,
  PRIMARY KEY (`sniplet_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sniplets_to_tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sniplets_to_tags`;

CREATE TABLE `sniplets_to_tags` (
  `sniplet_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sniplet_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `sniplets_to_tags_ibfk_1` FOREIGN KEY (`sniplet_id`) REFERENCES `sniplets` (`sniplet_id`),
  CONSTRAINT `sniplets_to_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `tag_keyword` (`tag_keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



ALTER TABLE sniplets ADD FULLTEXT(sniplet_title, sniplet_content);
ALTER TABLE tags ADD FULLTEXT(tag_keyword);


CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '100',
  `token` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


