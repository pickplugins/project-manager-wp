<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


$to_do_id = get_the_id();
$userid = get_current_user_id();
$class_pm_functions = new class_pm_functions();
$to_do_status_list = $class_pm_functions->to_do_status_list();


$task_id = get_post_meta( $to_do_id, 'task_id', true );
$project_id = get_post_meta( $task_id, 'project_id', true );
$project_admin = get_post_meta( $project_id, 'project_admin', true );

$task_workers = get_post_meta( $task_id, 'task_workers', true );


$to_do_workers = get_post_meta( $to_do_id, 'to_do_workers', true );
$to_do_deadline = get_post_meta( $to_do_id, 'to_do_deadline', true );
$to_do_status = get_post_meta( $to_do_id, 'to_do_status', true );
$to_do_submission = get_post_meta( $to_do_id, 'to_do_submission', true );
$to_do_check_list = get_post_meta( $to_do_id, 'to_do_check_list', true );


$notify_messege = '';


$paged = isset( $_GET['paged'] ) ? $_GET['paged'] : 1;


//echo '<pre>'.var_export($_POST, true).'</pre>';
//echo '<pre>'.var_export($task_workers, true).'</pre>';
//echo '<pre>'.var_export($to_do_workers, true).'</pre>';

$_edit_lock = get_post_meta($to_do_id, '_edit_lock', true);





if(!empty($_edit_lock)){

    $_edit_lock = explode( ':', $_edit_lock );
    $_edit_lock_time = $_edit_lock[0];
    $_edit_lock_user = isset( $_edit_lock[1] ) ? $_edit_lock[1] : get_post_meta( $to_do_id, '_edit_last', true );
    $_edit_lock_user_data = get_user_by('ID',$_edit_lock_user);

}

if(!empty($_GET['take_over_user_id'])){

    $take_over_user_id = (int)$_GET['take_over_user_id'];
    //echo '<pre>'.var_export($take_over_user_id, true).'</pre>';

    update_post_meta(  $to_do_id, '_edit_lock', time().':'.$take_over_user_id );
    $_edit_lock = get_post_meta($to_do_id, '_edit_lock', true);
    $_edit_lock = explode( ':', $_edit_lock );
    $_edit_lock_time = $_edit_lock[0];
    $_edit_lock_user = isset( $_edit_lock[1] ) ? $_edit_lock[1] : get_post_meta( $to_do_id, '_edit_last', true );
    $_edit_lock_user_data = get_user_by('ID',$_edit_lock_user);

}




if(!empty($_POST)){


    $to_do_submission = $_POST['to_do_submission'];
	$to_do_check_list = $_POST['to_do_check_list'];
    $to_do_status = $_POST['to_do_status'];

    update_post_meta($to_do_id, 'to_do_submission', $to_do_submission );
	update_post_meta($to_do_id, 'to_do_check_list', $to_do_check_list );
    update_post_meta($to_do_id, 'to_do_status', $to_do_status );


    $notify_user = $_POST['notify_user'];
    $action_type = $_POST['action_type'];

    //echo '<pre>'.var_export($_POST['notify_user'], true).'</pre>';
    //echo '<pre>'.var_export($_POST['action_type'], true).'</pre>';

    global $wpdb;
    $table = $wpdb->prefix . "project_manager_notify";

    $status = 'unread';

    $gmt_offset = get_option('gmt_offset');
    $datetime = date('Y-m-d h:i:s', strtotime('+'.$gmt_offset.' hour'));
    $is_read = 'no';
    $is_logged = 'no';

    $action_by = get_current_user_id();

    if(!empty($notify_user))
        foreach($notify_user as $user){

            if(!empty($action_type)){

                $notify_messege = 'Notfication sent.';

                $wpdb->query( $wpdb->prepare("INSERT INTO $table 
													( id, action_type, action_by, action_to, is_read, is_logged, datetime, post_id)
													VALUES	( %d, %s, %d, %d, %s, %s, %s, %d )",
                    array	( '', $action_type, $action_by, $user, $is_read, $is_logged, $datetime, $to_do_id)

                ));

            }


        }

    /*



    */

	//wp_safe_redirect(get_permalink($to_do_id));




}
else{

    $to_do_submission = get_post_meta($to_do_id, 'to_do_submission', true);
	$to_do_check_list = get_post_meta($to_do_id, 'to_do_check_list', true);

    $to_do_status = get_post_meta($to_do_id, 'to_do_status', true);

}



//echo '<pre>'.var_export($$project_id, true).'</pre>';

?>

<div class="header full">
    <form class="submission" method="post" action="#">

    <div class="full">
        <div class="inner">
            <h3 class="post-title-display" >

                <?php  the_title(); ?>
            </h3>


            <div class="to_do-details">

                <?php the_content(); ?>
            </div>



        </div>
    </div>




    <div class="full">

        <div class="inner to-do-action">
            <h3>To-do Action</h3>
            <div class="to_do-worker meta">
                <div class="title">To-do worker's</div>
                <div class="worker-list">
                    <?php
                    //$to_do_admin = isset( $to_do_admin ) ? $to_do_admin : array();
                    if(!empty($to_do_workers))
                    foreach( $to_do_workers as $worker ) {

                        echo "<div  user-id=$worker id=user-$worker class='worker worker-hover'>".get_avatar( $worker, 30 ).'</div>';

                    }
                    ?>


                </div>
            </div>

            <div class="to_do-deadline meta">
                <div class="title">To-do Deadline</div>

                <div class="deadline">
                    <?php

                    $date = date_create($to_do_deadline);
                    echo date_format($date, 'd M, Y'); ?>

                </div>
            </div>





            <div class="to-do-status meta">
                <div class="title">To-do Status</div>

                <div class="status <?php echo $to_do_status; ?>"><?php echo $to_do_status_list[$to_do_status]; ?></div>

                    <?php

                    if(current_user_can('manage_options')){

                        ?>
                        <select name="to_do_status">
		                    <?php

		                    foreach ($to_do_status_list as $status_index=>$status){
			                    ?>
                                <option <?php if($to_do_status==$status_index) echo 'selected';?>  value="<?php  echo $status_index; ?>"><?php echo $status; ?></option>
			                    <?php


		                    }
		                    ?>


                        </select>

                        <?php

                    }
                    else{

                        ?>
                        <input type="hidden" name="to_do_status" value="<?php echo $to_do_status; ?>">
                        <?php
                    }

                    ?>








            </div>


        </div>

        <div class="to_do-deadline meta">
            <div class="title">Task link</div>

            <div class="deadline">
                <a href="<?php echo get_permalink($task_id); ?>"><i class="fa fa-external-link" aria-hidden="true"></i> <?php echo get_the_title($task_id); ?></a>
            </div>
        </div>

        <div class="to_do-deadline meta">
            <div class="title">Project link</div>

            <div class="deadline">
                <a href="<?php echo get_permalink($project_id); ?>"><i class="fa fa-external-link" aria-hidden="true"></i> <?php echo get_the_title($project_id); ?></a>
            </div>
        </div>


    </div>


    <div class="full">
        <div class="inner">

            <h3>Check list</h3>
            <div class="check-list">
                <?php
                if(!empty($_edit_lock) && $_edit_lock_user!=$userid){


                }
                else{
	                ?>
                    <div class="add-checklist">Add Check list</div>
	                <?php
                }
                ?>



            <?php



            if(empty($to_do_check_list)){

	            $unique_key = time();
	            $gmt_offset = get_option('gmt_offset');
	            $datetime = date('Y-m-d h:i:s', strtotime('+'.$gmt_offset.' hour'));

	            ?>
                <div class="item">
                    <span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span>



                    <div class="item-header">

                        <span class="title">Dummy title</span>

                        <span class="datetime">04/20/2017</span>
                        <span class="user">Nur Hasan</span>

                    </div>
                    <div class="item-details">

                        <p class="full">
                            Title:<br/>
                            <input type="text" value="" name="to_do_check_list[<?php echo $unique_key; ?>][name]">
                        </p>

                        <p class="full">
                            Details:<br/>


                            <?php

                            wp_editor( '', 'to_do_check_list'.$unique_key, $settings = array('textarea_name'=>'to_do_check_list['.$unique_key.'][details]') );
                            ?>

                        </p>

                        <input type="hidden" name="to_do_check_list[<?php echo $unique_key; ?>][user_id]" value="<?php echo $userid; ?>" />
                        <input type="hidden" name="to_do_check_list[<?php echo $unique_key; ?>][datetime]" value="<?php echo $datetime; ?>" />


                    </div>

                </div>
	            <?php

            }
            else{

                //echo '<pre>'.var_export($to_do_check_list, true).'</pre>';

	            foreach($to_do_check_list as $key=>$submission ) {

		            //$datetime = '';
		            $version = $submission['version'];
		            $name = $submission['name'];
		            $user_id = $submission['user_id'];
		            $datetime = $submission['datetime'];
		            $is_checked = $submission['is_checked'];


		            $details = $submission['details'];
		            $files = $submission['files'];

		            $user_data = get_user_by('ID', $user_id);

		            ?>
                    <div class="item">

			            <?php if(!empty($_edit_lock) && $_edit_lock_user==$userid): ?>
                            <span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span>
			            <?php endif; ?>

                        <div class="item-header">



                            <span class="title">

                                <?php
                                if($is_checked=='yes'){
                                    ?>
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                    <?php
                                }
                                else{
	                                ?>
                                    <i class="fa fa-times" aria-hidden="true"></i>
	                                <?php

                                }

                                ?>

                                <?php echo $name; ?>

                            </span>

                            <span class="datetime"><?php echo $datetime; ?></span>
                            <span class="user"><?php echo $user_data->display_name; ?></span>


                        </div>
                        <div class="item-details">

                            <p class="full">
                                <label><input title="marked as checked" <?php if($is_checked=='yes'){ echo 'checked'; }?>  type="checkbox" value="yes" name="to_do_check_list[<?php echo $key; ?>][is_checked]">
	                                <?php
                                    if($is_checked=='yes'){
                                        echo 'Marked as unchecked';
                                    }
                                    else{
	                                    echo 'Marked as checked';
                                    }
	                                ?>
                                </label>
                            </p>

                            <p class="full">
                                Name:<br/>
                                <input type="text" value="<?php echo $name; ?>" name="to_do_check_list[<?php echo $key; ?>][name]">
                            </p>

                            <p class="full">
                                Details:<br/>
	                            <?php echo wpautop($details); ?>
                                <hr/>
	                            <?php

	                            wp_editor( $details, 'to_do_check_list'.$key, $settings = array('textarea_name'=>'to_do_check_list['.$key.'][details]') );
	                            ?>
                            </p>

                            <input type="hidden" name="to_do_check_list[<?php echo $key; ?>][user_id]" value="<?php echo $user_id; ?>" />
                            <input type="hidden" name="to_do_check_list[<?php echo $key; ?>][datetime]" value="<?php echo $datetime; ?>" />

                        </div>

                    </div>
		            <?php
	            }

            }
            ?>

            </div>






            <h3>Submission</h3>

            <?php

            if(!empty($_edit_lock) && $_edit_lock_user!=$userid){

	            echo '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> This do to is currently editing by <b>'.$_edit_lock_user_data->display_name.'</b> you can\'t update or change. <a class="take-over" href="'.get_permalink($to_do_id).'?take_over_user_id='.$userid.'"><i class="fa fa-pencil-square-o"></i> Take over</a>';
	            ?>
                <br />


	            <?php
            }
            else{
	            ?>
                <div class="add-version">Add Progress</div>
	            <?php

            }



            ?>






                <div class="list-item">

                <?php




				//var_dump($datetime);

                if(empty($to_do_submission)){

                    $unique_key = time();
					$gmt_offset = get_option('gmt_offset');
					$datetime = date('Y-m-d h:i:s', strtotime('+'.$gmt_offset.' hour'));

                    ?>
                    <div class="item">
						<span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span>
						

						
                        <div class="item-header">
                            <span class="version"><i class="fa fa-file-archive-o" aria-hidden="true"></i> <?php echo $version; ?> 1.0.0</span>
							
							
                            <span class="title">Dummy title</span>
							
							<span class="datetime">04/20/2017</span>
							<span class="user">Nur Hasan</span>
							
                        </div>
                        <div class="item-details">
                            <p class="half">
                                Version:<br/>
                                <input type="text" value="" name="to_do_submission[<?php echo $unique_key; ?>][version]">
                            </p>

                            <p class="half">
                                Name:<br/>
                                <input type="text" value="" name="to_do_submission[<?php echo $unique_key; ?>][name]">
                            </p>

                            <p class="full">
                                Details:<br/>

	                            <?php

	                            wp_editor( '', 'to_do_submission'.$unique_key, $settings = array('textarea_name'=>'to_do_submission['.$unique_key.'][details]') );
	                            ?>

                            </p>

								<input type="hidden" name="to_do_submission[<?php echo $unique_key; ?>][user_id]" value="<?php echo $userid; ?>" />
								<input type="hidden" name="to_do_submission[<?php echo $unique_key; ?>][datetime]" value="<?php echo $datetime; ?>" />
							
							
                            <div class="files">
                                <div class="add-file" data-id="<?php echo $unique_key; ?>">Add file</div>

                                <div class="file-list" id="file-list-<?php echo $unique_key; ?>">
                                    <div class="file">
                                        <span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span><input type="text" id="file-name-<?php echo $unique_key; ?>" class="file-name" value="" name="to_do_submission[<?php echo $unique_key; ?>][files][0][name]" placeholder="File name"> <input type="hidden" placeholder="link" id="file-id-<?php echo $unique_key; ?>" class="file-id" name="to_do_submission[<?php echo $unique_key; ?>][files][0][id]"><input type="text" id="file-link-<?php echo $unique_key; ?>" class="file-link" placeholder="link" name="to_do_submission[<?php echo $unique_key; ?>][files][0][link]"> <button data-id="<?php echo $unique_key; ?>" class="upload-file" value="Upload">Upload</button><a target="_blank" data-id="<?php echo $unique_key; ?>" href="<?php echo $file_link; ?>" class="download-file" value="Upload">Download</a>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <?php

                }
                else{


                    foreach($to_do_submission as $key=>$submission ) {

						//$datetime = '';
                        $version = $submission['version'];
                        $name = $submission['name'];
                        $user_id = $submission['user_id'];						
						$datetime = $submission['datetime'];
					
						
                        $details = $submission['details'];
                        $files = $submission['files'];

						$user_data = get_user_by('ID', $user_id);
						
                        ?>
                        <div class="item">

	                        <?php if(!empty($_edit_lock) && $_edit_lock_user==$userid): ?>
							<span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span>
	                        <?php endif; ?>

                            <div class="item-header">
								
                                <span class="version"><i class="fa fa-file-archive-o" aria-hidden="true"></i> <?php echo $version; ?></span>
								
                                <span class="title"><?php echo $name; ?></span>
								
								<span class="datetime"><?php echo $datetime; ?></span>
								<span class="user"><?php echo $user_data->display_name; ?></span>

								
                            </div>
                            <div class="item-details">
                                <p class="half">
                                    Version:<br/>
                                    <input type="text" value="<?php echo $version; ?>" name="to_do_submission[<?php echo $key; ?>][version]">
                                </p>

                                <p class="half">
                                    Name:<br/>
                                    <input type="text" value="<?php echo $name; ?>" name="to_do_submission[<?php echo $key; ?>][name]">
                                </p>

                                <p class="full">
                                    Details:<br/>
	                                <?php echo wpautop($details); ?>

                                    <hr/>
	                                <?php

	                                wp_editor( $details, 'to_do_submission'.$key, $settings = array('textarea_name'=>'to_do_submission['.$key.'][details]') );
	                                ?>


                                </p>
								
								<input type="hidden" name="to_do_submission[<?php echo $key; ?>][user_id]" value="<?php echo $user_id; ?>" />
								<input type="hidden" name="to_do_submission[<?php echo $key; ?>][datetime]" value="<?php echo $datetime; ?>" />								
								

                                <div class="files">

                                    <?php if(!empty($_edit_lock) && $_edit_lock_user==$userid): ?>
                                    <div class="add-file" data-id="<?php echo $key; ?>">Add file</div>
                                    <?php endif; ?>

                                    <div class="file-list" id="file-list-<?php echo $key; ?>">

                                        <?php

                                        if(!empty($files))
                                        foreach($files as $file_key=>$file){

                                            $file_name =$file['name'];
                                            $file_link =$file['link'];
                                            $file_id =$file['id'];

                                            ?>
                                            <div class="file">
	                                        <?php if(!empty($_edit_lock) && $_edit_lock_user==$userid): ?>
                                            <span class="remove">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </span>
	                                        <?php endif; ?>
										
											
                                                <input type="text" id="file-name-<?php echo $file_key; ?>" class="file-name" value="<?php echo $file_name; ?>" name="to_do_submission[<?php echo $key; ?>][files][<?php echo $file_key; ?>][name]" placeholder="File name">
                                                <input type="hidden" id="file-id-<?php echo $file_key; ?>" class="file-id" placeholder="id" value="<?php echo $file_id; ?>" name="to_do_submission[<?php echo $key; ?>][files][<?php echo $file_key; ?>][id]">
                                                <input type="text" id="file-link-<?php echo $file_key; ?>" class="file-link" placeholder="link" value="<?php echo $file_link; ?>" name="to_do_submission[<?php echo $key; ?>][files][<?php echo $file_key; ?>][link]">

	                                            <?php if(!empty($_edit_lock) && $_edit_lock_user==$userid): ?>
                                                <button data-id="<?php echo $file_key; ?>" class="upload-file" value="Upload">Upload</button>
	                                            <?php endif; ?>

												<a target="_blank" data-id="<?php echo $unique_key; ?>" href="<?php echo $file_link; ?>" class="download-file" value="Upload">Download</a>

                                            </div>
                                            <?php


                                        }


                                        ?>


                                    </div>
                                </div>

                            </div>

                        </div>
                        <?php
                    }

                }




                ?>

                </div>

				<h3>Notify</h3>
				
				<?php if(!empty($notify_messege)): ?>
				
				<div class="notify-messege">
				<?php echo $notify_messege; ?>
				</div>
				
				<?php endif; ?>
				
				<div class="add-notify">
				Users: 
				<?php
				
				$notify_user_list = array_unique(array_merge($to_do_workers, $project_admin));
				//echo '<pre>'.var_export($notify_user_list, true).'</pre>';
				
				foreach($notify_user_list as $notify_user){
					
					$user = get_user_by('ID', $notify_user);
					
					?>
					<lable><input type="checkbox" name="notify_user[]" value="<?php echo $notify_user; ?>" /><?php echo $user->display_name; ?></lable>
					<?php
					
				}
				
				
				?>
				
				
				<p></p>
				
				Action:
				<select name="action_type">
					<option value="">None</option>	
					<option value="update">Update</option>
					<option value="submit">Submitted</option>		
					<option value="assign">Assign</option>
					<option value="re-assign">Re-assign</option>
					<option value="complete">Completed</option>
					<option value="pending">Pending</option>
					<option value="on-hold">On-hold</option>
				</select>
				</div>
				
				<h3> </h3>


                <p>
                <?php

                if(!empty($_edit_lock) && $_edit_lock_user!=$userid){

                    echo '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> This do to is currently editing by <b>'.$_edit_lock_user_data->display_name.'</b> you can\'t update or change. <a class="take-over" href="'.get_permalink($to_do_id).'?take_over_user_id='.$userid.'"> <i class="fa fa-pencil-square-o"></i> Take over</a>';
                    ?>
                    <br />

                    <?php
                }
                else{
                    ?>
                    <input type="submit" value="Update" name="submit">
                    <?php

                }

                ?>



                </p>




            <script>
                jQuery(document).ready(function($) {


                    $(document).on('click','.submission .item-header',function(){





                        if($(this).parent().hasClass("active")){


                            $(this).parent().removeClass('active');
                        }
                        else{
                            $(this).parent().addClass('active');

                        }

                    })

                    $(document).on('click','.submission .remove',function(){

                        $(this).parent().remove();

                    })




                    $(document).on('click','.submission .add-file',function(){

                        data_id = $(this).attr('data-id');
                        now = $.now();

                        html = '<div class="file"><span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span><input type="text" id="file-name-'+now+'" class="file-name" value="" name="to_do_submission['+data_id+'][files]['+now+'][name]" placeholder="File name"> <input type="hidden" id="file-id-'+now+'" placeholder="link"  class="file-id" name="to_do_submission['+data_id+'][files]['+now+'][id]"><input type="text" id="file-link-'+now+'" placeholder="link" class="file-link"  name="to_do_submission['+data_id+'][files]['+now+'][link]">  <button data-id="'+now+'" class="upload-file" value="Upload">Upload</button></div>';
                        $('#file-list-'+data_id).append(html);

                        //alert(html);

                    })



                    $(document).on('click','.submission .add-version',function(){


                        now = $.now();
						var d = new Date();

						var curr_day = d.getDate();
						var curr_month = d.getMonth()+1;
						var curr_year = d.getFullYear();

						var curr_hour = d.getHours();
						var curr_min = d.getMinutes();
						var curr_sec = d.getSeconds();


							
						var datetime = d.getFullYear() + '-' +
							(curr_month<10 ? '0' : '') + curr_month + '-' +
							(curr_day<10 ? '0' : '') + curr_day + ' ' +
							
							(curr_hour<10 ? '0' : '') + curr_hour + ':' +
							(curr_min<10 ? '0' : '') + curr_min + ':' +
							(curr_sec<10 ? '0' : '') + curr_sec;
	
	
						
						<?php 
						$user = get_user_by('ID', get_current_user_id());
						
						?>

                        html = '<div class="item"><span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span><div class="item-header"><span class="version"><i class="fa fa-file-archive-o" aria-hidden="true"></i></span> <span class="title">Dummy title</span><span class="datetime">'+datetime+'</span><span class="user"><?php echo $user->display_name; ?></span></div><div class="item-details"><p class="half">Version:<br><input value="" name="to_do_submission['+now+'][version]" type="text"></p><p class="half">Name:<br><input value="" name="to_do_submission['+now+'][name]" type="text"></p><p class="full">Details:<br><textarea class="" name="to_do_submission['+now+'][details]"></textarea></p><input type="hidden" name="to_do_submission['+now+'][user_id]" value="<?php echo $userid; ?>" /><input type="hidden" name="to_do_submission['+now+'][datetime]" value="'+datetime+'" /><div class="files"><div class="add-file" data-id="'+now+'">Add file</div><div class="file-list" id="file-list-'+now+'"><div class="file"><span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span><input id="file-name-'+now+'" class="file-name" value="" name="to_do_submission['+now+'][files][0][name]" placeholder="File name" type="text"> <input placeholder="link" value="" name="to_do_submission['+now+'][files][0][id]" id="file-id-'+now+'" class="file-id" type="hidden"><input placeholder="link" value="" id="file-link-'+now+'" class="file-link" name="to_do_submission['+now+'][files][0][link]" type="text"> <button data-id="'+now+'" class="upload-file" value="Upload">Upload</button></div></div></div></div></div>';


                        $('.list-item').prepend(html);

                        //alert(html);

                    })






                    $(document).on('click','.check-list .add-checklist',function(){


                        now = $.now();
                        var d = new Date();

                        var curr_day = d.getDate();
                        var curr_month = d.getMonth()+1;
                        var curr_year = d.getFullYear();

                        var curr_hour = d.getHours();
                        var curr_min = d.getMinutes();
                        var curr_sec = d.getSeconds();



                        var datetime = d.getFullYear() + '-' +
                            (curr_month<10 ? '0' : '') + curr_month + '-' +
                            (curr_day<10 ? '0' : '') + curr_day + ' ' +

                            (curr_hour<10 ? '0' : '') + curr_hour + ':' +
                            (curr_min<10 ? '0' : '') + curr_min + ':' +
                            (curr_sec<10 ? '0' : '') + curr_sec;



		                <?php
		                $user = get_user_by('ID', get_current_user_id());

		                ?>

                        html = '<div class="item"><span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span><div class="item-header"><span class="version"></span> <span class="title">Dummy title</span><span class="datetime">'+datetime+'</span><span class="user"><?php echo $user->display_name; ?></span></div><div class="item-details"><p class="full">Name:<br><input value="" name="to_do_check_list['+now+'][name]" type="text"></p><p class="full">Details:<br><textarea class="" name="to_do_check_list['+now+'][details]"></textarea></p><input type="hidden" name="to_do_check_list['+now+'][user_id]" value="<?php echo $userid; ?>" /><input type="hidden" name="to_do_check_list['+now+'][datetime]" value="'+datetime+'" /></div></div></div>';


                        $('.check-list').append(html);

                        //alert(html);

                    })












                    var side_uploader;

                    $(document).on('click','.upload-file',function(e){

                        data_id = $(this).attr('data-id');


                        this_ = $(this);
                        //alert('Hello');

                        e.preventDefault();


                        //If the uploader object has already been created, reopen the dialog
                        if (side_uploader) {
                            side_uploader.open();
                            return;
                        }

                        //alert('Hello 2');
                        //Extend the wp.media object
                        side_uploader = wp.media.frames.file_frame = wp.media({
                            title: 'Choose Image',
                            button: {
                                text: 'Choose Image'
                            },
                            multiple: true
                        });

                        //When a file is selected, grab the URL and set it as the text field's value
                        side_uploader.on('select', function() {
                            attachment = side_uploader.state().get('selection').first().toJSON();

                            src_url = attachment.url;
                            filename = attachment.filename;
                            src_id = attachment.id;
                            console.log(attachment);

                            $('#file-name-'+data_id).val(filename);
                            $('#file-link-'+data_id).val(src_url);
                            $('#file-id-'+data_id).val(src_id);

                            //$('input[name=' + target_input + ']').val(attachment.url);
                            //jQuery('#product_features_front_img_preview').attr("src",attachment.url);
                        });


                        //Open the uploader dialog
                        side_uploader.open();


                    })



                })
            </script>

        </div>
    </div>


    </form>
</div>

