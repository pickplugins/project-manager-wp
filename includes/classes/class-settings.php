<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class class_qa_settings  {
	
	public function __construct(){

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
    }
	
	public function admin_menu() {
		
		add_dashboard_page( '', '', 'manage_options', 'wc-setup', '' );
		add_submenu_page( 'edit.php?post_type=question', __( 'Settings', PM_TEXTDOMAIN ), __( 'Settings', PM_TEXTDOMAIN ), 'manage_options', 'settings', array( $this, 'settings' ) );		
		add_submenu_page( 'edit.php?post_type=question', __( 'Addons', PM_TEXTDOMAIN ), __( 'Addons', PM_TEXTDOMAIN ), 'manage_options', 'addons', array( $this, 'addons' ) );			
		
		
		
		do_action( 'qa_action_admin_menus' );
		
	}
	
	public function settings(){
		include( QA_PLUGIN_DIR. 'includes/menus/settings.php' );
	}	
	
	public function addons(){
		include( QA_PLUGIN_DIR. 'includes/menus/addons.php' );
	}	
	
	
} new class_qa_settings();

