<?php
/*	Project:	EQdkp-Plus
 *	Package:	RaidLogImport Plugin
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2016 EQdkp-Plus Developer Team
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

/*+----------------------------------------------------------------------------
  | rli_calendarevent_raid_menu_hook
  +--------------------------------------------------------------------------*/
if (!class_exists('itemprio_calendarevent_raid_menu_hook')){
	class itemprio_calendarevent_raid_menu_hook extends gen_class{

		/**
		* calendarevent_raid_menu
		* Do the hook 'calendarevent_raid_menu'
		*
		* @return array
		*/
		public function calendarevent_raid_menu($arrParams){
			
			if(!$this->pm->check('itemprio', PLUGIN_INSTALLED)) return array();
			
			$intCalendareventID = $arrParams['id'];
			
			return array(
				0 => array(
					'text'	=> $this->user->lang('ip_itemprios'),
					'link'	=> $this->routing->build('itemprios').'&calendarevent='.$intCalendareventID,
					'icon'	=> 'fa-list-ol',
					'perm'	=> $this->user->check_auth('u_itemprio_view', false),
				)
			);
		}
	}
}
?>