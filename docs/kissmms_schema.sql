SET NAMES utf8;
SET time_zone = '+00:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

/*
# uncomment this block if you want also to drop the tables before creating
# naturally all of your data will be lost

DROP TABLE IF EXISTS `accounts`;
DROP TABLE IF EXISTS `attributes`;
DROP TABLE IF EXISTS `attribute_defs`;
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `auxi_lang`;
DROP TABLE IF EXISTS `auxi_pages`;
DROP TABLE IF EXISTS `email_tokens`;
DROP TABLE IF EXISTS `iuids`;
DROP TABLE IF EXISTS `remote_accounts`;
*/

CREATE TABLE `accounts` (
  `cuid` varchar(64) NOT NULL COMMENT 'CUID',
  `is_complete` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'IsComplete',
  `is_disabled` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'IsDisabled',
  `is_deleted` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'IsDeleted',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp() COMMENT 'CreatedAt',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp() COMMENT 'UpdatedAt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Accounts';


CREATE TABLE `attributes` (
  `cuid` varchar(64) NOT NULL COMMENT 'CUID',
  `name` varchar(64) NOT NULL COMMENT 'Name',
  `value` text NOT NULL COMMENT 'Value',
  `source` varchar(128) NOT NULL DEFAULT 'user_input' COMMENT 'Source',
  `assurance` varchar(128) DEFAULT NULL COMMENT 'Assurance',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Updated At',
  UNIQUE KEY `cuid_name_source` (`cuid`,`name`,`source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Attributes';

CREATE TABLE `attribute_defs` (
  `name` varchar(32) NOT NULL COMMENT 'AttributeName',
  `required` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Required',
  `multival` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Multival',
  `customizable` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Customizable',
  `displayed` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Displayed',
  `validator_regex` varchar(256) DEFAULT 'NULL' COMMENT 'ValidatorRegex'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='AttributeDefs';

INSERT INTO `attribute_defs` (`name`, `required`, `multival`, `customizable`, `displayed`, `validator_regex`) VALUES
('orcid',	'N',	'N',	'Y',	'Y',	'NULL'),
('email',	'Y',	'Y',	'N',	'Y',	'NULL'),
('first_name',	'Y',	'N',	'Y',	'Y',	'NULL'),
('last_name',	'Y',	'N',	'Y',	'Y',	'NULL'),
('assurance',	'N',	'N',	'N',	'Y',	'NULL'),
('nickname',	'Y',	'N',	'Y',	'Y',	'/^[\\w]+$/'),
('source_id',	'Y',	'N',	'N',	'Y',	'NULL');

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actor_cuid` varchar(128) NOT NULL COMMENT 'Actor',
  `target_cuid` varchar(128) NOT NULL COMMENT 'Target',
  `action` varchar(128) NOT NULL COMMENT 'Action',
  `connection` varchar(128) DEFAULT NULL COMMENT 'Connection',
  `data` varchar(128) DEFAULT NULL COMMENT 'Data',
  `timestamp` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Audit Log';

CREATE TABLE `email_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('invite','verify') NOT NULL COMMENT 'Type',
  `token` varchar(128) NOT NULL COMMENT 'Token',
  `sender_cuid` varchar(128) DEFAULT NULL COMMENT 'Sender',
  `email` varchar(128) NOT NULL COMMENT 'Email Address',
  `sent_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Sent At',
  `consumed_at` datetime DEFAULT NULL COMMENT 'Consumed At',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Email Tokens';

CREATE TABLE `iuids` (
  `iuid` varchar(128) NOT NULL COMMENT 'IUID',
  `remote_account_id` int(11) NOT NULL COMMENT 'Remote Account Id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='IUIDs';

CREATE TABLE `remote_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `cuid` varchar(64) NOT NULL COMMENT 'CUID',
  `source_id` varchar(256) NOT NULL COMMENT 'Source entityID',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp() COMMENT 'Created at',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Updated at',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Remote Accounts';

CREATE TABLE `auxi_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txkey` varchar(128) NOT NULL COMMENT 'Key',
  `lang` varchar(4) NOT NULL COMMENT 'Lang',
  `content` text DEFAULT NULL COMMENT 'Value',
  PRIMARY KEY (`id`),
  UNIQUE KEY `en_url` (`txkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Translations';

CREATE TABLE `auxi_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txkey` varchar(128) NOT NULL COMMENT 'Key',
  `lang` varchar(4) NOT NULL COMMENT 'Lang',
  `content` text DEFAULT NULL COMMENT 'Content',
  PRIMARY KEY (`id`),
  UNIQUE KEY `en_url` (`txkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='StaticContent';