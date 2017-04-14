DROP TABLE IF EXISTS `#__lovefactory_fillin_notifications`;
CREATE TABLE `#__lovefactory_fillin_notifications` (
  `user_id` int(11) NOT NULL,
  `registered_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `#__lovefactory_memberships` ADD `max_message_replies_per_day` int(11) NOT NULL AFTER `max_messages_per_day`;

ALTER TABLE `#__lovefactory_statistics_per_day` ADD `date_message_reply` date NOT NULL, ADD `message_replies` int(11) NOT NULL;

ALTER TABLE `#__lovefactory_memberships_sold` ADD `max_message_replies_per_day` int(11) NOT NULL AFTER `max_messages_per_day`;

ALTER TABLE `#__lovefactory_profiles` ADD `display_name` varchar(255) NOT NULL AFTER `user_id`;

ALTER TABLE `#__lovefactory_profiles` DROP `alerts`, DROP `date_format`, DROP `infobar`;
