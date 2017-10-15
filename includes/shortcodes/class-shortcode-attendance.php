<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_project_manager_shortcode_attendance{
	
    public function __construct(){
		add_shortcode( 'pm_attendance', array( $this, 'pm_attendance' ) );
   	}	
	
	public function pm_attendance($atts, $content = null ) {
			
		$atts = shortcode_atts( array(
					
		), $atts);
		


		ob_start();
		include( PM_PLUGIN_DIR . 'templates/attendance/attendance.php');

		return ob_get_clean();
	}
	
} new class_project_manager_shortcode_attendance();