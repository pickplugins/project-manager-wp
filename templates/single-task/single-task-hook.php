<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

add_action( 'project_manager_action_single_task', 'project_manager_action_single_task_header', 10 );



if ( ! function_exists( 'project_manager_action_single_task_header' ) ) {
    function project_manager_action_single_task_header() {
        include( PM_PLUGIN_DIR. 'templates/single-task/single-task-header.php');
    }
}