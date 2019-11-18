<?php
/*	Project:	EQdkp-Plus
 *	Package:	MediaCenter Plugin
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

/*+----------------------------------------------------------------------------
  | itemprio_portal_hook
  +--------------------------------------------------------------------------*/
if (!class_exists('itemprio_portal_hook')){
	class itemprio_portal_hook extends gen_class{

		public function portal(){
			if(!$this->user->check_auth('u_itemprio_use', false)) return;
			
			$this->tpl->assign_block_vars('user_tooltip_addition', array(
				'TEXT' => '<a href="'.$this->routing->build('MyItemprios').'"><i class="fa fa-list-ol fa-lg"></i> '.$this->user->lang('ip_myitemprios').'</a>',	
			));
			
		}
	}
}
?>