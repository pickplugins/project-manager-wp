<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_pm_post_meta_attendance{
	
	public function __construct(){

		add_action('add_meta_boxes', array($this, 'meta_boxes_attendance'));
		add_action('save_post', array($this, 'meta_boxes_attendance_save'));
		
		
	}
	
	public function meta_boxes_attendance($post_type) {
		
		$post_types = array('attendance');
		if (in_array($post_type, $post_types)) {
		
			add_meta_box('attendance_metabox',
				__( 'attendance data', PM_TEXTDOMAIN ),
				array($this, 'attendance_meta_box_function'),
				$post_type,
				'normal',
				'high'
			);
				
		}
	}
	
	public function attendance_meta_box_function($post) {
 
        wp_nonce_field('attendance_nonce_check', 'attendance_nonce_check_value');
		global $post;
		//$class_pm_functions	= new class_pm_functions();
		//$attendance_status_list 		= $class_pm_functions->attendance_status_list();
        $user_ids = get_option('pm_atten_user_ids');
        $user_ids = explode(',',$user_ids);
        $post_id = $post->ID;

		$month 	= get_post_meta( $post_id, 'month', true );
        $year 	= get_post_meta( $post_id, 'year', true );
        $start_hour 	= get_post_meta( $post_id, 'start_hour', true );
		$late_hour 	= get_post_meta( $post_id, 'late_hour', true );
		$end_hour 	= get_post_meta( $post_id, 'end_hour', true );
        $weekend_days 	= get_post_meta( $post_id, 'weekend_days', true );
        $weekend_days_array = explode(',',$weekend_days);
		$currency 	= get_post_meta( $post_id, 'currency', true );
		$lunch_cost 	= get_post_meta( $post_id, 'lunch_cost', true );

        $user_ids	= get_post_meta( $post_id, 'user_ids', true );
        $user_ids_array = explode(',',$user_ids);

        $off_days 	= get_post_meta( $post_id, 'off_days', true );
        $off_days_array = explode(',',$off_days);
        $attendance_data 	= get_post_meta( $post_id, 'attendance_data', true );


        //echo '<pre>'.var_export($attendance_data, true).'</pre>';



/*
 *
 *
        $attendance_data = array(
            'date' => array(
                'user_id'=>array(
                    'login'=>'10.30',
                    'logout'=>'17.30',
                    'ip'=>'17.30.63.45',
                ),
                'user_id'=>array(
                    'login'=>'10.30',
                    'logout'=>'17.30',
                    'ip'=>'17.30.63.45',
                ),

            )
        );
 *
 * */

        $date = new DateTime();
        $total_day =  date('t');
        //echo var_export($date, true);

        $gmt_offset = get_option('gmt_offset');
        $current_date = date('d', strtotime('+'.$gmt_offset.' hour'));
        $current_hour = date('H', strtotime('+'.$gmt_offset.' hour'));
        $current_minute = date('i', strtotime('+'.$gmt_offset.' hour'));
        $current_month = date('m', strtotime('+'.$gmt_offset.' hour'));
        $current_year = date('Y', strtotime('+'.$gmt_offset.' hour'));


        //$progresses		= isset( $pm_attendance_meta['progresses'] ) ? $pm_attendance_meta['progresses'] : array();


		
		?> 
		
		<div class="attendance-meta">
			
            <div class="half">
            	<div class="inner">
                    <div class="title">Month</div>
                    <input type="text" name="month" value="<?php echo $month; ?>">

                    <div class="title">Year</div>
                    <input type="text" name="year" value="<?php echo $year; ?>">

                    <div class="title">Start hour, ex: 10 => 10AM 22 => 10PM</div>
                    <input placeholder="10:00:00" type="text" name="start_hour" value="<?php echo $start_hour; ?>">

                    <div class="title">Late hour, ex: 10 => 10AM 22 => 10PM</div>
                    <input placeholder="10:12:00" type="text" name="late_hour" value="<?php echo $late_hour; ?>">

                    <div class="title">End hour, ex: 10 => 10AM 22 => 10PM</div>
                    <input placeholder="10:12:00" type="text" name="end_hour" value="<?php echo $end_hour; ?>">

                    <div class="title">Weekend, ex: Sat,Sun</div>
                    <input type="text" name="weekend_days" value="<?php echo $weekend_days; ?>">


                    <div class="title">Off days, ex: 15,28</div>
                    <input type="text" name="off_days" value="<?php echo $off_days; ?>">

                    <div class="title">Users ids, ex: 5,4,6</div>
                    <input type="text" name="user_ids" value="<?php echo $user_ids; ?>">

                    <div class="title">Currency</div>
                    <input placeholder="$" type="text" name="currency" value="<?php echo $currency; ?>">

                    <div class="title">Lunch cost</div>
                    <input placeholder="60" type="text" name="lunch_cost" value="<?php echo $lunch_cost; ?>">


                </div>





            </div>
            

			 

			
		</div> 
		
		<?php
   	}
	
	public function meta_boxes_attendance_save($post_id){
	 
		if (!isset($_POST['attendance_nonce_check_value'])) return $post_id;
		$nonce = $_POST['attendance_nonce_check_value'];
		if (!wp_verify_nonce($nonce, 'attendance_nonce_check')) return $post_id;

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	 
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
	 
		$month = sanitize_text_field( $_POST['month'] );
		update_post_meta( $post_id, 'month', $month );

        $year = sanitize_text_field( $_POST['year'] );
        update_post_meta( $post_id, 'year', $year );

        $start_hour = sanitize_text_field( $_POST['start_hour'] );
        update_post_meta( $post_id, 'start_hour', $start_hour );

		$late_hour = sanitize_text_field( $_POST['late_hour'] );
		update_post_meta( $post_id, 'late_hour', $late_hour );

		$end_hour = sanitize_text_field( $_POST['end_hour'] );
		update_post_meta( $post_id, 'end_hour', $end_hour );


        $weekend_days = sanitize_text_field( $_POST['weekend_days'] );
        update_post_meta( $post_id, 'weekend_days', $weekend_days );

        $user_ids = sanitize_text_field( $_POST['user_ids'] );
        update_post_meta( $post_id, 'user_ids', $user_ids );


        $off_days = sanitize_text_field( $_POST['off_days'] );
        update_post_meta( $post_id, 'off_days', $off_days );

		$currency = sanitize_text_field( $_POST['currency'] );
		update_post_meta( $post_id, 'currency', $currency );

		$lunch_cost = sanitize_text_field( $_POST['lunch_cost'] );
		update_post_meta( $post_id, 'lunch_cost', $lunch_cost );
		//var_dump($attendance_status);
		
		//if( !is_array( $pm_attendance_meta_ori ) ) $pm_attendance_meta_ori = array();
		
		//update_post_meta( $post_id, 'pm_attendance_meta', array_merge( $pm_attendance_meta_ori, $pm_attendance_meta_new ) );
		
	}
	
} new class_pm_post_meta_attendance();