<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_pm_post_meta_salary{
	
	public function __construct(){

		add_action('add_meta_boxes', array($this, 'meta_boxes_salary'));
		add_action('save_post', array($this, 'meta_boxes_salary_save'));
		
		
	}
	
	public function meta_boxes_salary($post_type) {
		
		$post_types = array('salary');
		if (in_array($post_type, $post_types)) {
		
			add_meta_box('salary_metabox',
				__( 'salary data', PM_TEXTDOMAIN ),
				array($this, 'salary_meta_box_function'),
				$post_type,
				'normal',
				'high'
			);
				
		}
	}
	
	public function salary_meta_box_function($post) {
 
        wp_nonce_field('salary_nonce_check', 'salary_nonce_check_value');
		global $post;


		$date = new DateTime();
		$total_day =  date('t');
		//echo var_export($date, true);

		$gmt_offset = get_option('gmt_offset');
		$current_date = date('d', strtotime('+'.$gmt_offset.' hour'));
		$current_hour = date('H', strtotime('+'.$gmt_offset.' hour'));
		$current_minute = date('i', strtotime('+'.$gmt_offset.' hour'));
		$current_month = date('m', strtotime('+'.$gmt_offset.' hour'));
		$current_year = date('Y', strtotime('+'.$gmt_offset.' hour'));











		//$class_pm_functions	= new class_pm_functions();
		//$salary_status_list 		= $class_pm_functions->salary_status_list();
        $user_ids = get_option('pm_atten_user_ids');


        $user_ids = explode(',',$user_ids);
        $post_id = $post->ID;

		$month 	= get_post_meta( $post_id, 'month', true );
		if(empty($month)) $month = $current_month;

        $year 	= get_post_meta( $post_id, 'year', true );
		if(empty($year)) $year = $current_year;



		$user_ids 	= get_post_meta( $post_id, 'user_ids', true );
		$user_ids_array = explode(',',$user_ids);

        $salary_data 	= get_post_meta( $post_id, 'salary_data', true );


        //echo '<pre>'.var_export($_POST, true).'</pre>';
		//echo '<pre>'.var_export($salary_data, true).'</pre>';


/*
 *
 *
        $salary_data = array(
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




        //$progresses		= isset( $pm_salary_meta['progresses'] ) ? $pm_salary_meta['progresses'] : array();
		

		
		?> 
		
		<div class="salary-meta">
			
            <div class="half">
            	<div class="inner">
                    <div class="title">Month</div>
                    <input type="text" name="month" value="<?php echo $month; ?>">

                    <div class="title">Year</div>
                    <input type="text" name="year" value="<?php echo $year; ?>">

		            <div class="title">User ids</div>
		            <input type="text" name="user_ids" value="<?php echo $user_ids; ?>">

                    <p></p>

                </div>



            </div>
            

            <div class="full">
            
            	<div class="inner">
                    <table class="widefat salary-data">
                        <thead>
                        <tr class="thead">
                            <th colspan="">Date</th>
                            <th colspan="">Salary</th>
	                        <th colspan="">Note</th>
	                        <th colspan="">Paid</th>
                        </tr>
                        </thead>

                        <tbody id="" class="">
                            <div class="user-list">

                                <?php




                                    ?>

                                            <?php

											$total_basic = 0;
                                            if(!empty($user_ids_array)):
                                                foreach($user_ids_array as $user_id){

	                                                $salary_data_by_user = array();

                                            	if(!empty($salary_data[$user_id])){

		                                            $salary_data_by_user = $salary_data[$user_id];

		                                            //echo '<pre>'.var_export($salary_data_by_user, true).'</pre>';


		                                            $basic = (int) $salary_data_by_user['basic'];
		                                            $lunch = $salary_data_by_user['lunch'];
		                                            $bonus = $salary_data_by_user['bonus'];

		                                            $is_paid = $salary_data_by_user['is_paid'];
		                                            $note = $salary_data_by_user['note'];

		                                            $total = (int)($basic+$lunch+$bonus);

													$total_basic += $basic;
													$total_lunch += $lunch;
													$total_bonus += $bonus;


	                                            }
	                                            else{

		                                            $basic = '';
		                                            $lunch = '';
		                                            $bonus = '';
		                                            $is_paid = '';
		                                            $total = '';
		                                            $note = '';

	                                            }




                                                    $user = get_user_by('id', $user_id);
                                                    $display_name = $user->display_name;

                                                    ?>

	                                                <tr class="<?php if($i%2==0) echo 'alternate';?> ">
		                                                <td width="150px">
			                                                <div class="thumb"><?php echo get_avatar( $user_id, 40 ); //echo '<img width="40" src="'.PM_PLUGIN_URL.'assets/admin/images/demo-logo.png" />';  ?></div>
			                                                <div class="name"><?php echo $display_name;  //var_dump($i%2); ?></div>


		                                                </td>

		                                                <td>

																<input placeholder="Salary" name="salary_data[<?php echo $user_id; ?>][basic]" style="width: 80px" type="text" value="<?php echo $basic; ?>">
																<span> + </span>
																<input placeholder="Lunch" name="salary_data[<?php echo $user_id; ?>][lunch]" style="width: 80px" type="text" value="<?php echo $lunch; ?>">
																<span> + </span>
																<input placeholder="Bonus" name="salary_data[<?php echo $user_id; ?>][bonus]" style="width: 80px" type="text" value="<?php echo $bonus; ?>">
																<span> = </span>

																<input readonly placeholder="Total" name="" style="width: 80px" type="text" value="<?php echo $total; ?>Tk">
			                                                    <input type="button" class="button reset-data" value="Reset">




				                                        </td>
		                                                <td>

			                                                <textarea name="salary_data[<?php echo $user_id; ?>][note]"><?php echo $note; ?></textarea>

		                                                </td>

		                                                <td>

                                                            <?php
                                                            //echo $is_paid;
                                                            ?>

				                                                <select name="salary_data[<?php echo $user_id; ?>][is_paid]">

					                                                <option <?php if($is_paid == 'no') echo 'selected'; ?> value="no">No</option>
					                                                <option <?php if($is_paid == 'yes') echo 'selected'; ?>  value="yes">Yes</option>

				                                                </select>




		                                                </td>




				                                    </tr>


                                                    <?php
                                                }
                                            else:



                                            endif;

                                            ?>




                            </div>








                        </tbody>

                    </table>

                    
                                       
                </div>
                
				<div class="inner">

					<div class="">

						Total basic: <?php echo $total_basic; ?><br />
						Total lunch: <?php echo $total_lunch; ?><br />
						Total bonus: <?php echo $total_bonus; ?><br />
						<hr>

						Total: <?php echo $total_basic+$total_lunch+$total_bonus; ?>Tk<br />
					</div>
                    
                </div>
            </div>            
			 

			
		</div> 
		
		<?php
   	}
	
	public function meta_boxes_salary_save($post_id){
	 
		if (!isset($_POST['salary_nonce_check_value'])) return $post_id;
		$nonce = $_POST['salary_nonce_check_value'];
		if (!wp_verify_nonce($nonce, 'salary_nonce_check')) return $post_id;

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


		$user_ids = sanitize_text_field( $_POST['user_ids'] );
		update_post_meta( $post_id, 'user_ids', $user_ids );

		$salary_data = stripslashes_deep( $_POST['salary_data'] );
		update_post_meta( $post_id, 'salary_data', $salary_data );
		
		//var_dump($salary_status);
		
		//if( !is_array( $pm_salary_meta_ori ) ) $pm_salary_meta_ori = array();
		
		//update_post_meta( $post_id, 'pm_salary_meta', array_merge( $pm_salary_meta_ori, $pm_salary_meta_new ) );
		
	}
	
} new class_pm_post_meta_salary();