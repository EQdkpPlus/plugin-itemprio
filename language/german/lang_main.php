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
	'itemprio'					=> 'Item-Prioritätenliste',

	// Description
	'itemprio_short_desc'		=> 'Item-Prioritätenliste',
	'itemprio_long_desc'		=> 'Charaktere können Prioritäten von gewünschten Items festlegen',

	'ip_itemprios'				=> 'Item-Prioritätenliste',
	'ip_myitemprios'			=> 'Meine Item-Prioritätenliste',
	'ip_config_saved'			=> 'Die Einstellungen wurden gespeichert.',
	'ip_fs_general'				=> 'Allgemeines',
	'ip_fs_items'				=> 'Items',
	'ip_f_item_count'			=> 'Anzahl an Items pro Event',
	'ip_f_item_new_allgone'		=> 'Neue Items dürfen erst eingetragen werden, wenn alle vorhandenen Items vergeben sind',
	'ip_f_events'				=> 'Events auswählen',
	'ip_f_twinks'				=> 'Item-Listen für Twinks',
	'ip_f_item_new_allrequired' => 'Es müssen alle Items pro Event eingetragen werden',
	'ip_view'					=> 'Ansehen',
	'ip_use'					=> 'Prioritätenliste erstellen',
	'ip_change_items'			=> 'Items auf den Listen austauschen',
	'ip_change_order'			=> 'Priorität der Items verändern',
	'ip_distribute'				=> 'Items verteilen',
	'ip_plugin_not_installed'	=> 'Dieses Plugin ist derzeit nicht installiert.',
	'ip_itemname'				=> 'Itemname',
	'ip_itemid'					=> 'Game Item-ID',
	'ip_searchitem'				=> 'Nach Item suchen',
	'ip_hint_allgone'			=> 'Du kannst erst dann neue Items eintragen, wenn du alle Items auf deiner Prioritätenliste erhalten hast.',
	'ip_dragndrop'				=> 'Halten und an gewünschte Position verschieben',
	'ip_given_success'			=> 'Das Item \'%1$s\' wurde an %2$s vergeben.',
	'ip_notify_new_item'		=> 'Du hast das Item \'{PRIMARY}\' von deiner Prioritätenliste erhalten.',
	'ip_notify_new_item_grouped' => 'Du hast {COUNT} Items von deiner Prioritätenliste erhalten.',
	'ip_throw'					=> 'Item auswürfeln',
	'ip_give'					=> 'Item vergeben',
	'ip_additional_buyers'		=> 'Weitere Interessenten',
	'ip_f_show_additional_buyers' => 'Zeige alle Interessenten eines Items an',
);

?>
