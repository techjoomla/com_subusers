--
-- Table structure for table `#__tjsu_roles`
--

CREATE TABLE IF NOT EXISTS `#__tjsu_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'The name of the role',
  `client` varchar(255) NOT NULL COMMENT 'The client name Eg. com_tjlms, com_jlike',
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Table structure for table `#__tjsu_actions`
--

CREATE TABLE IF NOT EXISTS `#__tjsu_actions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL COMMENT 'The unique string of the action Eg. core.view.course',
  `name` varchar(255) NOT NULL COMMENT 'Short intro of action',
  `client` varchar(255) NOT NULL COMMENT 'The client name Eg. com_tjlms, com_jlike',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__tjsu_role_action_map`
--

CREATE TABLE IF NOT EXISTS `#__tjsu_role_action_map` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL COMMENT 'FK to roles table',
  `action_id` int(11) NOT NULL COMMENT 'FK to actions table',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__tjsu_users`
--

CREATE TABLE IF NOT EXISTS `#__tjsu_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'FK to users table',
  `role_id` int(11) NOT NULL COMMENT 'FK to roles table',
  `client` varchar(255) NOT NULL COMMENT 'The client name Eg. com_tjlms, com_jlike',
  `client_id` int(11) NOT NULL COMMENT 'The client content id.',
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
  KEY `user_id_idx` (`user_id`)
  KEY `client_id_idx` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__tjsu_roles` (`id`, `name`, `client`, `created_by`, `created_date`, `modified_by`, `modified_date`, `ordering`) VALUES
(1,	'Organization Manager',	'com_multiagency',	0,	'0000-00-00 00:00:00',	0,	'0000-00-00 00:00:00',	1),
(2,	'Organization User',	'com_multiagency',	0,	'0000-00-00 00:00:00',	0,	'0000-00-00 00:00:00',	2);

INSERT INTO `#__tjsu_actions` (`id`, `code`, `name`, `client`, `created_date`) VALUES
(1,	'core.agency.user.delete',	'Remove user from agency',	'com_multiagency',	'0000-00-00 00:00:00'),
(2,	'core.agency.delete', 'Remove agency', 'com_multiagency', '0000-00-00 00:00:00');
