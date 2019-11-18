<?php
/*	Project:	EQdkp-Plus
 *	Package:	EQdkp-plus
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

if ( !defined('EQDKP_INC') ){
	die('Do not access this file directly.');
}
				
if ( !class_exists( "pdh_r_itemprio" ) ) {
	class pdh_r_itemprio extends pdh_r_generic{
		public static function __shortcuts() {
		$shortcuts = array();
		return array_merge(parent::$shortcuts, $shortcuts);
	}				
	
	public $default_lang = 'english';
	public $itemprio = null;

	public $hooks = array(
		'itemprio_update',
	);		
			
	public $presets = array(
		'itemprio_id' => array('id', array('%itemprioID%'), array()),
		'itemprio_itemname' => array('itemname', array('%itemprioID%'), array()),
		'itemprio_itemid' => array('itemid', array('%itemprioID%'), array()),
		'itemprio_memberid' => array('memberid', array('%itemprioID%'), array()),
		'itemprio_prio' => array('prio', array('%itemprioID%'), array()),
		'itemprio_eventid' => array('eventid', array('%itemprioID%'), array()),
		'itemprio_revision' => array('revision', array('%itemprioID%'), array()),
		'itemprio_time' => array('time', array('%itemprioID%'), array()),
		'itemprio_userid' => array('userid', array('%itemprioID%'), array()),
		'itemprio_given' => array('given', array('%itemprioID%'), array()),
		'itemprio_hash' => array('hash', array('%itemprioID%'), array()),
	);
				
	public function reset(){
			$this->pdc->del('pdh_itemprio_table');
			
			$this->itemprio = NULL;
	}
					
	public function init(){
			$this->itemprio	= $this->pdc->get('pdh_itemprio_table');				
					
			if($this->itemprio !== NULL){
				return true;
			}		

			$objQuery = $this->db->query('SELECT * FROM __itemprio');
			if($objQuery){
				while($drow = $objQuery->fetchAssoc()){
					$this->itemprio[(int)$drow['id']] = array(
						'id'			=> (int)$drow['id'],
						'itemname'		=> $drow['itemname'],
						'itemid'		=> $drow['itemid'],
						'memberid'		=> (int)$drow['memberid'],
						'prio'			=> (int)$drow['prio'],
						'eventid'		=> (int)$drow['eventid'],
						'revision'		=> (int)$drow['revision'],
						'time'			=> (int)$drow['time'],
						'userid'		=> (int)$drow['userid'],
						'given'			=> (int)$drow['given'],
						'hash'			=> $drow['hash'],
					);
				}
				
				$this->pdc->put('pdh_itemprio_table', $this->itemprio, null);
			}

		}	//end init function

		/**
		 * @return multitype: List of all IDs
		 */				
		public function get_id_list(){
			if ($this->itemprio === null) return array();
			return array_keys($this->itemprio);
		}
		
		/**
		 * Get all data of Element with $strID
		 * @return multitype: Array with all data
		 */				
		public function get_data($itemprioID){
			if (isset($this->itemprio[$itemprioID])){
				return $this->itemprio[$itemprioID];
			}
			return false;
		}
				
		/**
		 * Returns id for $itemprioID				
		 * @param integer $itemprioID
		 * @return mixed id
		 */
		 public function get_id($itemprioID){
			if (isset($this->itemprio[$itemprioID])){
				return $this->itemprio[$itemprioID]['id'];
			}
			return false;
		}

		/**
		 * Returns itemname for $itemprioID				
		 * @param integer $itemprioID
		 * @return mixed itemname
		 */
		 public function get_itemname($itemprioID){
			if (isset($this->itemprio[$itemprioID])){
				return $this->itemprio[$itemprioID]['itemname'];
			}
			return false;
		}

		/**
		 * Returns itemid for $itemprioID				
		 * @param integer $itemprioID
		 * @return mixed itemid
		 */
		 public function get_itemid($itemprioID){
			if (isset($this->itemprio[$itemprioID])){
				return $this->itemprio[$itemprioID]['itemid'];
			}
			return false;
		}

		/**
		 * Returns memberid for $itemprioID				
		 * @param integer $itemprioID
		 * @return mixed memberid
		 */
		 public function get_memberid($itemprioID){
			if (isset($this->itemprio[$itemprioID])){
				return $this->itemprio[$itemprioID]['memberid'];
			}
			return false;
		}

		/**
		 * Returns prio for $itemprioID				
		 * @param integer $itemprioID
		 * @return mixed prio
		 */
		 public function get_prio($itemprioID){
			if (isset($this->itemprio[$itemprioID])){
				return $this->itemprio[$itemprioID]['prio'];
			}
			return false;
		}

		/**
		 * Returns eventid for $itemprioID				
		 * @param integer $itemprioID
		 * @return mixed eventid
		 */
		 public function get_eventid($itemprioID){
			if (isset($this->itemprio[$itemprioID])){
				return $this->itemprio[$itemprioID]['eventid'];
			}
			return false;
		}

		/**
		 * Returns revision for $itemprioID				
		 * @param integer $itemprioID
		 * @return mixed revision
		 */
		 public function get_revision($itemprioID){
			if (isset($this->itemprio[$itemprioID])){
				return $this->itemprio[$itemprioID]['revision'];
			}
			return false;
		}

		/**
		 * Returns time for $itemprioID				
		 * @param integer $itemprioID
		 * @return mixed time
		 */
		 public function get_time($itemprioID){
			if (isset($this->itemprio[$itemprioID])){
				return $this->itemprio[$itemprioID]['time'];
			}
			return false;
		}

		/**
		 * Returns userid for $itemprioID				
		 * @param integer $itemprioID
		 * @return mixed userid
		 */
		 public function get_userid($itemprioID){
			if (isset($this->itemprio[$itemprioID])){
				return $this->itemprio[$itemprioID]['userid'];
			}
			return false;
		}

		/**
		 * Returns given for $itemprioID				
		 * @param integer $itemprioID
		 * @return mixed given
		 */
		 public function get_given($itemprioID){
			if (isset($this->itemprio[$itemprioID])){
				return $this->itemprio[$itemprioID]['given'];
			}
			return false;
		}

		/**
		 * Returns hash for $itemprioID				
		 * @param integer $itemprioID
		 * @return mixed hash
		 */
		 public function get_hash($itemprioID){
			if (isset($this->itemprio[$itemprioID])){
				return $this->itemprio[$itemprioID]['hash'];
			}
			return false;
		}

	}//end class
}//end if
