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

class myitemprios_pageobject extends pageobject
{
  /**
   * __dependencies
   * Get module dependencies
   */
  public static function __shortcuts()
  {
    $shortcuts = array('email' => 'MyMailer');
    return array_merge(parent::__shortcuts(), $shortcuts);
  }
  
  /**
   * Constructor
   */
  public function __construct()
  {
    // plugin installed?
    if (!$this->pm->check('itemprio', PLUGIN_INSTALLED))
      message_die($this->user->lang('ip_plugin_not_installed'));
	
	$this->user->check_auth('u_itemprio_use');
	if(!$this->user->is_signedin()) $this->user->check_auth('u_something');
	
    $handler = array(
		'save' => array('process' => 'save', 'csrf' => 'true'),
    );
    parent::__construct(false, $handler, array('itemprio', 'deletename'), null, 'ip[]');

    $this->process();
  }
	
	public function save(){
		$intEventID = $this->in->get('event', 0);
		
		$arrItems = $this->in->getArray('item');
		
		$arrCharacterIDs = $this->pdh->get('member', 'connection_id', array($this->user->id));
		foreach($arrCharacterIDs as $intCharId){
			$arrCharacters[$intCharId] = $this->pdh->get('member', 'name', array($intCharId));
		}
		$intMainID	= $this->pdh->get('member', 'mainchar', array($this->user->id));
				
		$intCharID = ($this->in->exists('char')) ? $this->in->get('char', 0) : $intMainID;
		//Check Char
		if(!isset($arrCharacters[$intCharID])){
			message_die('This is not your Character.');
		}
		
		$objQuery = $this->db->prepare("SELECT *
FROM __itemprio
WHERE id IN (
    SELECT MAX(id)
    FROM __itemprio
	WHERE eventid=? AND memberid=?
    GROUP BY prio
);")->execute($intEventID, $intCharID);
		
		$arrMemberItemsPrio = array();
		if($objQuery){
			while($row = $objQuery->fetchAssoc()){
				$arrMemberItemsPrio[$row['itemname']] = $row;
			}
		}
		
		
		$i = 0;
		foreach($arrItems as $strHash => $arrInfos){
			if(!isset($arrInfos['gameid'])){				
				$arrOldItem = $arrMemberItemsPrio[$arrInfos['name']];
				if((int)$i != (int)$arrOldItem['prio']) {
					$this->pdh->put('itemprio', 'add', array($arrOldItem['itemname'], $arrOldItem['itemid'], $arrOldItem['memberid'], $i, $arrOldItem['eventid'], $arrOldItem['userid'], $arrOldItem['hash'], $arrOldItem['given']));
				}		
			} else {
				// add($strItemname, $strItemID, $intMember, $intPrio, $eventID, $intUserID, $strHash){
				$this->pdh->put('itemprio', 'add', array($arrInfos['name'], $arrInfos['gameid'], $intCharID, $i, $intEventID, $this->user->id, $strHash));
			}

			$i++;
		}
		$this->pdh->process_hook_queue();
	}

  
  public function display()
  {
  	$arrSavedEvents = $this->config->get('events', 'itemprio');
  	
  	$arrEventIDs = $this->pdh->sort($arrSavedEvents, 'event', 'name');
  	$arrEvents[0] = "";
  	foreach($arrEventIDs as $eventID){
  		$arrEvents[$eventID] = $this->pdh->get('event', 'name', array($eventID));
  	}
  	$intCountEvents = count($arrEvents)-1;
  	
  	$arrCharacters = array();
  	$arrCharacterIDs = $this->pdh->get('member', 'connection_id', array($this->user->id));
  	foreach($arrCharacterIDs as $intCharId){
  		$arrCharacters[$intCharId] = $this->pdh->get('member', 'name', array($intCharId));
  	}
  	
  	if(count($arrCharacters) === 0 ){
  		message_die($this->user->lang('ip_no_chars'));
  	}
  	
  	
  	$intMainID	= $this->pdh->get('member', 'mainchar', array($this->user->id));
  	
  	$intEventId = ($this->in->exists('event')) ? $this->in->get('event', 0) : (($intCountEvents === 1) ? $arrSavedEvents[0] : 0);
	  	
	$intCharID = ($this->in->exists('char')) ? $this->in->get('char', 0) : $intMainID;
	//Check Char
	if(!isset($arrCharacters[$intCharID]) && !$this->user->check_auths(array('a_itemprio_settings', 'a_itemprio_distribute'), 'OR', false)){
		message_die('This is not your Character.');
	}
  	
  	$intCount = (int)$this->config->get('item_count', 'itemprio');
  	
  	
  	$objQuery = $this->db->prepare("SELECT *
FROM __itemprio
WHERE id IN (
    SELECT MAX(id)
    FROM __itemprio
	WHERE eventid=? AND memberid=?
    GROUP BY prio
);")->execute($intEventId, $intCharID);
  	
  	$arrMemberItemsPrio =  array();
  	$intGiven = 0;
  	$item_ids = $item_names = array();
  	
  	if($objQuery){
  		while($row = $objQuery->fetchAssoc()){
  			if(!isset($arrMemberItemsPrio[$row['prio']])){
  				$arrMemberItemsPrio[$row['prio']] = $row;
  				if($row['given']) $intGiven++;
  			}
  		}
  	}
  	
  
  	$blnCanChangeItems = true;
  	if($this->config->get('item_new_allgone', 'itemprio')){
  		if($intGiven < $intCount){
  			$blnCanChangeItems = false;
  		}
  	}
  	
  	
  	for($i=0;$i<$intCount;$i++){
  		if(isset($arrMemberItemsPrio[$i])){	
  			if($blnCanChangeItems && $arrMemberItemsPrio[$i]['given']){
  				$this->tpl->assign_block_vars('item_row', array(
  						'HASH' 	=> md5($intCharID.'_'.$intEventId.'_'.$i),
  						'S_IP_CHANGE' => true,
  				));	
  			} else {
  			
	  			$this->tpl->assign_block_vars('item_row', array(
	  					'HASH' 	=> md5($intCharID.'_'.$intEventId.'_'.$i),
	  					'NAME'	=> $arrMemberItemsPrio[$i]['itemname'],
	  					'NAME_TT'=> infotooltip($arrMemberItemsPrio[$i]['itemname'], $arrMemberItemsPrio[$i]['itemid']),
	  					'GAMEID'=> $arrMemberItemsPrio[$i]['itemid'],
	  					'GIVEN' => $arrMemberItemsPrio[$i]['given'],
	  					'S_IP_CHANGE' => ($this->user->check_auth('u_itemprio_change_items', false) && $blnCanChangeItems) || $this->user->check_auths(array('a_itemprio_settings', 'a_itemprio_distribute'), 'OR', false),
	  			));	
  			}

  		} else {
	  		$this->tpl->assign_block_vars('item_row', array(
	  				'HASH' 			=> md5($intCharID.'_'.$intEventId.'_'.$i),
	  				'S_IP_CHANGE'	=> true,
	  		));	
  		}
  		
  		$item_ids[] = 'item_'.md5($intCharID.'_'.$intEventId.'_'.$i);
  	}
  	
  	$objQuery = $this->db->prepare('SELECT DISTINCT(itemname) FROM __itemprio;')->execute();
  	if($objQuery){
  		while($row = $objQuery->fetchAssoc()){
  			$item_names[] = $row['itemname'];
  		}
  	}
  	
  	$this->jquery->Autocomplete($item_ids, array_unique($item_names));
  	
  	infotooltip_js();
  	
  	$this->tpl->add_js("
			$(\"#itempriosort tbody\").sortable({
				cancel: '.not-sortable, input, tr th.footer, th',
				update: numerize(),
				cursor: 'pointer',
			});

			$( \"#itempriosort tbody\" ).on( \"sortstop\", function( event, ui ) {numerize();} );
		", "docready");
  	
  	
  	$this->tpl->assign_vars(array(
  			'EVENT_DROPDOWN' => (new hdropdown('event', array('options' => $arrEvents, 'js' => 'onchange="this.form.submit();"', 'value' => $intEventId)))->output(),
  			'IP_CHARACTER_DROPDOWN' => (new hdropdown('char', array('options' => $arrCharacters, 'js' => 'onchange="this.form.submit();"', 'value' => $intCharID)))->output(),
  			'EVENT_ID'				=> $intEventId,
  			'S_IP_CHANGE_PRIO'		=> $this->user->check_auth('u_itemprio_change_order', false) || $this->user->check_auth('u_itemprio_change_items', false) || $this->user->check_auths(array('a_itemprio_settings', 'a_itemprio_distribute'), 'OR', false),
  			'S_IP_CHANGE'			=> ($this->user->check_auth('u_itemprio_change_items', false) && $blnCanChangeItems)  || $this->user->check_auths(array('a_itemprio_settings', 'a_itemprio_distribute'), 'OR', false),
  			'S_HINT_ALLGONE'		=> ($this->config->get('item_new_allgone', 'itemprio')),
  			'S_IP_TWINKS'			=> ($this->config->get('twinks', 'itemprio')),
  			'S_IP_REQUIRED'			=> ($this->config->get('item_new_allrequired', 'itemprio')),
  	));
  	
	$this->core->set_vars(array (
      'page_title'    => $this->user->lang('ip_myitemprios'),
      'template_path' => $this->pm->get_data('itemprio', 'template_path'),
      'template_file' => 'myitemprios.html',
			'page_path'=> [
					['title'=>$this->user->lang('ip_myitemprios'), 'url'=> ' '],
			],
      'display'       => true
    ));
  }
  
}
?>