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
	die('Do not access this file directly.');
}

/*+----------------------------------------------------------------------------
  | pdh_w_itemprio
  +--------------------------------------------------------------------------*/
if (!class_exists('pdh_w_itemprio')){
	class pdh_w_itemprio extends pdh_w_generic {

		public function add($strItemname, $strItemID, $intMember, $intPrio, $eventID, $intUserID, $strHash, $blnGiven=false){
			
			$objQuery = $this->db->prepare("INSERT INTO __itemprio :p")->set(array(
				'itemname'			=> $strItemname,
				'itemid'			=> $strItemID,
				'memberid'			=> $intMember,
				'prio'				=> $intPrio,
				'eventid'			=> $eventID,
				'revision'			=> 1,
				'time'				=> $this->time->time,
				'userid'			=> $this->user->id,
				'given'				=> ($blnGiven) ? 1 : 0,
				'hash'				=> $strHash,
			))->execute();

			$this->pdh->enqueue_hook('itemprio_update');
			if ($objQuery) return $objQuery->insertId;

			return false;
		}
		
		public function change_prio($intItemID, $intPrio){
			$objQuery = $this->db->prepare("UPDATE __itemprio :p WHERE id=?")->set(array(
					'prio'				=> $intPrio,
			))->execute($intItemID);
			
			$this->pdh->enqueue_hook('itemprio_update');
			if ($objQuery) return $intItemID;
			
			return false;
		}
		
		public function give($intItemID){
			$objQuery = $this->db->prepare("UPDATE __itemprio :p WHERE id=?")->set(array(
					'given'				=> 1,
			))->execute($intItemID);
			
			$this->pdh->enqueue_hook('itemprio_update');
			if ($objQuery) return $intItemID;
			
			return false;
		}

		public function truncate(){
			$this->db->query("TRUNCATE __itemprio");
			$this->pdh->enqueue_hook('itemprio_update');
			return true;
		}

	} //end class
} //end if class not exists
?>
