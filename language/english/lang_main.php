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

$lang = array(
	'itemprio'					=> 'Item Priority list',

	// Description
	'itemprio_short_desc'		=> 'Item Priority list',
	'itemprio_long_desc'		=> 'Characters can add priorities of wanted items',

	'ip_itemprios'				=> 'Item Priority list',
	'ip_myitemprios'			=> 'My Item Priority lists',
	'ip_config_saved'			=> 'Settings have been saved successfully.',
	'ip_fs_general'				=> 'General',
	'ip_fs_items'				=> 'Items',
	'ip_f_item_count'			=> 'Number of Items per Event',
	'ip_f_item_new_allgone'		=> 'New items may only be entered when all items from the list have been assigned',
	'ip_f_events'				=> 'Select Events',
	'ip_f_twinks'				=> 'Item lists for Twinks',
	'ip_view'					=> 'View',
	'ip_use'					=> 'Create priority lists',
	'ip_change_items'			=> 'Change Items on the list',
	'ip_change_order'			=> 'Change priority of Items',
	'ip_distribute'				=> 'Distribute Items',
	'ip_plugin_not_installed'	=> 'This Plugin is currently not installed.',
	'ip_itemname'				=> 'Itemname',
	'ip_itemid'					=> 'Game Item-ID',
	'ip_searchitem'				=> 'Search for Item',
	'ip_hint_allgone'			=> 'You cannot add new items until you have received all items on your priority list.',
	'ip_dragndrop'				=> 'Hold and move to desired position',
	'ip_given_success'			=> 'The item \'%1$s\' was given to %2$s.',
	'ip_notify_new_item'		=> 'You have received the Item \'{PRIMARY}\' from your priority list.',
	'ip_notify_new_item_grouped' => 'You have received {COUNT} from your priority lists.',
	'ip_throw'					=> 'Randomly give item',
	'ip_give'					=> 'Give item',
);

?>
