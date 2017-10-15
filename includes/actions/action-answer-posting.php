<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


	add_action( 'qa_action_answer_posting', 'qa_action_answer_posting_function', 10 );
	
	if ( ! function_exists( 'qa_action_answer_posting_function' ) ) {
		function qa_action_answer_posting_function() {
			require_once( QA_PLUGIN_DIR. 'templates/template-answer-posting.php');
		}
	}
	
	
	
	
	
	
	