<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 



if($_GET['attendance_id']){


	include( PM_PLUGIN_DIR . 'templates/attendance/attendance-by-month.php');

}
else{



	include( PM_PLUGIN_DIR . 'templates/attendance/attendance-current-month.php');
}








