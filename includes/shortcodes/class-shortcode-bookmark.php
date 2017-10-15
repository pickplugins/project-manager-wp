<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_project_manager_shortcode_bookmark{
	
    public function __construct(){
		add_shortcode( 'bookmark', array( $this, 'bookmark' ) );
   	}	
	
	public function bookmark($atts, $content = null ) {
			
		$atts = shortcode_atts( array(
					
		), $atts);
		


		ob_start();
		include( PM_PLUGIN_DIR . 'templates/bookmark/bookmark.php');

		return ob_get_clean();
	}
	
} new class_project_manager_shortcode_bookmark();