<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_project_manager_shortcode_templates_task_chart{
	
    public function __construct(){
		add_shortcode( 'templates_task_chart', array( $this, 'templates_task_chart' ) );
   	}	
	
	public function templates_task_chart($atts, $content = null ) {
			
		$atts = shortcode_atts( array(
					
		), $atts);
		


		ob_start();
		include( PM_PLUGIN_DIR . 'templates/templates-task-chart/templates-task-chart.php');

		return ob_get_clean();
	}
	
} new class_project_manager_shortcode_templates_task_chart();