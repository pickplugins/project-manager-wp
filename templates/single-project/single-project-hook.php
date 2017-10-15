<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

add_action( 'project_manager_action_single_project', 'project_manager_action_single_project_header', 10 );



if ( ! function_exists( 'project_manager_action_single_project_header' ) ) {
    function project_manager_action_single_project_header() {
        include( PM_PLUGIN_DIR. 'templates/single-project/single-project-header.php');
    }
}