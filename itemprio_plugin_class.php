<?php
/*	Project:	EQdkp-Plus
 *	Package:	itemprio Plugin
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
	header('HTTP/1.0 404 Not Found'); exit;
}

/*+----------------------------------------------------------------------------
  | itemprio
  +--------------------------------------------------------------------------*/
class itemprio extends plugin_generic {

	public $version				= '1.0.1';
	public $build				= '';
	public $copyright			= 'GodMod';

	protected static $apiLevel	= 23;

	/**
	* Constructor
	* Initialize all informations for installing/uninstalling plugin
	*/
	public function __construct(){
		parent::__construct();

		$this->add_data(array (
			'name'				=> 'Itemprio',
			'code'				=> 'itemprio',
			'path'				=> 'itemprio',
			'template_path'		=> 'plugins/itemprio/templates/',
			'icon'				=> 'fa fa-list-ol',
			'version'			=> $this->version,
			'author'			=> $this->copyright,
			'description'		=> $this->user->lang('itemprio_short_desc'),
			'long_description'	=> $this->user->lang('itemprio_long_desc'),
			'homepage'			=> EQDKP_PROJECT_URL,
			'manuallink'		=> false,
			'plus_version'		=> '2.3',
		));

		$this->add_dependency(array(
			'plus_version'      => '2.3'
		));

		// -- Register our permissions ------------------------
		// permissions: 'a'=admins, 'u'=user
		// ('a'/'u', Permission-Name, Enable? 'Y'/'N', Language string, array of user-group-ids that should have this permission)
		// Groups: 1 = Guests, 2 = Super-Admin, 3 = Admin, 4 = Member
		$this->add_permission('u', 'view',			'Y', $this->user->lang('ip_view'),				array(2,3,4));
		$this->add_permission('u', 'use',			'Y', $this->user->lang('ip_use'),				array(2,3,4));
		$this->add_permission('u', 'change_items',	'Y', $this->user->lang('ip_change_items'),		array(2,3));
		$this->add_permission('u', 'change_order',	'Y', $this->user->lang('ip_change_order'),		array(2,3,4));
		$this->add_permission('a', 'settings',		'N', $this->user->lang('menu_settings'),		array(2,3));
		$this->add_permission('a', 'distribute',	'Y', $this->user->lang('ip_distribute'),		array(2,3));		

		// -- PDH Modules -------------------------------------
		$this->add_pdh_read_module('itemprio');
		$this->add_pdh_write_module('itemprio');
		
		// -- Hooks -------------------------------------------
		$this->add_hook('portal', 'itemprio_portal_hook', 'portal');

		//Routing
		$this->routing->addRoute('Itemprios', 'itemprios', 'plugins/itemprio/page_objects');
		$this->routing->addRoute('MyItemprios', 'myitemprios', 'plugins/itemprio/page_objects');
		
		// -- Menu --------------------------------------------
		$this->add_menu('admin', $this->gen_admin_menu());
		$this->add_menu('main', $this->gen_main_menu());
	}

	/**
	* pre_install
	* Define Installation
	*/
	public function pre_install(){
		// include SQL and default configuration data for installation
		include($this->root_path.'plugins/itemprio/includes/sql.php');

		// define installation
		for ($i = 1; $i <= count($itemprioSQL['install']); $i++)
			$this->add_sql(SQL_INSTALL, $itemprioSQL['install'][$i]);
		
		$this->ntfy->addNotificationType('itemprio_new_item', 'ip_notify_new_item', 'itemprio', 0, 1, 1, 'ip_notify_new_item_grouped', 5, 'fa-list-ol');
	}


	/**
	* post_uninstall
	* Define Post Uninstall
	*/
	public function post_uninstall(){
		// include SQL data for uninstallation
		include($this->root_path.'plugins/itemprio/includes/sql.php');
		
		for ($i = 1; $i <= count($itemprioSQL['uninstall']); $i++)
			$this->db->query($itemprioSQL['uninstall'][$i]);
		
		$this->ntfy->deleteNotificationType('itemprio_new_item');

		$this->pdh->process_hook_queue();
	}
	
	public function post_install(){
		$this->config->set(array(
			'item_count' => 3,
			'item_new_allgone' => 0,
			'twinks' => 0,
		), '', 'itemprio');
	}

	/**
	* gen_admin_menu
	* Generate the Admin Menu
	*/
	private function gen_admin_menu(){
		$admin_menu = array (array(
			'name'	=> $this->user->lang('itemprio'),
			'icon'	=> 'fa fa-list-ol',
			1 => array (
					'link'	=> 'plugins/itemprio/admin/settings.php'.$this->SID,
					'text'	=> $this->user->lang('settings'),
					'check'	=> 'a_itemprio_settings',
					'icon'	=> 'fa-wrench'
			),
		));
		return $admin_menu;
	}

	/**
	* gen_admin_menu
	* Generate the Admin Menu
	*/
	private function gen_main_menu(){

		$main_menu = array(
			1 => array (
				'link'		=> $this->routing->build('Itemprios', false, false, true, true),
				'text'		=> $this->user->lang('ip_itemprios'),
				'check'		=> 'u_itemprio_view',
			),
			/*
			2 => array (
				'link'		=> $this->routing->build('MyItemprios', false, false, true, true),
				'text'		=> $this->user->lang('ip_myitemprios'),
				'check'		=> 'u_itemprio_use',
			),
			*/
		);
		return $main_menu;
	}
}
?>
