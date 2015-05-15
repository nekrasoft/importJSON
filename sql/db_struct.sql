CREATE TABLE `category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL default 0,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL default '',
  `status` tinyint(1) unsigned NOT NULL default 1,
  PRIMARY KEY (`id`),
  KEY `idx_parent_id` (`parent_id`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE `news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `description` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `text` text CHARACTER SET utf8mb4 NOT NULL,
  `image` varchar(255) DEFAULT '',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_date` (`date`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
