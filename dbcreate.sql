-- Drop members_users table if already exists. Once deleted cannot undo.
DROP TABLE IF EXISTS `members_users`;

-- Create members_users table which will hold the data for users login in, signing up
CREATE TABLE `members_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `md5_id` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `first_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `middle_name` varchar(50) COLLATE utf8_bin DEFAULT 'NULL',
  `last_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `gender` varchar(10) COLLATE utf8_bin DEFAULT 'NULL',
  `birthday` date NOT NULL DEFAULT '1900-12-31',
  `user_name` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_email` varchar(220) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user_level` smallint(6) NOT NULL DEFAULT '1',
  `pwd` varchar(225) COLLATE utf8_bin DEFAULT 'NULL',
  `address` text COLLATE utf8_bin,
  `street` text COLLATE utf8_bin,
  `pic` text COLLATE utf8_bin NOT NULL,
  `city` varchar(100) COLLATE utf8_bin DEFAULT 'NULL',
  `zipcode` varchar(10) COLLATE utf8_bin DEFAULT 'NULL',
  `province` varchar(100) COLLATE utf8_bin DEFAULT 'NULL',
  `country` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `tel` varchar(200) COLLATE utf8_bin DEFAULT '',
  `website` longtext COLLATE utf8_bin NOT NULL,
  `users_ip` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `approved` int(11) NOT NULL DEFAULT '0',
  `email_verify` varchar(20) COLLATE utf8_bin DEFAULT 'NULL',
  `email_verified_on` datetime NOT NULL,
  `verification_email_sent` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activation_code` int(11) NOT NULL DEFAULT '0',
  `banned` int(11) NOT NULL DEFAULT '0',
  `ckey` varchar(220) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ctime` varchar(220) COLLATE utf8_bin NOT NULL DEFAULT '',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`user_name`,`user_email`),
  UNIQUE KEY `members_users_user_email` (`user_email`),
  UNIQUE KEY `members_users_user_name` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

