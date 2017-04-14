DROP TABLE IF EXISTS `#__lovefactory_imports`;
CREATE TABLE `#__lovefactory_imports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adaptor` varchar(255) NOT NULL,
  `finished` tinyint(1) NOT NULL,
  `percent` decimal(10,0) NOT NULL,
  `params` mediumtext NOT NULL,
  `last_action` varchar(255) NOT NULL,
  `current_action` varchar(255) NOT NULL,
  `current_action_finished` tinyint(1) NOT NULL,
  `current_action_percent` decimal(5,2) NOT NULL,
  `message` varchar(255) NOT NULL,
  `started_at` datetime NOT NULL,
  `last_action_at` datetime NOT NULL,
  `finished_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_imports_jomsocial_discuss`;
CREATE TABLE `#__lovefactory_imports_jomsocial_discuss` (
  `discuss_id` int(11) NOT NULL,
  `imported_group_id` int(11) NOT NULL,
  `imported_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_imports_jomsocial_groups`;
CREATE TABLE `#__lovefactory_imports_jomsocial_groups` (
  `group_id` int(11) NOT NULL,
  `imported_id` int(11) NOT NULL,
  KEY `group_id` (`group_id`),
  KEY `imported_id` (`imported_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_imports_jomsocial_photos`;
CREATE TABLE `#__lovefactory_imports_jomsocial_photos` (
  `photo_id` int(11) NOT NULL,
  `imported_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comments` tinyint(1) NOT NULL,
  KEY `photo_id` (`photo_id`),
  KEY `imported_id` (`imported_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_imports_jomsocial_users`;
CREATE TABLE `#__lovefactory_imports_jomsocial_users` (
  `user_id` int(11) NOT NULL,
  `imported` tinyint(1) NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `imported` (`imported`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_imports_jomsocial_videos`;
CREATE TABLE `#__lovefactory_imports_jomsocial_videos` (
  `video_id` int(11) NOT NULL,
  `imported_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comments` tinyint(1) NOT NULL,
  KEY `video_id` (`video_id`),
  KEY `comments` (`comments`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
