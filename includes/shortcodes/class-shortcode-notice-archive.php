<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_project_manager_shortcode_notice_archive{
	
    public function __construct(){
		add_shortcode( 'notice_archive', array( $this, 'notice_archive' ) );
   	}	
	
	public function notice_archive($atts, $content = null ) {
			
		$atts = shortcode_atts( array(
					
		), $atts);
		


		ob_start();
		include( PM_PLUGIN_DIR . 'templates/notice-archive/notice-archive.php');

		return ob_get_clean();
	}
	
} new class_project_manager_shortcode_notice_archive();