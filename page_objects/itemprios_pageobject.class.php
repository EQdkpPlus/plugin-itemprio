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

class itemprios_pageobject extends pageobject
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
	
	$this->user->check_auth('u_itemprio_view');
	
    $handler = array(
		'save' => array('process' => 'save', 'csrf' => 'true'),
    	'throw' => array('process' => 'throw', 'csrf' => 'true'),
    );
    parent::__construct(false, $handler, array('itemprio', 'deletename'), null, 'ip[]');

    $this->process();
  }
	
	public function save(){
		$this->user->check_auth('a_itemprio_distribute');
		
		
		$intItemID = $this->in->get('save', 0);
		
		$this->pdh->put('itemprio', 'give', array($intItemID));
		
		$objQuery = $this->db->prepare("SELECT * FROM __itemprio WHERE id=?;")->execute($intItemID);
		if($objQuery){
			$row = $objQuery->fetchAssoc();
			
			$strBuyer = $this->pdh->get('member', 'name', array($row['memberid']));
			
			$intUser = $this->pdh->get('member', 'userid', array($row['memberid']));
			if($intUser) $this->ntfy->add('itemprio_new_item', $intItemID, sanitize($row['itemname']), $this->routing->build('MyItemprios'), $intUser, sanitize($row['itemname']));
		}
		
		$this->core->message(sprintf($this->user->lang('ip_given_success'), sanitize($row['itemname']), $strBuyer), $this->user->lang('success'), 'green');	
	}

  
	public function throw(){
		$this->user->check_auth('a_itemprio_distribute');
		
		$strIDs = $this->in->get('throw');
		$arrIDs = explode(',', $strIDs);
		array_pop($arrIDs);
		$intRandom = mt_rand(0, count($arrIDs)-1);
		
		$intWinID = $arrIDs[$intRandom];
		$this->pdh->put('itemprio', 'give', array($intWinID));
		
		$objQuery = $this->db->prepare("SELECT * FROM __itemprio WHERE id=?;")->execute($intWinID);
		if($objQuery){
			$row = $objQuery->fetchAssoc();
			
			$strBuyer = $this->pdh->get('member', 'name', array($row['memberid']));
			
			$intUser = $this->pdh->get('member', 'userid', array($row['memberid']));
			if($intUser) $this->ntfy->add('itemprio_new_item', $intWinID, sanitize($row['itemname']), $this->routing->build('MyItemprios'), $intUser, sanitize($row['itemname']));
		}
		
		$this->core->message(sprintf($this->user->lang('ip_given_success'), sanitize($row['itemname']), $strBuyer), $this->user->lang('success'), 'green');
	}
	
	public function display() {
  	$arrSavedEvents = $this->config->get('events', 'itemprio');
  	
  	$arrEventIDs = $this->pdh->sort($arrSavedEvents, 'event', 'name');
  	$arrEvents[0] = "";
  	foreach($arrEventIDs as $eventID){
  		$arrEvents[$eventID] = $this->pdh->get('event', 'name', array($eventID));
  	}
  	$intCountEvents = count($arrEvents)-1;
  	
  	$intCount = (int)$this->config->get('item_count', 'itemprio');
  	$intDate = $this->time->fromformat($this->in->get('date'), 1);

  	if(!$intDate) $intDate = $this->time->time;
  	
  	$arrMemberFilter = array();
  	
  	$intEventId = ($this->in->exists('event')) ? $this->in->get('event', 0) : (($intCountEvents === 1) ? $arrSavedEvents[0] : 0);  	
  	if($this->in->exists('calendarevent', 0)){
  		$intCalendarEvent = $this->in->get('calendarevent', 0);
  		
  		$arrRaiddata = $this->pdh->get('calendar_events', 'data', array($intCalendarEvent));
  		$myEventId = intval($arrRaiddata['extension']['raid_eventid']);
  		if($myEventId > 0 && isset($arrEvents[$myEventId])) $intEventId = $myEventId;
  		
  		$attendees_raw	= $this->pdh->get('calendar_raids_attendees', 'attendees', array($intCalendarEvent));
  		
  		$raidcal_status = $this->config->get('calendar_raid_status');

  		$minRaidstatus = 4;
  		if(is_array($raidcal_status)){
  			foreach($raidcal_status as $status){
  				if((int)$status < $minRaidstatus) $minRaidstatus = (int)$status;
  			}
  		} else {
  			$minRaidstatus = 0;
  		}
  		
  		foreach($attendees_raw as $intMemberID => $arrData){
  			if((int)$arrData['signup_status'] === $minRaidstatus){
  				if($this->config->get('twinks', 'itemprio')){
  					$arrMemberFilter[] = $intMemberID;
  				} else {
  					//resolve main
  					$intMain = $this->pdh->get('member', 'mainid', array($intMemberID));
  					$arrMemberFilter[] = $intMain;
  				}	
  			}
  		}
  	}
  	
  	
  	$this->tpl->assign_vars(array(
  			'EVENT_DROPDOWN' => (new hdropdown('event', array('options' => $arrEvents, 'js' => 'onchange="this.form.submit();"', 'value' => $intEventId)))->output(),
  			'EVENT_ID'		=> $intEventId,
  			'START_PICKER'	=> (new hdatepicker('date', array('value' => $intDate, 'timepicker' => true)))->output(),
  			'S_DISTRIBUTE' => $this->user->check_auth('a_itemprio_distribute', false),
  			'S_IP_CANUSE' => $this->user->check_auth('u_itemprio_use', false),
  			'CALENDAREVENT_ID' => $this->in->get('calendarevent', 0),
	));

  	$objQuery = $this->db->prepare("SELECT *
FROM __itemprio
WHERE id IN (
    SELECT MAX(id)
    FROM __itemprio
	WHERE eventid=? AND time <=?
    GROUP BY prio,memberid
) ORDER by itemname ASC;")->execute($intEventId, $intDate);
  	
  	$arrMemberItems = array();
  	
  	$lang = registry::fetch('user')->lang('XML_LANG');
  	if(register('config')->get('itt_overwrite_lang')) $lang = register('config')->get('itt_langprio1');
  	$data =  array();
  	
  	if($objQuery){
  		while($row = $objQuery->fetchAssoc()){
  			if((int)$row['given'] == 1) continue;
  			
  			$cachedname = register('infotooltip')->getcacheditem($row['itemname'], $lang, $row['itemid'], false, true, $data);
  			if($cachedname == false){
  				$cachedname = $row['itemname'];
  			}

  			$arrMemberItems[$cachedname][] = $row;
  		}
  	}
  	
  	
  	#d($arrMemberItems);
  	
  	$arrPrioList = array();
  	foreach($arrMemberItems as $strKey => $arrItems){
  		$intCurrentPrio = PHP_INT_MAX;
  		foreach($arrItems as $arrItem)	{
  			$intMemberID = (int)$arrItem['memberid'];
  			
  			if(count($arrMemberFilter) && !in_array($intMemberID, $arrMemberFilter)) continue;
  			if(!$this->config->get('twinks', 'itemprio') && !$this->pdh->get('member', 'is_main', array($intMemberID))) continue;
  			
  			
	  		$arrItem['prio'] = intval($arrItem['prio']);
	  		if($arrItem['prio'] < $intCurrentPrio){
	  			$intCurrentPrio = $arrItem['prio'];
	  			$arrPrioList[$strKey] = array();
	  			$arrPrioList[$strKey][] = $arrItem;
	  				
	  		}elseif((int)$intCurrentPrio === intval($arrItem['prio'])){
	  			$arrPrioList[$strKey][] = $arrItem;
	  		}
  		}
  	}
  	
  	foreach($arrPrioList as $arrItems){
  		$this->tpl->assign_block_vars('item_row', array(
  				'ITEM' => infotooltip($arrItems[0]['itemname'], $arrItems[0]['itemid']),
  				'ITEMID' => $arrItems[0]['itemid'],
  				'COUNT' => count($arrItems),
  		));
  		
  		foreach($arrItems as $arrItem)	{
	  		$this->tpl->assign_block_vars('item_row.buyer_row', array(
	  				'ID'	=> $arrItem['id'],
	  				'BUYER' => ($arrItem['prio']+1).'. - '.$this->pdh->geth('member', 'memberlink_decorated', array($arrItem['memberid'], register('routing')->simpleBuild('character'), '', true)),
	  		));
  		}
  	}
  	
  	infotooltip_js();
  	
	$this->core->set_vars(array (
      'page_title'    => $this->user->lang('ip_itemprios'),
      'template_path' => $this->pm->get_data('itemprio', 'template_path'),
      'template_file' => 'itemprios.html',
			'page_path'			=> [
					['title'=>$this->user->lang('ip_itemprios'), 'url'=> ' '],
			],
      'display'       => true
    ));
  }
  
}
?>