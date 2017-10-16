<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 



$date = new DateTime();
$total_day =  date('t');
//echo '<pre>'.var_export($date, true).'</pre>';

$gmt_offset = get_option('gmt_offset');
$current_date = date('d', strtotime('+'.$gmt_offset.' hour'));
$current_hour = date('H', strtotime('+'.$gmt_offset.' hour'));
$current_minute = date('i', strtotime('+'.$gmt_offset.' hour'));
$current_month = date('m', strtotime('+'.$gmt_offset.' hour'));
$current_month_name = date('M', strtotime('+'.$gmt_offset.' hour'));
$current_year = date('Y', strtotime('+'.$gmt_offset.' hour'));



$meta_query[] = array(

    'key' => 'month',
    'value' => ($current_month),
    'compare' => '=',

);

$meta_query[] = array(

    'key' => 'year',
    'value' => ($current_year),
    'compare' => '=',

);

$wp_query = new WP_Query(
    array (
        'post_type' => 'attendance',
        'post_status' => 'publish',
        'meta_query' => $meta_query,
        'order' => 'DESC',
        'posts_per_page' => 1,

    ) );

if(!empty($wp_query->posts[0]->ID)){

    $post_id = $wp_query->posts[0]->ID;
}
else{

    $post_data = array(
        'post_title'    => $current_month_name.' '.$current_year,
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_type'   => 'attendance',

    );

    // Insert the post/job into the database
    //wp_insert_post( $job_ID );
    $post_id = wp_insert_post($post_data);
	
	update_post_meta($post_id,'month',  $current_month);
	update_post_meta($post_id,'year',  $current_year);
	update_post_meta($post_id,'late_hour',  10);
	update_post_meta($post_id,'weekend_days',  'Fri,Sat');
	update_post_meta($post_id,'off_days',  '');	

}


//$user_ids = get_option('pm_atten_user_ids');
//$user_ids = explode(',',$user_ids);


$month 	= get_post_meta( $post_id, 'month', true );
$year 	= get_post_meta( $post_id, 'year', true );

$start_hour 	= get_post_meta( $post_id, 'start_hour', true );
$start_time 	= strtotime( $start_hour );


$start_hour 	= new DateTime($start_hour );
$start_hour_display = $start_hour->format('h:i A');



$late_hour 	= get_post_meta( $post_id, 'late_hour', true );
$late_hour 	= new DateTime($late_hour );
$late_hour_display = $late_hour->format('h:i A');

$end_hour 	= get_post_meta( $post_id, 'end_hour', true );
$end_hour 	= new DateTime($end_hour );
$end_hour_display = $end_hour->format('h:i A');

//echo '<pre>'.var_export($end_hour, true).'</pre>';

$weekend_days 	= get_post_meta( $post_id, 'weekend_days', true );
$weekend_days_array = explode(',',$weekend_days);

$user_ids	= get_post_meta( $post_id, 'user_ids', true );
$user_ids_array = explode(',',$user_ids);


$off_days 	= get_post_meta( $post_id, 'off_days', true );
$off_days_array = explode(',',$off_days);

$attendance_data 	= get_post_meta( $post_id, 'attendance_data', true );


if(!is_user_logged_in()){

    echo 'Please <a href="'.wp_login_url('https://www.pickplugins.com/project-manager/attendance/').'" title="Login">Login</a> first';

    return;
}


//echo '<pre>'.var_export($late_hour, true).'</pre>';
?>
<div class="pm-attendance">

	<div class="office-times">
	
		<div class="start-time item">
			<span class="title">Start time</span>		
			<span class="time"><?php echo $start_hour_display; ?></span>
		
		</div>
		
		<div class="late-time item">
			<span class="title">Late time</span>	
			<span class="time"><?php echo $late_hour_display; ?></span>
		
		</div>

		<div class="end-time item">
			<span class="title">End time</span>
			<span class="time"><?php echo $end_hour_display; ?></span>
		
		</div>		
	
	</div>



    <div post_id="<?php echo $post_id; ?>" class="give-atten button"><span class="loading"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></span> Present</div>
    <div post_id="<?php echo $post_id; ?>" class="left-atten button"><span class="loading"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></span> Left Office</div>
    
	<p></p>
	<p></p>
	
	<div class="">
	    <table class="widefat">
			<thead>
			<tr class="thead">
				<th colspan="">User</th>
				<th colspan="">Attendence count</th>
				<th colspan="">Lunch count</th>
                <th colspan="">Lunch cost</th>
			</tr>
			</thead>
			<tbody id="" class="">
			
		
		<?php 
		$attendance_data_by_user = array();
		
		foreach($user_ids_array as $user_id){
			
			?>
			<tr class="thead">
			<?php

			//echo '<pre>'.var_export($total_day, true).'</pre>';

			$user_att_count = 0;
			$user_lunch_count = 0;		
			for ($i=1; $i <= $total_day; $i++){
				
				//$attendance_data_by_user[$user_id][$i] = $attendance_data[$i][$user_id];

				
				if(!empty($attendance_data[$i][$user_id]['lunch']) && $attendance_data[$i][$user_id]['lunch'] =='yes'){
					
					$user_lunch_count++;
					$attendance_data_by_user[$user_id]['lunch'] = $user_lunch_count;
					
				}			
				
				
				if(!empty($attendance_data[$i][$user_id])){
					
					$user_att_count++;
					$attendance_data_by_user[$user_id]['attn_count'] = $user_att_count;
					
				}
							
				
			}
			
			?>
			
				<?php 
				$user = get_user_by('id', $user_id);
				$display_name = $user->display_name;
				//echo $display_name;
				?>	
				
				<tr class="thead">
					<td colspan=""><?php echo $display_name; ?></td>
					<td colspan=""><?php echo $user_att_count; ?></td>
					<td colspan=""><?php echo $user_lunch_count; ?></td>
                <td colspan=""><?php echo $user_lunch_count.'*60 = '. ($user_lunch_count*60).'Tk'; ?></td>
				</tr>
				

				
			<?php
			
			
			
		}
		
		
		
		
		//echo '<pre>'.var_export($attendance_data_by_user, true).'</pre>';
		?>
			</tbody>
		</table>
	</div>
	
	
	<p></p>
	<p></p>
	
	
	
	
	
	
    <table class="widefat user-list">
        <thead>
        <tr class="thead">
            <th colspan="">Date</th>
            <th colspan="">Users</th>
        </tr>
        </thead>

        <tbody id="" class="">
        <div class="user-list">

            <?php

            $total_weekend = 0;
            $total_off_days = 0;
            $all_off_day = 0;

            $user_data = array();

            $user_time_spend_hour =array();
            $user_time_spend_minute=array();
            $user_time_spend_second=array();

            //echo '<pre>'.var_export($total_day, true).'</pre>';

            for ($i=1; $i<=$total_day; $i++){

                $make_date = $current_year.'-'.$current_month.'-'.$i;

                $day_string = date("D", strtotime($make_date));

                if(in_array($day_string, $weekend_days_array)){

                    //var_dump($day_string);
                    $is_weekend = 'weekend';
                    $total_weekend++;
                }
                else{
                    $is_weekend = '';
                }


                if(in_array($i, $off_days_array)){

                    //var_dump($day_string);
                    $is_off_day = 'offday';
                    $total_off_days++;
                }
                else{
                    $is_off_day = '';
                }


            if(in_array($i, $off_days_array) || in_array($day_string, $weekend_days_array)){

                $all_off_day +=1;


            }


                ?>
                <tr class="<?php if($i%2==0) echo 'alternate';?> <?php echo $is_weekend?> <?php echo $is_off_day?>">
                    <td style="padding-left: 10px" width="70px">
                        <?php echo $i.' - '.$day_string;  //var_dump($i%2); ?>

                    </td>

                    <td>
                        <?php

                        if(!empty($user_ids_array) && ($current_date>=$i) && empty($is_weekend) && empty($is_off_day) ):
                            foreach($user_ids_array as $user_id){

                                if(!empty($attendance_data[$i][$user_id]['login'])){
                                    $login = $attendance_data[$i][$user_id]['login'];
//echo '<pre>'.var_export($login, true).'</pre>';
                                    $login = str_replace('.',':', $login);
									$login_time = strtotime($login);
                                }
                                else{
                                    $login = '';
                                }

								
								
								
//echo '<pre>'.var_export($login_time, true).'</pre>';
//echo '<pre>'.var_export($start_time, true).'</pre>';
//echo '<pre>'.var_export(($start_time-$login_time), true).'</pre>';



                                $login_hour = explode(':', $login);
                                $login_hour = $login_hour[0];

                                if(!empty($attendance_data[$i][$user_id]['logout'])){
                                    $logout = $attendance_data[$i][$user_id]['logout'];
                                    $logout = str_replace('.',':', $logout);
                                }else{

                                    $logout = '';
                                }

                                //echo '<pre>'.var_export($login, true).'</pre>';

                                $hour_spend = 0;
                                $minute_spend = 0;
                                $second_spend = 0;

                                if(!empty($login)){

                                    $start_date = new DateTime($year.'-'.$month.'-'.$i.' '.$login);
                                    //echo '<pre>'.var_export($year.'-'.$month.'-'.$i.' '.$login, true).'</pre>';
                                    //echo '<pre>'.var_export($year.'-'.$month.'-'.$i.' '.$logout, true).'</pre>';
                                    $since_start = $start_date->diff(new DateTime($year.'-'.$month.'-'.$i.' '.$logout));


                                    $hour_spend = $since_start->h;
                                    $minute_spend = $since_start->i;
                                    $second_spend = $since_start->s;
                                    //$hour_spend = date('H:i', mktime(0, $minute_spend));

                                    //$user_time_spend_data[$i][$user_id] = $hour_spend.':'.$minute_spend;

                                   $user_time_spend_hour[$user_id] +=$hour_spend;
                                   $user_time_spend_minute[$user_id] +=$minute_spend;
                                   $user_time_spend_second[$user_id] +=$second_spend;
                                    //echo '<pre>'.var_export($hour_spend, true).'</pre>';
                                }


//echo '<pre>'.var_export($login_hour, true).'</pre>';


                                if(!empty($attendance_data[$i][$user_id]['ip'])){

                                    $ip = $attendance_data[$i][$user_id]['ip'];
                                }
                                else{
                                    $ip = '';
                                }

                                if(!empty($attendance_data[$i][$user_id]['lunch'])){

                                    $lunch = $attendance_data[$i][$user_id]['lunch'];

                                }
                                else{

                                    $lunch = 'no';
                                }



                                if(!empty($attendance_data[$i][$user_id])){

                                    $is_present = 'yes';
                                    $user_data[$user_id]['present_count'] =+1;

                                }
                                else{

                                    $is_present = 'no';
                                }



                                $user = get_user_by('id', $user_id);
                                $display_name = $user->display_name;

                                ?>
                                <div class="user">
                                    <div class="thumb"><?php echo get_avatar( $user_id, 40 ); //echo '<img width="40" src="'.PM_PLUGIN_URL.'assets/admin/images/demo-logo.png" />';  ?></div>


                                        <?php

										$login_delay = $start_time-$login_time;

                                        if($is_present=='yes' && $login_delay<0 ){

                                            ?>
                                            <div class="is-late">
                                            <i title="Late <?php echo date("H:i:s", absint($login_delay)); ?>" class="fa fa-clock-o" aria-hidden="true"></i>
                                            </div>
                                            <?php


                                        }

                                        //echo $is_present;

                                        ?>



                                    <div class="is-present <?php echo $is_present; ?>">

                                        <?php
                                        if($is_present=='yes'){

                                            ?>
                                            <i title="Present" class="fa fa-hand-paper-o" aria-hidden="true"></i>
                                            <?php


                                        }
                                        else{
                                            ?>
                                            <i title="Absent" class="fa fa-hand-rock-o" aria-hidden="true"></i>
                                            <?php


                                        }

                                        //echo $is_present;

                                        ?>

                                    </div>






                                    <div class="hover">
                                        <div class="name"><?php echo $display_name; ?></div>
                                        <div class="intime">Login time: <?php echo $login; ?></div>
                                        <div class="outtime">Logout time: <?php echo $logout; ?></div>
                                        <div class="outtime">Late time: <?php echo date("H:i:s", absint($login_delay)); ?></div>
                                        <div class="spend-hour">Spend time: <?php echo $hour_spend.':'.$minute_spend.':'.$second_spend; ?></div>
										<div class="ip">IP: <?php echo $ip; ?></div>
                                        <div class="lunch">Lunch: <?php if($lunch=='yes') echo 'Yes'; else echo 'No' ?></div>

                                        <?php if(current_user_can('administrator')){?>

                                            <div post_id="<?php echo $post_id; ?>" user_id="<?php echo $user_id; ?>" date="<?php echo $i; ?>" month="<?php echo $month; ?>"  year="<?php echo $year; ?>" class="action-atten button"><span class="loading"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></span> <?php if($is_present=='yes') echo 'Absent'; else echo 'Present'; ?></div>

                                            <?php if($is_present=='yes'):?>
                                            <div post_id="<?php echo $post_id; ?>" user_id="<?php echo $user_id; ?>" date="<?php echo $i; ?>" month="<?php echo $month; ?>"  year="<?php echo $year; ?>" class="action-lunch button"><span class="loading"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></span> <?php if($lunch=='yes') echo 'No lunch'; else echo 'Lunch'; ?></div>


                                            <div post_id="<?php echo $post_id; ?>" user_id="<?php echo $user_id; ?>" date="<?php echo $i; ?>" month="<?php echo $month; ?>"  year="<?php echo $year; ?>" class="action-left button"><span class="loading"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></span><input style="width: 60px" type="text" class="" placeholder="17:04:00" value=""> <span class="action-left-submit">Left</span></div>



                                            <?php endif; ?>






                                        <?php } ?>

                                    </div>
                                </div>
                                <?php
                            }
                        else:

                            if($is_weekend){

                                echo '###### Weekend ######';
                            }

                            if($is_off_day){

                                echo '###### Casual Off day ######';
                            }


                        endif;

                        ?>

                    </td>

                </tr>
                <?php
            }

            ?>

        </div>



        </tbody>

    </table>

    <div class="">

        <div class="">
            Total weekend: <?php echo $total_weekend; ?>

        </div>

        <div class="">
            Casual offday: <?php echo $total_off_days; ?>

        </div>

        <div class="">
            Total offday: <?php echo $all_off_day; ?>

        </div>

        <div class="">
            Total working day: <?php echo $total_day - $all_off_day; ?>

        </div>

        <?php











        //echo '<pre>'.var_export($user_time_spend_hour, true).'</pre>';

        //echo '<pre>'.var_export($user_time_spend_minute, true).'</pre>';

         ?>
        <br>
        <br>
        <table class="widefat">
            <thead>
            <tr class="thead">
                <th colspan="">User</th>
                <th colspan="">Total Time</th>
                <th colspan="">Avg.</th>

            </tr>
            </thead>
            <tbody id="" class="">


            <?php
            $attendance_data_by_user = array();

            foreach($user_ids_array as $user_id){

                ?>
                <tr class="thead">
                    <?php
                    $user_minute_to_hour = 0;

                    //echo '<pre>'.var_export($user_time_spend_hour, true).'</pre>';

                    $user_hour = $user_time_spend_hour[$user_id];
                    $user_minute = $user_time_spend_minute[$user_id];
                    $user_second = $user_time_spend_second[$user_id];

                    $user_offset_hour = floor ($user_minute/ 60);
                    $user_minute_remin = $user_minute%60;
                    $user_second_remin = $user_second%60;
                    //echo '<pre>'.var_export($user_offset_hour, true).'</pre>';
                    ?>

                    <?php
                    $user = get_user_by('id', $user_id);
                    $display_name = $user->display_name;
                    //echo $display_name;
                    ?>

                <tr class="thead">
                    <td colspan=""><?php echo $display_name; ?></td>
                    <td colspan=""><?php echo $user_hour+$user_offset_hour.':'.$user_minute_remin.':'.$user_second_remin; ?></td>
                    <td colspan=""><?php //echo $user_minute_remin; ?></td>

                </tr>

                <?php



            }




            //echo '<pre>'.var_export($attendance_data_by_user, true).'</pre>';
            ?>
            </tbody>
        </table>








    </div>



</div>


