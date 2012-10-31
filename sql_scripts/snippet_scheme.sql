# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: mysql.twosmallfeet.com (MySQL 5.1.53-log)
# Database: snippetboxx_sniplet
# Generation Time: 2012-10-31 05:30:00 +0000
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
  `config_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `session_id` varchar(40) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `ip_address` varchar(16) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `user_agent` varchar(50) CHARACTER SET latin1 NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sniplets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sniplets`;

CREATE TABLE `sniplets` (
  `sniplet_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sniplet_title` varchar(350) NOT NULL DEFAULT '',
  `sniplet_content` varchar(10000) NOT NULL DEFAULT '',
  `sniplet_url` varchar(350) NOT NULL DEFAULT '',
  `create_sniplet_time` varchar(255) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`sniplet_id`),
  FULLTEXT KEY `sniplet_title` (`sniplet_title`,`sniplet_content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table sniplets_to_tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sniplets_to_tags`;

CREATE TABLE `sniplets_to_tags` (
  `sniplet_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`sniplet_id`,`tag_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_keyword` varchar(255) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `create_tag_time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `tag_keyword` (`tag_keyword`),
  FULLTEXT KEY `tag_keyword_2` (`tag_keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table user_tracker
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_tracker`;

CREATE TABLE `user_tracker` (
  `tracker_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `tracker_ip` varchar(255) NOT NULL,
  `tracker_region` varchar(255) NOT NULL,
  `tracker_date_created` varchar(255) NOT NULL,
  `tracker_date_updated` varchar(255) NOT NULL,
  `tracker_clicks` varchar(1000) NOT NULL DEFAULT '',
  `username` varchar(255) DEFAULT NULL,
  `agent` varchar(255) DEFAULT NULL,
  `referer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`tracker_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '100',
  `token` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `date_created` varchar(255) DEFAULT NULL,
  `date_last_login` varchar(255) DEFAULT NULL,
  `last_ip_login` varchar(255) DEFAULT NULL,
  `active` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
