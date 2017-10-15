<?php
/*
* @Author 		pickplugins
* Copyright: 	pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


function pm_attendance_ip(){
	
	    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


function pm_ajax_action_attendance(){

    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];
    $date = $_POST['date'];
    $month = $_POST['month'];
    $year = $_POST['year'];


    $gmt_offset = get_option('gmt_offset');
    $current_date = date('j', strtotime('+'.$gmt_offset.' hour'));
    $current_hour = date('H', strtotime('+'.$gmt_offset.' hour'));
    $current_minute = date('i', strtotime('+'.$gmt_offset.' hour'));
    $current_second = date('s', strtotime('+'.$gmt_offset.' hour'));
    $current_month = date('m', strtotime('+'.$gmt_offset.' hour'));
    $current_year = date('Y', strtotime('+'.$gmt_offset.' hour'));


    $response = array();

    $attendance_data 	= get_post_meta( $post_id, 'attendance_data', true );

    if(!empty($attendance_data)){



        if(array_key_exists($date, $attendance_data)){

            $attendance_data_by_date = $attendance_data[$date];

            if(array_key_exists($user_id, $attendance_data_by_date)){
                $response['message'] = 'Update success';
                $attendance_data_by_date_user = $attendance_data[$date][$user_id];

                unset($attendance_data[$date][$user_id]);

                update_post_meta( $post_id, 'attendance_data', $attendance_data );

            }
            else{
                $response['message'] = 'Thanks for present';

                $attendance_data[$date][$user_id] = array(
                        'login'=> $current_hour.':'.$current_minute.':'.$current_second,
                        'logout'=>$current_hour.':'.$current_minute.':'.$current_second,
                        'ip'=>pm_attendance_ip(),
                        'lunch'=>'yes',
                    );

                //$attendance_data = array_replace($attendance_data, $attendance_data_by_date);

                update_post_meta( $post_id, 'attendance_data', $attendance_data );
            }


        }
        else{





            $attendance_data[$date][$user_id] = array(
                'login'=> $current_hour.':'.$current_minute.':'.$current_second,
                'logout'=>$current_hour.':'.$current_minute.':'.$current_second,
                'ip'=>pm_attendance_ip(),
                'lunch'=>'yes',
            );



            //$attendance_data = array_replace($attendance_data, $attendance_data_new);
            update_post_meta( $post_id, 'attendance_data', $attendance_data );



        }





    }
    else{

        $attendance_data[$date][$user_id] = array(
            'login'=> $current_hour.':'.$current_minute.':'.$current_second,
            'logout'=>$current_hour.':'.$current_minute.':'.$current_second,
            'ip'=>pm_attendance_ip(),
            'lunch'=>'yes',
        );


        update_post_meta( $post_id, 'attendance_data', $attendance_data );
    }





    echo json_encode($response);

    die();
}
add_action('wp_ajax_pm_ajax_action_attendance', 'pm_ajax_action_attendance');
add_action('wp_ajax_nopriv_pm_ajax_action_attendance', 'pm_ajax_action_attendance');






function pm_ajax_action_give_atten(){

    $post_id = $_POST['post_id'];
    $user_id = get_current_user_id();



    $gmt_offset = get_option('gmt_offset');
    $current_date = date('j', strtotime('+'.$gmt_offset.' hour'));
    $current_date_name = date('D', strtotime('+'.$gmt_offset.' hour'));	
    $current_hour = date('H', strtotime('+'.$gmt_offset.' hour'));
    $current_minute = date('i', strtotime('+'.$gmt_offset.' hour'));
    $current_second = date('s', strtotime('+'.$gmt_offset.' hour'));
    $current_month = date('n', strtotime('+'.$gmt_offset.' hour'));
    $current_year = date('Y', strtotime('+'.$gmt_offset.' hour'));

    $date = $current_date;
    $month = $current_month;
    $year = $current_year;


    $response = array();

    $attendance_data 	= get_post_meta( $post_id, 'attendance_data', true );
	$weekend_days 	= get_post_meta( $post_id, 'weekend_days', true );
	$weekend_days_array = explode(',',$weekend_days);

    $off_days 	= get_post_meta( $post_id, 'off_days', true );
    $off_days_array = explode(',',$off_days);


	if(in_array($current_date_name,$weekend_days_array)){
		
		$response['message'] = 'Its weekend.';
		

		echo json_encode($response);

		die();
	}
	elseif(in_array($current_date,$off_days_array)){

        $response['message'] = 'Its offday.';


        echo json_encode($response);

        die();

    }
	

    if(!empty($attendance_data)){



        if(array_key_exists($date, $attendance_data)){

            $attendance_data_by_date = $attendance_data[$date];

            if(array_key_exists($user_id, $attendance_data_by_date)){
                $response['message'] = 'You already present';
                $attendance_data_by_date_user = $attendance_data[$date][$user_id];

                unset($attendance_data[$date][$user_id]);

                //update_post_meta( $post_id, 'attendance_data', $attendance_data );

            }
            else{
                $response['message'] = 'Thanks for present';

                $attendance_data[$date][$user_id] = array(
                    'login'=> $current_hour.':'.$current_minute.':'.$current_second,
                    'logout'=>$current_hour.':'.$current_minute.':'.$current_second,
                    'ip'=>pm_attendance_ip(),
                    'lunch'=>'yes',
                );

                //$attendance_data = array_replace($attendance_data, $attendance_data_by_date);

                update_post_meta( $post_id, 'attendance_data', $attendance_data );
            }


        }
        else{





            $attendance_data[$date][$user_id] = array(
                'login'=> $current_hour.':'.$current_minute.':'.$current_second,
                'logout'=>$current_hour.':'.$current_minute.':'.$current_second,
                'ip'=>pm_attendance_ip(),
                'lunch'=>'yes',
            );



            //$attendance_data = array_replace($attendance_data, $attendance_data_new);
            update_post_meta( $post_id, 'attendance_data', $attendance_data );



        }





    }
    else{

        $attendance_data[$date][$user_id] = array(
            'login'=> $current_hour.':'.$current_minute.':'.$current_second,
            'logout'=>$current_hour.':'.$current_minute.':'.$current_second,
            'ip'=>pm_attendance_ip(),
            'lunch'=>'yes',
        );


        update_post_meta( $post_id, 'attendance_data', $attendance_data );
    }





    echo json_encode($response);

    die();
}
add_action('wp_ajax_pm_ajax_action_give_atten', 'pm_ajax_action_give_atten');
add_action('wp_ajax_nopriv_pm_ajax_action_give_atten', 'pm_ajax_action_give_atten');






function pm_ajax_action_left_atten(){

    $post_id = $_POST['post_id'];
    $user_id = get_current_user_id();



    $gmt_offset = get_option('gmt_offset');
    $current_date = date('j', strtotime('+'.$gmt_offset.' hour'));
	$current_date_name = date('D', strtotime('+'.$gmt_offset.' hour'));	
    $current_hour = date('H', strtotime('+'.$gmt_offset.' hour'));
    $current_minute = date('i', strtotime('+'.$gmt_offset.' hour'));
    $current_second = date('s', strtotime('+'.$gmt_offset.' hour'));
    $current_month = date('m', strtotime('+'.$gmt_offset.' hour'));
    $current_year = date('Y', strtotime('+'.$gmt_offset.' hour'));

    $date = $current_date;
    $month = $current_month;
    $year = $current_year;

	
	
	
	

    $response = array();

    $attendance_data 	= get_post_meta( $post_id, 'attendance_data', true );

	
	
	$weekend_days 	= get_post_meta( $post_id, 'weekend_days', true );
	$weekend_days_array = explode(',',$weekend_days);

	if(in_array($current_date_name,$weekend_days_array)){
		
		$response['message'] = 'Its weekend.';
		

		echo json_encode($response);

		die();
	}
	

    if(!empty($attendance_data)){



        if(array_key_exists($date, $attendance_data)){

            $attendance_data_by_date = $attendance_data[$date];

            if(array_key_exists($user_id, $attendance_data_by_date)){
                $response['message'] = 'Come back tomorrow';
                $attendance_data_by_date_user = $attendance_data[$date][$user_id];


                $attendance_data[$date][$user_id]['logout'] = $current_hour.':'.$current_minute.':'.$current_second;


                update_post_meta( $post_id, 'attendance_data', $attendance_data );


            }

        }

    }






    echo json_encode($response);

    die();
}
add_action('wp_ajax_pm_ajax_action_left_atten', 'pm_ajax_action_left_atten');
add_action('wp_ajax_nopriv_pm_ajax_action_left_atten', 'pm_ajax_action_left_atten');







function pm_ajax_action_lunch(){

    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];
    $date = $_POST['date'];
    $month = $_POST['month'];
    $year = $_POST['year'];


    $gmt_offset = get_option('gmt_offset');
    $current_date = date('j', strtotime('+'.$gmt_offset.' hour'));
    $current_hour = date('H', strtotime('+'.$gmt_offset.' hour'));
    $current_minute = date('i', strtotime('+'.$gmt_offset.' hour'));
    $current_second = date('s', strtotime('+'.$gmt_offset.' hour'));
    $current_month = date('m', strtotime('+'.$gmt_offset.' hour'));
    $current_year = date('Y', strtotime('+'.$gmt_offset.' hour'));


    $response = array();

    $attendance_data 	= get_post_meta( $post_id, 'attendance_data', true );

    if(!empty($attendance_data)){



        if(array_key_exists($date, $attendance_data)){

            $attendance_data_by_date = $attendance_data[$date];

            if(array_key_exists($user_id, $attendance_data_by_date)){
                $attendance_data_by_date_user = $attendance_data[$date][$user_id];
                $user_lunch = $attendance_data[$date][$user_id]['lunch'];
                //unset($attendance_data[$date][$user_id]);

                if($user_lunch=='yes'){

                    $attendance_data[$date][$user_id]['lunch'] = 'no';

                }
                else{

                    $attendance_data[$date][$user_id]['lunch'] = 'yes';



                }




                update_post_meta( $post_id, 'attendance_data', $attendance_data );

            }
            else{
                $response['message'] = 'user not exist';

                $attendance_data[$date][$user_id] = array(
                    'login'=> $current_hour.':'.$current_minute.':'.$current_second,
                    'logout'=>$current_hour.':'.$current_minute.':'.$current_second,
                    'ip'=>pm_attendance_ip(),
                    'lunch'=>'yes',
                );

                //$attendance_data = array_replace($attendance_data, $attendance_data_by_date);

                //update_post_meta( $post_id, 'attendance_data', $attendance_data );
            }


        }
        else{


            $attendance_data[$date][$user_id] = array(
                'login'=> $current_hour.':'.$current_minute.':'.$current_second,
                'logout'=>$current_hour.':'.$current_minute.':'.$current_second,
                'ip'=>pm_attendance_ip(),
                'lunch'=>'yes',
            );



            //$attendance_data = array_replace($attendance_data, $attendance_data_new);
            //update_post_meta( $post_id, 'attendance_data', $attendance_data );



        }





    }
    else{

        $attendance_data[$date][$user_id] = array(
            'login'=> $current_hour.':'.$current_minute.':'.$current_second,
            'logout'=>$current_hour.':'.$current_minute.':'.$current_second,
            'ip'=>pm_attendance_ip(),
            'lunch'=>'yes',
        );


        //update_post_meta( $post_id, 'attendance_data', $attendance_data );
    }





    echo json_encode($response);

    die();
}
add_action('wp_ajax_pm_ajax_action_lunch', 'pm_ajax_action_lunch');
add_action('wp_ajax_nopriv_pm_ajax_action_lunch', 'pm_ajax_action_lunch');




function pm_ajax_action_left(){

    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];
    $date = $_POST['date'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $custom_time = $_POST['custom_time'];


    $gmt_offset = get_option('gmt_offset');
    $current_date = date('j', strtotime('+'.$gmt_offset.' hour'));
    $current_hour = date('H', strtotime('+'.$gmt_offset.' hour'));
    $current_minute = date('i', strtotime('+'.$gmt_offset.' hour'));
    $current_second = date('s', strtotime('+'.$gmt_offset.' hour'));
    $current_month = date('m', strtotime('+'.$gmt_offset.' hour'));
    $current_year = date('Y', strtotime('+'.$gmt_offset.' hour'));


    if(empty($custom_time)){

        $custom_time = $current_hour.':'.$current_minute.':'.$current_second;

    }

    $response = array();

    $attendance_data 	= get_post_meta( $post_id, 'attendance_data', true );

    if(!empty($attendance_data)){



        if(array_key_exists($date, $attendance_data)){

            $attendance_data_by_date = $attendance_data[$date];

            if(array_key_exists($user_id, $attendance_data_by_date)){
                $response['message'] = 'Update';
                $attendance_data_by_date_user = $attendance_data[$date][$user_id];
                $user_lunch = $attendance_data[$date][$user_id]['lunch'];
                //unset($attendance_data[$date][$user_id]);

                $attendance_data[$date][$user_id]['logout'] = $custom_time;
                update_post_meta( $post_id, 'attendance_data', $attendance_data );

            }
        }
    }

    echo json_encode($response);

    die();
}
add_action('wp_ajax_pm_ajax_action_left', 'pm_ajax_action_left');
add_action('wp_ajax_nopriv_pm_ajax_action_left', 'pm_ajax_action_left');








