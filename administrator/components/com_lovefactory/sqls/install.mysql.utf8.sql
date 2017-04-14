DROP TABLE IF EXISTS `#__lovefactory_activity`;
CREATE TABLE `#__lovefactory_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event` varchar(50) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `params` mediumtext NOT NULL,
  `deleted_by_sender` tinyint(1) NOT NULL,
  `deleted_by_receiver` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_admin_profile_tokens`;
CREATE TABLE `#__lovefactory_admin_profile_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_adsense`;
CREATE TABLE `#__lovefactory_adsense` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `script` mediumtext NOT NULL,
  `rows` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_approvals`;
CREATE TABLE `#__lovefactory_approvals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` mediumtext NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_item_id` (`item_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_blacklist`;
CREATE TABLE `#__lovefactory_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sender_id` (`sender_id`),
  KEY `idx_receiver_id` (`receiver_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_fields`;
CREATE TABLE `#__lovefactory_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `params` mediumtext NOT NULL,
  `descriptions` mediumtext NOT NULL,
  `css` mediumtext NOT NULL,
  `labels` mediumtext NOT NULL,
  `required` tinyint(1) NOT NULL,
  `searchable` tinyint(1) NOT NULL,
  `visibility` tinyint(1) NOT NULL,
  `user_visibility` tinyint(1) NOT NULL,
  `admin_only_viewable` tinyint(1) NOT NULL,
  `admin_only_editable` tinyint(1) NOT NULL,
  `lock_after_save` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__lovefactory_fields` (`id`, `title`, `type`, `params`, `descriptions`, `labels`, `required`, `searchable`, `visibility`, `user_visibility`, `admin_only_viewable`, `admin_only_editable`, `lock_after_save`, `published`) VALUES
(1,	'Membership',	'Membership',	'',	'',	'',	0,	0,	1,	0,	0,	0,	0,	1),
(2,	'Main photo',	'ProfilePhoto',	'',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"0\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	1,	0,	1,	0,	0,	0,	0,	1),
(3,	'Username',	'Username',	'{\"ajax_check\":\"1\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	1,	0,	1,	0,	0,	0,	0,	1),
(4,	'Gender',	'Gender',	'{\"choices\":{\"default\":[\"Male\",\"Female\",\"Couple\",\"Other\"]}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"Looking for\",\"en-GB\":\"\"}}',	1,	0,	1,	0,	0,	0,	0,	1),
(5,	'Looking for',	'Looking',	'{\"type\":\"Checkbox\",\"min_choices\":0,\"max_choices\":0,\"display_mode\":\"row\",\"blank_choice\":\"0\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"I am a\",\"en-GB\":\"\"}}',	1,	0,	1,	0,	0,	0,	0,	1),
(6,	'Date of birth',	'Birthdate',	'{\"min_age\":\"18\",\"max_age\":\"40\",\"month_format\":\"text\",\"format\":\"dmY\",\"separator\":\"\\/\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"Age\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"Age between\",\"en-GB\":\"\"}}',	1,	0,	1,	0,	0,	0,	0,	1),
(7,	'Online',	'Online',	'',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"0\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"0\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(8,	'Photos',	'Photos',	'',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"0\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(9,	'Last seen',	'LastSeen',	'',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(12,	'Email',	'Email',	'{\"ajax_check\":\"1\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	1,	0,	1,	0,	0,	0,	0,	1),
(13,	'Password',	'Password',	'{\"confirmation_for\":\"\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	1,	0,	1,	0,	0,	0,	0,	1),
(14,	'Repeat password',	'Password',	'{\"confirmation_for\":\"13\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	1,	0,	1,	0,	0,	0,	0,	1),
(16,	'Name',	'Text',	'{\"min_length\":\"\",\"max_length\":\"\",\"validation\":\"\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(18,	'About me',	'Textarea',	'{\"rows\":\"5\",\"cols\":\"\",\"allowed_tags\":\"iframe, a, object\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	1,	0,	1,	0,	0,	0,	0,	1),
(19,	'Relationship Status',	'Checkbox',	'{\"choices\":{\"default\":[\"Single\",\"In a relationship\",\"Long term relationship\",\"Engaged\",\"Married\",\"Divorced\"]},\"min_choices\":0,\"max_choices\":0,\"display_mode\":\"row\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(22,	'Country',	'Select',	'{\"choices\":{\"default\":[\"Afghanistan\",\"\\u00c5land Islands\",\"Albania\",\"Algeria\",\"American Samoa\",\"Andorra\",\"Angola\",\"Anguilla\",\"Antarctica\",\"Antigua and Barbuda\",\"Argentina\",\"Armenia\",\"Aruba\",\"Australia\",\"Austria\",\"Azerbaijan\",\"Bahamas\",\"Bahrain\",\"Bangladesh\",\"Barbados\",\"Belarus\",\"Belgium\",\"Belize\",\"Benin\",\"Bermuda\",\"Bhutan\",\"Bolivia\",\"Bosnia and Herzegovina\",\"Botswana\",\"Bouvet Island\",\"Brazil\",\"British Indian Ocean Territory\",\"Brunei Darussalam\",\"Bulgaria\",\"Burkina Faso\",\"Burundi\",\"Cambodia\",\"Cameroon\",\"Canada\",\"Cape Verde\",\"Cayman Islands\",\"Central African Republic\",\"Chad\",\"Chile\",\"China\",\"Christmas Island\",\"Cocos (Keeling) Islands\",\"Colombia\",\"Comoros\",\"Congo\",\"Congo, The Democratic Republic of The\",\"Cook Islands\",\"Costa Rica\",\"Cote D\'ivoire\",\"Croatia\",\"Cuba\",\"Cyprus\",\"Czech Republic\",\"Denmark\",\"Djibouti\",\"Dominica\",\"Dominican Republic\",\"Ecuador\",\"Egypt\",\"El Salvador\",\"Equatorial Guinea\",\"Eritrea\",\"Estonia\",\"Ethiopia\",\"Falkland Islands (Malvinas)\",\"Faroe Islands\",\"Fiji\",\"Finland\",\"France\",\"French Guiana\",\"French Polynesia\",\"French Southern Territories\",\"Gabon\",\"Gambia\",\"Georgia\",\"Germany\",\"Ghana\",\"Gibraltar\",\"Greece\",\"Greenland\",\"Grenada\",\"Guadeloupe\",\"Guam\",\"Guatemala\",\"Guernsey\",\"Guinea\",\"Guinea-bissau\",\"Guyana\",\"Haiti\",\"Heard Island and Mcdonald Islands\",\"Holy See (Vatican City State)\",\"Honduras\",\"Hong Kong\",\"Hungary\",\"Iceland\",\"India\",\"Indonesia\",\"Iran, Islamic Republic of\",\"Iraq\",\"Ireland\",\"Isle of Man\",\"Israel\",\"Italy\",\"Jamaica\",\"Japan\",\"Jersey\",\"Jordan\",\"Kazakhstan\",\"Kenya\",\"Kiribati\",\"Korea, Democratic People\'s Republic of\",\"Korea, Republic of\",\"Kuwait\",\"Kyrgyzstan\",\"Lao People\'s Democratic Republic\",\"Latvia\",\"Lebanon\",\"Lesotho\",\"Liberia\",\"Libyan Arab Jamahiriya\",\"Liechtenstein\",\"Lithuania\",\"Luxembourg\",\"Macao\",\"Macedonia, The Former Yugoslav Republic of\",\"Madagascar\",\"Malawi\",\"Malaysia\",\"Maldives\",\"Mali\",\"Malta\",\"Marshall Islands\",\"Martinique\",\"Mauritania\",\"Mauritius\",\"Mayotte\",\"Mexico\",\"Micronesia, Federated States of\",\"Moldova, Republic of\",\"Monaco\",\"Mongolia\",\"Montenegro\",\"Montserrat\",\"Morocco\",\"Mozambique\",\"Myanmar\",\"Namibia\",\"Nauru\",\"Nepal\",\"Netherlands\",\"Netherlands Antilles\",\"New Caledonia\",\"New Zealand\",\"Nicaragua\",\"Niger\",\"Nigeria\",\"Niue\",\"Norfolk Island\",\"Northern Mariana Islands\",\"Norway\",\"Oman\",\"Pakistan\",\"Palau\",\"Palestinian Territory, Occupied\",\"Panama\",\"Papua New Guinea\",\"Paraguay\",\"Peru\",\"Philippines\",\"Pitcairn\",\"Poland\",\"Portugal\",\"Puerto Rico\",\"Qatar\",\"Reunion\",\"Romania\",\"Russian Federation\",\"Rwanda\",\"Saint Helena\",\"Saint Kitts and Nevis\",\"Saint Lucia\",\"Saint Pierre and Miquelon\",\"Saint Vincent and The Grenadines\",\"Samoa\",\"San Marino\",\"Sao Tome and Principe\",\"Saudi Arabia\",\"Senegal\",\"Serbia\",\"Seychelles\",\"Sierra Leone\",\"Singapore\",\"Slovakia\",\"Slovenia\",\"Solomon Islands\",\"Somalia\",\"South Africa\",\"South Georgia and The South Sandwich Islands\",\"Spain\",\"Sri Lanka\",\"Sudan\",\"Suriname\",\"Svalbard and Jan Mayen\",\"Swaziland\",\"Sweden\",\"Switzerland\",\"Syrian Arab Republic\",\"Taiwan, Province of China\",\"Tajikistan\",\"Tanzania, United Republic of\",\"Thailand\",\"Timor-leste\",\"Togo\",\"Tokelau\",\"Tonga\",\"Trinidad and Tobago\",\"Tunisia\",\"Turkey\",\"Turkmenistan\",\"Turks and Caicos Islands\",\"Tuvalu\",\"Uganda\",\"Ukraine\",\"United Arab Emirates\",\"United Kingdom\",\"United States\",\"United States Minor Outlying Islands\",\"Uruguay\",\"Uzbekistan\",\"Vanuatu\",\"Venezuela\",\"Viet Nam\",\"Virgin Islands, British\",\"Virgin Islands, U.S.\",\"Wallis and Futuna\",\"Western Sahara\",\"Yemen\",\"Zambia\",\"Zimbabwe\"]},\"blank_choice\":\"1\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(23,	'Friends',	'Friends',	'',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(43,	'Google Maps Location',	'GoogleMapsLocation',	'{\"edit_height\":\"200\",\"edit_width\":\"100%\",\"view_height\":\"200\",\"view_width\":\"100%\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(25,	'Distance',	'Distance',	'{\"google_maps_field\":\"43\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"Maximum distance\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(26,	'Relationship',	'Relationship',	'{\"search_mode\":\"0\",\"display_mode\":\"photo\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"This is your chance to find single members in your area!\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"0\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(27,	'ReCaptcha',	'ReCaptcha',	'{\"theme\":\"red\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	1,	0,	1,	0,	0,	0,	0,	1),
(28,	'Spacer',	'Spacer',	'{\"height\":\"50\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(29,	'Terms and Conditions',	'Terms',	'{\"article\":\"1\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"0\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	1,	0,	1,	0,	0,	0,	0,	1),
(30,	'Profile rating',	'Rating',	'{\"view_mode\":\"1\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(41,	'Search Multiple',	'SearchMultiple',	'{\"searchable_fields\":[\"16\",\"3\"]}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"Name and Username\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(42,	'Radius Search',	'Radius',	'{\"google_maps_field\":\"43\",\"max_radius\":\"1000\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"Enter the radius you want to search for members in.\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"Radius\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(44,	'Big Spacer',	'Spacer',	'{\"height\":\"200\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(45,	'Here for',	'Checkbox',	'{\"choices\":{\"default\":[\"Friends\",\"Fun\",\"Dating\",\"Relationship\",\"Sex\"]},\"min_choices\":0,\"max_choices\":0,\"display_mode\":\"row\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(46,	'City',	'Text',	'{\"min_length\":\"\",\"max_length\":\"\",\"validation\":\"\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1),
(47,	'Surname',	'Text',	'{\"min_length\":\"\",\"max_length\":\"\",\"validation\":\"\"}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	'{\"view\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"edit\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"},\"search\":{\"enabled\":\"1\",\"default\":\"\",\"en-GB\":\"\"}}',	0,	0,	1,	0,	0,	0,	0,	1);

DROP TABLE IF EXISTS `#__lovefactory_fillin_notifications`;
CREATE TABLE `#__lovefactory_fillin_notifications` (
  `user_id` int(11) NOT NULL,
  `registered_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__lovefactory_friends`;
CREATE TABLE `#__lovefactory_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `message` mediumtext NOT NULL,
  `sender_status` tinyint(1) NOT NULL,
  `receiver_status` tinyint(1) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `pending` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sender_id` (`sender_id`),
  KEY `idx_receiver_id` (`receiver_id`),
  KEY `idx_pending` (`receiver_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__lovefactory_friends_requests`;
CREATE TABLE `#__lovefactory_friends_requests` (
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  KEY `sender_id_receiver_id_created_at` (`sender_id`,`receiver_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__lovefactory_gateways`;
CREATE TABLE `#__lovefactory_gateways` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `element` varchar(20) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `published` tinyint(1) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `params` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_group_bans`;
CREATE TABLE `#__lovefactory_group_bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `description` mediumtext NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_group_members`;
CREATE TABLE `#__lovefactory_group_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_group_posts`;
CREATE TABLE `#__lovefactory_group_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` mediumtext NOT NULL,
  `reported` tinyint(1) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_group_threads`;
CREATE TABLE `#__lovefactory_group_threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` mediumtext NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `reported` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_groups`;
CREATE TABLE `#__lovefactory_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `private` tinyint(4) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `reported` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_interactions`;
CREATE TABLE `#__lovefactory_interactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `type_id` tinyint(4) NOT NULL,
  `date` datetime NOT NULL,
  `seen` tinyint(1) NOT NULL,
  `responded` tinyint(1) NOT NULL,
  `deleted_by_sender` tinyint(1) NOT NULL,
  `deleted_by_receiver` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sender_id` (`sender_id`),
  KEY `idx_receiver_id` (`receiver_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_invoices`;
CREATE TABLE `#__lovefactory_invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `seller` mediumtext NOT NULL,
  `buyer` mediumtext NOT NULL,
  `membership` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `vat_rate` decimal(10,2) NOT NULL,
  `vat_value` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `issued_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_ips`;
CREATE TABLE `#__lovefactory_ips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `visits` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_item_comments`;
CREATE TABLE `#__lovefactory_item_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_type` varchar(20) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_user_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` mediumtext NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `reported` tinyint(1) NOT NULL,
  `read` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_type` (`item_type`),
  KEY `asset_id` (`item_id`),
  KEY `user_id` (`user_id`),
  KEY `item_user_id` (`item_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_memberships`;
CREATE TABLE `#__lovefactory_memberships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `default` tinyint(1) NOT NULL,
  `icon_extension` varchar(20) NOT NULL,
  `restrictions` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO `#__lovefactory_memberships` (`id`, `title`, `published`, `ordering`, `default`, `icon_extension`, `restrictions`) VALUES
(1,	'Free',	1,	1,	1,	'',	'{\"profile_access\":1,\"avatar_access\":1,\"message_access\":1,\"comment_photo_access\":1,\"comment_video_access\":1,\"comment_profile_access\":1,\"photos\":\"0\",\"videos\":\"0\",\"friends\":\"0\",\"messages\":\"0\",\"message_replies\":\"0\",\"interactions\":\"0\",\"shoutbox\":\"0\",\"chat_factory_access\":\"0\",\"blog_factory_access\":\"0\",\"friends_top\":\"0\",\"groups_create\":\"0\",\"groups_join\":\"0\",\"same_gender_interaction\":\"0\"}'),
(2,	'Starter',	1,	2,	0,	'',	'{\"profile_access\":1,\"avatar_access\":1,\"message_access\":1,\"comment_photo_access\":1,\"comment_video_access\":1,\"comment_profile_access\":1,\"photos\":\"1\",\"videos\":\"1\",\"friends\":\"1\",\"messages\":\"1\",\"message_replies\":\"0\",\"interactions\":\"1\",\"shoutbox\":\"0\",\"chat_factory_access\":\"0\",\"blog_factory_access\":\"0\",\"friends_top\":\"0\",\"groups_create\":\"0\",\"groups_join\":\"0\",\"same_gender_interaction\":\"0\"}'),
(3,	'Basic',	1,	3,	0,	'',	'{\"profile_access\":1,\"avatar_access\":1,\"message_access\":1,\"comment_photo_access\":1,\"comment_video_access\":1,\"comment_profile_access\":1,\"photos\":\"3\",\"videos\":\"3\",\"friends\":\"3\",\"messages\":\"5\",\"message_replies\":\"0\",\"interactions\":\"5\",\"shoutbox\":\"0\",\"chat_factory_access\":\"0\",\"blog_factory_access\":\"0\",\"friends_top\":\"1\",\"groups_create\":\"0\",\"groups_join\":\"0\",\"same_gender_interaction\":\"0\"}'),
(4,	'Advanced',	1,	4,	0,	'',	'{\"profile_access\":1,\"avatar_access\":1,\"message_access\":1,\"comment_photo_access\":1,\"comment_video_access\":1,\"comment_profile_access\":1,\"photos\":\"5\",\"videos\":\"5\",\"friends\":\"5\",\"messages\":\"10\",\"message_replies\":\"0\",\"interactions\":\"10\",\"shoutbox\":\"0\",\"chat_factory_access\":\"0\",\"blog_factory_access\":\"0\",\"friends_top\":\"3\",\"groups_create\":\"0\",\"groups_join\":\"0\",\"same_gender_interaction\":\"0\"}'),
(5,	'Expert',	1,	5,	0,	'',	'{\"profile_access\":1,\"avatar_access\":1,\"message_access\":1,\"comment_photo_access\":1,\"comment_video_access\":1,\"comment_profile_access\":1,\"photos\":\"10\",\"videos\":\"10\",\"friends\":\"10\",\"messages\":\"50\",\"message_replies\":\"0\",\"interactions\":\"50\",\"shoutbox\":\"0\",\"chat_factory_access\":\"0\",\"blog_factory_access\":\"0\",\"friends_top\":\"5\",\"groups_create\":\"0\",\"groups_join\":\"0\",\"same_gender_interaction\":\"0\"}'),
(6,	'Unlimited',	1,	6,	0,	'',	'{\"profile_access\":1,\"avatar_access\":1,\"message_access\":1,\"comment_photo_access\":1,\"comment_video_access\":1,\"comment_profile_access\":1,\"photos\":\"-1\",\"videos\":\"-1\",\"friends\":\"-1\",\"messages\":\"-1\",\"message_replies\":\"0\",\"interactions\":\"-1\",\"shoutbox\":\"2\",\"chat_factory_access\":\"0\",\"blog_factory_access\":\"0\",\"friends_top\":\"-1\",\"groups_create\":\"-1\",\"groups_join\":\"-1\",\"same_gender_interaction\":\"0\"}');

DROP TABLE IF EXISTS `#__lovefactory_memberships_sold`;
CREATE TABLE `#__lovefactory_memberships_sold` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `membership_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `start_membership` datetime NOT NULL,
  `end_membership` datetime NOT NULL,
  `end_notification` tinyint(1) NOT NULL,
  `expired` tinyint(4) NOT NULL,
  `months` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `default` tinyint(1) NOT NULL,
  `trial` int(11) NOT NULL,
  `restrictions` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_membership_id` (`membership_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__lovefactory_messages`;
CREATE TABLE `#__lovefactory_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` mediumtext NOT NULL,
  `unread` tinyint(1) NOT NULL,
  `reported` tinyint(1) NOT NULL,
  `deleted_by_sender` tinyint(1) NOT NULL,
  `deleted_by_receiver` tinyint(1) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sender_id` (`sender_id`),
  KEY `idx_receiver_id` (`receiver_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_notifications`;
CREATE TABLE `#__lovefactory_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` mediumtext NOT NULL,
  `lang_code` varchar(10) NOT NULL,
  `groups` mediumtext NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__lovefactory_notifications` (`id`, `type`, `subject`, `body`, `lang_code`, `groups`, `published`) VALUES
(15,	'comment_received',	'New comment received!',	'<p>Hello %%receiver_username%%!</p><p>You have received a new comment from %%sender_username%%:</p><p>\"%%message%%\"</p><p>Regards,<br />Love Factory Team</p>',	'*',	'',	1),
(14,	'signup_with_user_activation',	'Account Details for %%name%% at %%site_url%%',	'<p>Hello %%name%%,</p><p>Thank you for registering at <a href=\"%%site_url%%\">%%site_name%%</a>. Your account is created and must be activated before you can use it.</p><p> </p><p>To activate the account click on the following link or copy-paste it in your browser:</p><p><a href=\"%%activation_link%%\">%%activation_link%%</a></p><p> </p><p>After activation you may login to <a href=\"%%site_url%%\">%%site_url%%</a> using the following username and password:</p><p>Username: %%username%%</p><p>Password: %%password%%</p>',	'*',	'',	1),
(20,	'comment_received',	'New comment received!',	'<p>Hello %%receiver_username%%!</p><p>You have received a new comment from %%sender_username%%:</p><p>\"%%message%%\"</p><p>Regards,<br />Love Factory Team</p>',	'en-GB',	'',	1),
(21,	'interaction_received',	'New interaction received!',	'<p>Hello %%receiver_username%%!</p><p>You have received a new interaction from %%sender_username%%!</p><p><br />Regards,</p><p>Love Factory Team!</p>',	'*',	'',	1),
(22,	'message_received',	'New message received!',	'<p>Hello %%receiver_username%%!</p><p>You have received a new message from %%sender_username%%:</p><p>\"%%message_body%%\"</p><p>Regards,</p><p>Love Factory Team!</p>',	'*',	'',	1),
(23,	'comment_photo_received',	'New photo comment received!',	'<p>Hello %%receiver_username%%!</p><p> </p><p>You have received a new photo comment from %%sender_username%%!</p><p>\"%%message%%\"</p><p> </p><p>Regards,</p><p>Love Factory Team!</p>',	'*',	'',	1),
(24,	'comment_video_received',	'New video comment received!',	'<p>Hello %%receiver_username%%!</p><p> </p><p>You have received a new video comment from %%sender_username%%!</p><p>\"%%message%%\"</p><p> </p><p>Regards,</p><p>Love Factory Team!</p>',	'*',	'',	1),
(25,	'rating_received',	'New rating received!',	'<p>Hello %%receiver_username%%!</p><p><br />You have received a new rating of %%rating%% from %%sender_username%%!</p><p><br />Regards,</p><p>Love Factory Team!</p>',	'*',	'',	1),
(26,	'membership_ending',	'Membership ending!',	'<p>Hello %%receiver_username%%!</p><p> </p><p>Your membership is about to expire in less than %%days%% day(s).</p><p> </p><p>Regards,<br />Love Factory Team</p>',	'*',	'',	1),
(27,	'signup_without_activation',	'Account Details for %%name%% at %%site_name%%',	'<p>Hello %%name%%,</p><p> </p><p>Thank you for registering at <a href=\"%%site_url%%\">%%site_name%%</a>.</p><p>You may now log in to <a href=\"%%site_url%%\">%%site_url%%</a> using the username and password you registered with.</p><p> </p><p>Regards,</p><p>Love Factory Team!</p>',	'*',	'',	1),
(28,	'signup_with_admin_activation',	'Account Details for %%name%% at %%site_name%% ',	'<p>Hello %%name%%,</p><p>Thank you for registering at <a href=\"%%site_url%%\">%%site_name%%</a>. Your account is created and must be verified before you can use it.</p><p>To verify the account click on the following link or copy-paste it in your browser:</p><p><a href=\"%%activation_link%%\">%%activation_link%%</a></p><p> </p><p>After verification an administrator will be notified to activate your account. You\'ll receive a confirmation when it\'s done.</p><p>Once that account has been activated you may login to <a href=\"%%site_url%%\">%%site_url%%</a> using the following username and password:</p><p>Username: %%username%%</p><p>Password: %%password%%</p>',	'*',	'',	1),
(29,	'offline_payment',	'Offline payment details',	'<p>Hello %%receiver_username%%!</p><p> </p><p>Here are the bank details for the order %%order_id%%:</p><p>%%bank_details%%</p><p> </p><p>Regards,</p><p>Love Factory Team!</p>',	'*',	'',	1),
(30,	'item_pending_approval',	'New item pending approval!',	'<p>Hello %%receiver_username%%!</p><p> </p><p>A new item of type <strong>%%item_type%%</strong> is pending approval!</p><p> </p><p>Regards,</p><p>Love Factory Team</p>',	'*',	'{\"0\":\"8\"}',	1);

DROP TABLE IF EXISTS `#__lovefactory_orders`;
CREATE TABLE `#__lovefactory_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `membership_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `gateway` varchar(50) NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `membership` mediumtext NOT NULL,
  `price` mediumtext NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_pages`;
CREATE TABLE `#__lovefactory_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `fields` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__lovefactory_pages` (`id`, `type`, `title`, `fields`) VALUES
(1,	'registration',	'Registration',	'{\"0\":{\"titles\":{\"default\":\"Account Details\",\"en-GB\":\"\"},\"setup\":[[\"3\",\"12\",\"13\",\"14\"]],\"columns\":[\"6\"]},\"1\":{\"titles\":{\"default\":\"Basic Details\",\"en-GB\":\"\"},\"setup\":[[\"16\",\"47\",\"4\",\"5\",\"6\"],[\"19\",\"45\",\"2\"]],\"columns\":[\"6\",\"6\"]},\"2\":{\"titles\":{\"default\":\"Location\",\"en-GB\":\"\"},\"setup\":[[\"46\",\"22\"]],\"columns\":[\"6\"]},\"3\":{\"titles\":{\"default\":\"Terms and Security\",\"en-GB\":\"\"},\"setup\":[[\"27\",\"29\"]],\"columns\":[\"6\"]}}'),
(2,	'profile_edit',	'Profile Edit',	'{\"0\":{\"titles\":{\"default\":\"Basic Details\",\"en-GB\":\"\"},\"setup\":[[\"16\",\"47\",\"4\"],[\"6\",\"5\"]],\"columns\":[\"6\",\"6\"]},\"1\":{\"titles\":{\"default\":\"More Information\",\"en-GB\":\"\"},\"setup\":[[\"19\",\"45\"],[\"46\",\"22\"]],\"columns\":[\"6\",\"6\"]},\"2\":{\"titles\":{\"default\":\"About Me\",\"en-GB\":\"\"},\"setup\":[[\"18\"]],\"columns\":[\"12\"]}}'),
(3,	'search_quick',	'Search',	'{\"0\":{\"titles\":{\"default\":\"Search\",\"en-GB\":\"\"},\"setup\":[[\"41\"]],\"columns\":[\"6\"]}}'),
(4,	'search_advanced',	'Search for a match',	'{\"0\":{\"titles\":{\"default\":\"Basic Details\",\"en-GB\":\"\"},\"setup\":[[\"4\",\"5\",\"6\"],[\"7\",\"8\"]],\"columns\":[\"6\",\"6\"]},\"1\":{\"titles\":{\"default\":\"More information\",\"en-GB\":\"\"},\"setup\":[[\"19\",\"45\"],[\"46\",\"22\"]],\"columns\":[\"6\",\"6\"]}}'),
(5,	'profile_results',	'Profile Search',	'{\"0\":{\"titles\":{\"default\":\"%%username%%\",\"en-GB\":\"\"},\"setup\":[[\"2\",\"7\"],[\"16\",\"6\",\"46\"],[\"4\",\"5\"]],\"columns\":[\"3\",\"5\",\"4\"]}}'),
(6,	'profile_view',	'Profile View',	'{\"0\":{\"titles\":{\"default\":\"%%username%%\",\"en-GB\":\"\"},\"setup\":[[\"2\",\"7\"],[\"16\",\"47\",\"6\"],[\"4\",\"5\"]],\"columns\":[\"3\",\"5\",\"4\"]},\"1\":{\"titles\":{\"default\":\"More Information\",\"en-GB\":\"\"},\"setup\":[[\"19\",\"46\",\"23\",\"1\"],[\"45\",\"22\",\"8\",\"3\"]],\"columns\":[\"6\",\"6\"]},\"2\":{\"titles\":{\"default\":\"About Me\",\"en-GB\":\"\"},\"setup\":[[\"18\"]],\"columns\":[\"12\"]}}'),
(7,	'friends_view',	'Friends list view',	'{\"0\":{\"titles\":{\"default\":\"%%username%%\",\"en-GB\":\"\"},\"setup\":[[\"2\",\"7\"],[\"16\",\"6\",\"46\"],[\"4\",\"5\"]],\"columns\":[\"3\",\"5\",\"4\"]}}'),
(8,	'profile_fillin',	'Profile Fillin',	'{\"0\":{\"titles\":{\"default\":\"Basic Details\",\"en-GB\":\"\"},\"setup\":[[\"4\",\"5\",\"6\"],[\"2\"]],\"columns\":[\"6\",\"6\"]},\"1\":{\"titles\":{\"default\":\"Basic Information\",\"en-GB\":\"\"},\"setup\":[[\"16\",\"47\"],[\"19\",\"45\"]],\"columns\":[\"6\",\"6\"]},\"2\":{\"titles\":{\"default\":\"Location\",\"en-GB\":\"\"},\"setup\":[[\"46\",\"22\"]],\"columns\":[\"6\"]}}'),
(9,	'profile_map',	'Map member info',	'{\"0\":{\"titles\":{\"default\":\"%%username%%\",\"en-GB\":\"\"},\"setup\":[[\"2\"],[\"16\",\"4\",\"5\"]],\"columns\":[\"3\",\"8\"]}}'),
(10,	'radius_search',	'Radius search',	'{\"0\":{\"titles\":{\"default\":\"\",\"en-GB\":\"\"},\"setup\":[[\"42\",\"4\"]],\"columns\":[\"6\"]}}');

DROP TABLE IF EXISTS `#__lovefactory_payments`;
CREATE TABLE `#__lovefactory_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `received_at` int(11) NOT NULL,
  `payment_date` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `gateway` int(11) NOT NULL,
  `refnumber` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `data` mediumtext NOT NULL,
  `errors` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_photos`;
CREATE TABLE `#__lovefactory_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `reported` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_pricing`;
CREATE TABLE `#__lovefactory_pricing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `published` tinyint(1) NOT NULL,
  `membership_id` tinyint(4) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `months` int(11) NOT NULL,
  `gender_prices` mediumtext NOT NULL,
  `is_trial` tinyint(1) NOT NULL,
  `available_interval` tinyint(1) NOT NULL,
  `available_from` datetime NOT NULL,
  `available_until` datetime NOT NULL,
  `new_trial` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_profile_updates`;
CREATE TABLE `#__lovefactory_profile_updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `profile` mediumtext NOT NULL,
  `created_at` datetime NOT NULL,
  `pending` tinyint(1) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_pending` (`pending`),
  KEY `idx_approved` (`approved`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_profile_visitors`;
CREATE TABLE `#__lovefactory_profile_visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `visitor_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_visitor_id` (`visitor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_profiles`;
CREATE TABLE `#__lovefactory_profiles` (
  `user_id` int(11) unsigned NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `filled` tinyint(1) NOT NULL,
  `online` tinyint(1) NOT NULL,
  `validated` tinyint(1) NOT NULL,
  `banned` tinyint(1) NOT NULL,
  `membership_sold_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `loggedin` tinyint(1) NOT NULL,
  `lastvisit` bigint(20) unsigned NOT NULL,
  `params` mediumtext NOT NULL,
  `rating` decimal(4,2) NOT NULL,
  `votes` int(11) NOT NULL,
  `main_photo` int(11) NOT NULL,
  `relationship` int(11) NOT NULL,
  `trials` int(11) NOT NULL,
  `status` mediumtext NOT NULL,
  `sex` mediumtext,
  `sex_visibility` tinyint(1) DEFAULT NULL,
  `looking` mediumtext,
  `looking_visibility` tinyint(1) DEFAULT NULL,
  `field_6` mediumtext,
  `field_6_visibility` tinyint(1) DEFAULT NULL,
  `field_16` mediumtext,
  `field_16_visibility` tinyint(1) DEFAULT NULL,
  `field_18` mediumtext,
  `field_18_visibility` tinyint(1) DEFAULT NULL,
  `field_19` mediumtext,
  `field_19_visibility` tinyint(1) DEFAULT NULL,
  `field_22` mediumtext,
  `field_22_visibility` tinyint(1) DEFAULT NULL,
  `field_43_lat` decimal(15,12) DEFAULT NULL,
  `field_43_lng` decimal(15,12) DEFAULT NULL,
  `field_43_zoom` tinyint(1) DEFAULT NULL,
  `field_43_visibility` tinyint(1) DEFAULT NULL,
  `field_46` mediumtext,
  `field_46_visibility` tinyint(1) DEFAULT NULL,
  `field_45` mediumtext,
  `field_45_visibility` tinyint(1) DEFAULT NULL,
  `field_47` mediumtext,
  `field_47_visibility` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `idx_online` (`online`),
  KEY `idx_validated` (`validated`),
  KEY `idx_banned` (`banned`),
  KEY `idx_membership_sold_id` (`membership_sold_id`),
  KEY `idx_loggedin` (`loggedin`),
  KEY `idx_lastvisit` (`lastvisit`),
  KEY `idx_main_photo` (`main_photo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_ratings`;
CREATE TABLE `#__lovefactory_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `rating` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sender_id` (`sender_id`),
  KEY `idx_receiver_id` (`receiver_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_reports`;
CREATE TABLE `#__lovefactory_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reporting_id` int(11) NOT NULL,
  `element` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `comment` mediumtext NOT NULL,
  `reported_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `text` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_shoutbox`;
CREATE TABLE `#__lovefactory_shoutbox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sender_id` (`sender_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_statistics_per_day`;
CREATE TABLE `#__lovefactory_statistics_per_day` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `date_messages` date NOT NULL,
  `messages` int(11) NOT NULL,
  `date_interactions` date NOT NULL,
  `interactions` int(11) NOT NULL,
  `date_message_replies` date NOT NULL,
  `message_replies` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `#__lovefactory_videos`;
CREATE TABLE `#__lovefactory_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code` mediumtext NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `status` tinyint(4) NOT NULL,
  `ordering` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `reported` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


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

DROP TABLE IF EXISTS `#__lovefactory_searches`;
CREATE TABLE `#__lovefactory_searches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `search` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
