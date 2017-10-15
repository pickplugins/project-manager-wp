<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

add_action( 'project_manager_action_single_to_do', 'project_manager_action_single_to_do_header', 10 );



if ( ! function_exists( 'project_manager_action_single_to_do_header' ) ) {
    function project_manager_action_single_to_do_header() {
        include( PM_PLUGIN_DIR. 'templates/single-to-do/single-to-do-header.php');
    }
}