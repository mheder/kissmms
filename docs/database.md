```
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

CREATE TABLE `auxi_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txkey` varchar(128) NOT NULL COMMENT 'Key',
  `lang` varchar(4) NOT NULL COMMENT 'Lang',
  `content` text DEFAULT NULL COMMENT 'Value',
  PRIMARY KEY (`id`),
  UNIQUE KEY `en_url` (`txkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Translations';

INSERT INTO `auxi_lang` (`id`, `txkey`, `lang`, `content`) VALUES
(1,	'pagetitle_profile.php',	'en',	'Community profile'),
(2,	'bannerabouttitle',	'en',	'About'),
(3,	'bannerabout',	'en',	'KISSMMS is a lightweight Account Registry and Membership Managements system.'),
(4,	'bannertitle_profile.php',	'en',	'Community Profile'),
(5,	'menuitem_reg.php',	'en',	'Registration'),
(6,	'menuitem_aup_new_user.php',	'en',	'Terms and Conditions'),
(8,	'bannertitle_aup_new_user.php',	'en',	'AUP'),
(9,	'attribute_name',	'en',	'Attribute Name'),
(10,	'attribute_value',	'en',	'Value'),
(11,	'need_agree_policy',	'en',	'Please indicate that you have read and agree to the Terms and Conditions and Privacy Policy'),
(12,	'agree_policy',	'en',	'I have read and agree to the <a href=\"aup_new_user.php\">Terms and Conditions and Privacy Policy</a>'),
(13,	'footer_content',	'en',	'kissreg portal for onboarding users'),
(14,	'attribute_email',	'en',	'Email'),
(15,	'attribute_first_name',	'en',	'First Name'),
(16,	'attribute_last_name',	'en',	'Last Name'),
(17,	'no_iuid_received',	'en',	'No IUID values received!'),
(18,	'attribute_cuid',	'en',	'Community Identifier'),
(19,	'attribute_nickname',	'en',	'Nickname'),
(20,	'your_account_data',	'en',	'Your Account'),
(21,	'cuid_conflict',	'en',	'Community Identifier Conflict!'),
(22,	'aup_unchecked',	'en',	'You need to accept the AUP!'),
(23,	'missing required_attr',	'en',	'Required Attribute Missing: %s !'),
(24,	'account_successfully_saved',	'en',	'Account Successfully Saved!'),
(25,	'attribute_source_entity_id',	'en',	'Source'),
(26,	'bannertitle_acc_man.php',	'en',	'Account Management'),
(27,	'pagetitle_acc_man.php',	'en',	'Account Management'),
(28,	'source_entity_id',	'en',	'Source'),
(29,	'created_at',	'en',	'Created at'),
(30,	'multiple_cuids',	'en',	'There are multiple CUIDs!'),
(31,	'remote_act',	'en',	'Remote Account'),
(32,	'cuid',	'en',	'Community identifier'),
(33,	'attribute',	'en',	'Attribute'),
(34,	'value',	'en',	'Value'),
(35,	'extrainfo',	'en',	'Info'),
(36,	'pagetitle_reg.php',	'en',	'New Account'),
(37,	'bannertitle_reg.php',	'en',	'New Account'),
(38,	'already_have_account_no_reg',	'en',	'You already have an account, therefore, new account registration is not possible.'),
(39,	'attribute_assurance',	'en',	'Assurance Level'),
(40,	'pagetitle_reg_save.php',	'en',	'Account Saved'),
(41,	'bannertitle_reg_save.php',	'en',	'Account Saved'),
(42,	'menuitem_acc_man.php',	'en',	'Manage Account'),
(43,	'no_incoming_form',	'en',	'No incoming form data!'),
(44,	'your_cuid',	'en',	'Your Community Identifier is <b>%s</b>.'),
(45,	'al_head_text',	'en',	'Begin Account Linking'),
(46,	'al_step_1',	'en',	'By clicking on the button below, you will be asked to log in again. Use this time the other account you want to link to this one.'),
(47,	'your_current_source',	'en',	'Your current authentication source is <b>%s</b>. '),
(48,	'pagetitle_acc_link.php',	'en',	'Account Linking'),
(49,	'bannertitle_acc_link.php',	'en',	'Account Linking'),
(50,	'cont_account_linking',	'en',	'Continue with the account linking'),
(51,	'this_remote_already_linked',	'en',	'This remote account is already linked. Please try again!'),
(52,	'account_linking_success',	'en',	'Account Linking Successful!'),
(53,	'acc_link_account_unknown',	'en',	'This account is unknown to the system. You cannot start account linking with an unknown account! You should register or log in with an existing account instead.'),
(54,	'unknown_user',	'en',	'Your user is not known to the system. You should register first.'),
(55,	'menuitem_acc_link.php',	'en',	'Account Linking'),
(56,	'attribute_source_id',	'en',	'Source'),
(57,	'your_new_account',	'en',	'Your New Account'),
(58,	'current_source',	'en',	'Currently logged in from'),
(59,	'edit_attribute',	'en',	'Edit Attribute'),
(60,	'attribute_read_only',	'en',	'(Read-only attribute)'),
(61,	'undefined_attribute',	'en',	'Undefined attribute: %s !'),
(62,	'attr_not_editable',	'en',	'Attribute not editable: %s !'),
(63,	'no_value',	'en',	'(no value)'),
(64,	'attribute_orcid',	'en',	'ORCID'),
(65,	'attr_edit_head',	'en',	'Edit Attribute Value'),
(66,	'save_attr_value',	'en',	'Save these values'),
(67,	'bannertitle_attr_edit.php',	'en',	'Edit Attribute'),
(68,	'pagetitle_attr_edit.php',	'en',	'Edit Attribute'),
(69,	'you_cannot_edit_this_attribute',	'en',	'You cannot edit this attribute.'),
(70,	'value_validation_fail',	'en',	'Invalid attribute value: %s. '),
(71,	'pagetitle_attr_edit_save.php',	'en',	'Edit Attribute'),
(72,	'bannertitle_attr_edit_save.php',	'en',	'Edit Attribute'),
(73,	'attribute_nickname_validation_info',	'en',	'Nickname should be a non-empty string made of alphanumeric characters or underscore.'),
(74,	'invalid_redirect_url',	'en',	'Invalid redirect url: %s!'),
(75,	'assurance',	'en',	'Assurance'),
(76,	'source',	'en',	'Source'),
(77,	'updated_at',	'en',	'Last Update'),
(78,	'pagetitle_',	'en',	'Manage Account'),
(79,	'bannertitle_',	'en',	'Manage Account'),
(80,	'menuitem_index.php',	'en',	'Manage Account'),
(81,	'pagetitle_index.php',	'en',	'Manage Account'),
(82,	'bannertitle_index.php',	'en',	'Manage Account'),
(83,	'email_verified',	'en',	'Email successfully verified: %s'),
(84,	'token_error',	'en',	'Token verification error'),
(85,	'pagetitle_email_verify.php',	'en',	'Verify Email'),
(86,	'bannertitle_email_verify.php',	'en',	'Verify Email'),
(87,	'token_already_consumed',	'en',	'Email already verified: %s.'),
(88,	'pagetitle_audit_logs.php',	'en',	'Audit Logs'),
(89,	'bannertitle_audit_logs.php',	'en',	'Audit Logs'),
(90,	'your_audit_logs',	'en',	'Your Audit Logs'),
(91,	'actor_cuid',	'en',	'Actor'),
(92,	'target_cuid',	'en',	'Target'),
(93,	'action',	'en',	'Action'),
(94,	'data',	'en',	'Data'),
(95,	'timestamp',	'en',	'Timestamp');

CREATE TABLE `auxi_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txkey` varchar(128) NOT NULL COMMENT 'Key',
  `lang` varchar(4) NOT NULL COMMENT 'Lang',
  `content` text DEFAULT NULL COMMENT 'Content',
  PRIMARY KEY (`id`),
  UNIQUE KEY `en_url` (`txkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='StaticContent';

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
```