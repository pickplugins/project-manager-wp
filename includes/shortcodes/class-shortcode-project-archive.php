<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_project_manager_shortcode_project_archive{
	
    public function __construct(){
		add_shortcode( 'project_archive', array( $this, 'project_archive' ) );
   	}	
	
	public function project_archive($atts, $content = null ) {
			
		$atts = shortcode_atts( array(
					
		), $atts);
		


		ob_start();
		include( PM_PLUGIN_DIR . 'templates/project-archive/project-archive.php');

		return ob_get_clean();
	}
	
} new class_project_manager_shortcode_project_archive();