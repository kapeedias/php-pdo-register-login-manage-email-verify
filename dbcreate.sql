
-- Drop members_users table if already exists. Once deleted cannot undo.
DROP TABLE IF EXISTS `members_users`;

-- Create members_users table which will hold the data for users login in, signing up
CREATE TABLE `members_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `md5_id` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `first_name` tinytext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `last_name` tinytext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `user_name` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `user_email` varchar(220) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `user_level` tinyint(4) NOT NULL DEFAULT '1',
  `pwd` varchar(220) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `address` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `pic` varchar(900) CHARACTER SET latin2 NOT NULL DEFAULT 'dist/img/profile.jpg',
  `country` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `tel` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `website` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `users_ip` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `approved` int(1) NOT NULL DEFAULT '0',
  `activation_code` int(10) NOT NULL DEFAULT '0',
  `banned` int(1) NOT NULL DEFAULT '0',
  `ckey` varchar(220) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `ctime` varchar(220) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email` (`user_email`),
  FULLTEXT KEY `idx_search` (`first_name`,`last_name`,`address`,`user_email`,`user_name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
