<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


	add_action( 'qa_action_single_question_title', 'qa_action_single_question_title_function', 10 );
	add_action( 'qa_action_single_question_title', 'qa_action_single_question_view_count_function', 10 );	
	
	add_action( 'qa_action_single_question_meta', 'qa_action_single_question_meta_function', 10 );
	add_action( 'qa_action_single_question_content', 'qa_action_single_question_content_function', 20 );
	add_action( 'qa_action_single_question_content', 'qa_action_single_question_subscriber_function', 20 );	
		

	if ( ! function_exists( 'qa_action_single_question_title_function' ) ) {
		function qa_action_single_question_title_function() {
			require_once( QA_PLUGIN_DIR. 'templates/single-question/title.php');
		}
	}

	if ( ! function_exists( 'qa_action_single_question_view_count_function' ) ) {
		function qa_action_single_question_view_count_function() {
			require_once( QA_PLUGIN_DIR. 'templates/single-question/view-count.php');
		}
	}


	if ( ! function_exists( 'qa_action_single_question_meta_function' ) ) {
		function qa_action_single_question_meta_function() {
			require_once( QA_PLUGIN_DIR. 'templates/single-question/meta.php');
		}
	}


	if ( ! function_exists( 'qa_action_single_question_content_function' ) ) {
		function qa_action_single_question_content_function() {
			require_once( QA_PLUGIN_DIR. 'templates/single-question/content.php');
		}
	}
	
	if ( ! function_exists( 'qa_action_single_question_subscriber_function' ) ) {
		function qa_action_single_question_subscriber_function() {
			require_once( QA_PLUGIN_DIR. 'templates/single-question/subscriber.php');
		}
	}