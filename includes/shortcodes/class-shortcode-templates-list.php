<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_project_manager_shortcode_templates_list{
	
    public function __construct(){
		add_shortcode( 'templates_list', array( $this, 'templates_list' ) );
   	}	
	
	public function templates_list($atts, $content = null ) {
			
		$atts = shortcode_atts( array(
					
		), $atts);
		


		ob_start();
		include( PM_PLUGIN_DIR . 'templates/templates-list/templates-list.php');

		return ob_get_clean();
	}
	
} new class_project_manager_shortcode_templates_list();