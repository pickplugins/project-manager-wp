<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


	add_action( 'qa_action_breadcrumb', 'qa_action_breadcrumb_function', 10 );
	
	if ( ! function_exists( 'qa_action_breadcrumb_function' ) ) {
		function qa_action_breadcrumb_function() {
			require_once( QA_PLUGIN_DIR. 'templates/template-breadcrumb.php');
		}
	}

	