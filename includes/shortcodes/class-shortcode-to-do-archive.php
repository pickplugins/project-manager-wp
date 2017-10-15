<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_project_manager_shortcode_to_do_archive{
	
    public function __construct(){
		add_shortcode( 'to_do_archive', array( $this, 'to_do_archive' ) );
   	}	
	
	public function to_do_archive($atts, $content = null ) {
			
		$atts = shortcode_atts( array(
					
		), $atts);
		


		ob_start();
		include( PM_PLUGIN_DIR . 'templates/to-do-archive/to-do-archive.php');

		return ob_get_clean();
	}
	
} new class_project_manager_shortcode_to_do_archive();