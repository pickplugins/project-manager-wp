<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_project_manager_shortcode_task_archive{
	
    public function __construct(){
		add_shortcode( 'task_archive', array( $this, 'task_archive' ) );
   	}	
	
	public function task_archive($atts, $content = null ) {
			
		$atts = shortcode_atts( array(
					
		), $atts);
		


		ob_start();
		include( PM_PLUGIN_DIR . 'templates/task-archive/task-archive.php');

		return ob_get_clean();
	}
	
} new class_project_manager_shortcode_task_archive();