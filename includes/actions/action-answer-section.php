<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


	add_action( 'qa_action_answer_section', 'qa_action_answer_section_function', 10 );
	add_action( 'qa_action_single_answer_content', 'qa_action_single_answer_content_function', 10 );
	add_action( 'qa_action_single_answer_reply', 'qa_action_single_answer_reply_function', 10 );
	
	if ( ! function_exists( 'qa_action_answer_section_function' ) ) {
		function qa_action_answer_section_function() {
			require_once( QA_PLUGIN_DIR. 'templates/single-answer.php');
		}
	}
	
	if ( ! function_exists( 'qa_action_single_answer_content_function' ) ) {
		function qa_action_single_answer_content_function() {
			include( QA_PLUGIN_DIR. 'templates/single-answer/content.php');
		}
	}
	
	if ( ! function_exists( 'qa_action_single_answer_reply_function' ) ) {
		function qa_action_single_answer_reply_function() {
			include( QA_PLUGIN_DIR. 'templates/single-answer/reply.php');
		}
	}
	
	