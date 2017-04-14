DROP TABLE IF EXISTS `#__lovefactory_friends_requests`;
CREATE TABLE `#__lovefactory_friends_requests` (
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  KEY `sender_id_receiver_id_created_at` (`sender_id`,`receiver_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `#__lovefactory_friends` ADD `approved` tinyint(1) NOT NULL;
