<?php
/*	Project:	EQdkp-Plus
 *	Package:	GuildRequest Plugin
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2015 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('EQDKP_INC')){
	header('HTTP/1.0 404 Not Found');exit;
}

$itemprioSQL = array(
	'uninstall' => array(
		1	=> 'DROP TABLE IF EXISTS `__itemprio`',
	),

	'install'   => array(
		1	=> "CREATE TABLE `__itemprio` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`itemname` VARCHAR(255) NULL DEFAULT NULL,
	`itemid` VARCHAR(255) NULL DEFAULT NULL,
	`memberid` INT(11) UNSIGNED NOT NULL,
	`prio` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '1',
	`eventid` INT(11) UNSIGNED NOT NULL,
	`revision` INT(10) UNSIGNED NOT NULL DEFAULT '1',
	`time` INT(10) UNSIGNED NOT NULL,
	`userid` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`given` TINYINT(2) UNSIGNED NOT NULL DEFAULT '0',
	`hash` VARCHAR(255) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `FK_eqdkp23_itemprio_eqdkp23_members` (`memberid`),
	INDEX `FK_eqdkp23_itemprio_eqdkp23_events` (`eventid`),
	CONSTRAINT `FK_eqdkp23_itemprio_eqdkp23_events` FOREIGN KEY (`eventid`) REFERENCES `__events` (`event_id`) ON UPDATE NO ACTION ON DELETE CASCADE,
	CONSTRAINT `FK_eqdkp23_itemprio_eqdkp23_members` FOREIGN KEY (`memberid`) REFERENCES `__members` (`member_id`) ON UPDATE NO ACTION ON DELETE CASCADE
);
")
		);
?>