<?php
/*
* @Author 		pickplugins
* Copyright: 	pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 



//add_shortcode('pm_off_day_update','pm_off_day_update');

function pm_off_day_update(){

	$wp_query = new WP_Query(
		array (
			'post_type' => 'attendance',
			'post_status' => 'publish',
			'orderby' => 'date',
			//'meta_key' => 'task_workers',
			//'meta_value' => array($current_user_id),
			//'meta_compare' => 'NOT IN',
			//'meta_query' => $meta_query,
			//'tax_query' => $tax_query,
			'order' => 'DESC',
			'posts_per_page' => -1,
			//'paged' => $paged,

		) );



	if ( $wp_query->have_posts() ) :
		while ( $wp_query->have_posts() ) : $wp_query->the_post();

			$off_days 	= get_post_meta( get_the_id(), 'off_days', true );

			if(is_array($off_days)){

				var_dump($off_days);
			}
			else{

				echo '####### Start #######';
				echo $off_days;
				$off_days = explode(',', $off_days);

				foreach ($off_days as $index=>$day){

					if(!empty($day)){
						$off_days_array[$day] = array(
							'day'=>$day,
							'name'=>'Casual Off day',
						);
					}




				}

				update_post_meta(get_the_id(),'off_days',  $off_days_array);


				var_dump($off_days_array);
				$off_days_array = array();


				//echo $off_days;
				echo '####### End #######';

			}



			//echo get_the_title();

		endwhile;
		wp_reset_query();
	else:

		echo __('No task found','classified_maker');

	endif;
}




















	function pm_ajax_notify_logg(){
		
		$userid = get_current_user_id();
		global $wpdb;
		$limit = 10;
		$table = $wpdb->prefix . "project_manager_notify";
		
		$entries = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}project_manager_notify WHERE is_logged='no' AND action_to='$userid' ORDER BY id DESC LIMIT $limit" );
		$pending_count = count($entries);
        $response['pending_count'] = $pending_count;


        $total_notification_entries = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}project_manager_notify WHERE is_read='no' AND action_to='$userid' ORDER BY id DESC LIMIT $limit" );
        $total_notification_count = count($total_notification_entries);
        $response['total_notification_count'] = $total_notification_count;




		foreach( $entries as $entry ){
				
			$notify_id = $entry->id;
			
			$wpdb->update( 
				$table, 
				array( 
					'is_logged' => 'yes',	// string
				), 
				array( 'id' => $notify_id ), 
				array( 
					'%s',	// value1
				), 
				array( '%d' ) 
			);
			
		}

        $response['notification_message'] = __($pending_count.' notification pending.');



        echo json_encode($response);
		
		//echo $entries_count;
				
		die();
	}
	add_action('wp_ajax_pm_ajax_notify_logg', 'pm_ajax_notify_logg');
	add_action('wp_ajax_nopriv_pm_ajax_notify_logg', 'pm_ajax_notify_logg');



	function pm_ajax_notify_mark(){
		

		$notify_id 	= (int)sanitize_text_field($_POST['notify_id']);		
		global $wpdb;
		$table = $wpdb->prefix . "project_manager_notify";	

		$wpdb->update( 
			$table, 
			array( 
				'is_read' => 'yes',	// string
			), 
			array( 'id' => $notify_id ), 
			array( 
				'%s',	// value1
			), 
			array( '%d' ) 
		);
				
		die();
	}
	add_action('wp_ajax_pm_ajax_notify_mark', 'pm_ajax_notify_mark');
	add_action('wp_ajax_nopriv_pm_ajax_notify_mark', 'pm_ajax_notify_mark');
	
	
	function pm_ajax_notify_reload(){
		
		$userid = get_current_user_id();
		global $wpdb;
		$limit = 10;
	
		$entries = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}project_manager_notify WHERE is_read='no' AND action_to='$userid' ORDER BY id DESC LIMIT $limit" );
		

		if(!empty($entries)):
		foreach( $entries as $entry ){
				
			$id = $entry->id;				
			$action_type = $entry->action_type;
			$action_by = $entry->action_by;	
			$action_to = $entry->action_to;
			$is_read = $entry->is_read;
			$is_logged = $entry->is_logged;
			$datetime = $entry->datetime;
			$post_id = $entry->post_id;			
			
			$post_type = get_post_type($post_id);
			$post_type = get_post_type_object($post_type);			
			
			$current_user = get_user_by('ID', $userid);
			$action_by_user = get_user_by('ID', $action_by);
			
			if($action_to ==$userid){

				echo '<div notify-id='.$id.' class="item">';
				echo '<span notify-id='.$id.' class="notify-mark"><i class="fa fa-bell-o" aria-hidden="true"></i></span>';
				echo '<img src="'.get_avatar_url($action_by,  array('size'=>40)).'" class="thumb">';

				if( $action_type == 'submit' ){
		 
					//echo '<div notify-id='.$id.' class="item">';

					echo '<b>'.$action_by_user->display_name.'</b> has submitted the '.$post_type->labels->singular_name.' <a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a>';
					//echo '</div>';
				}	
				
				elseif( $action_type == 'update' ){
		 
					//echo '<div notify-id='.$id.' class="item">';

					echo '<b>'.$action_by_user->display_name.'</b> has updated the '.$post_type->labels->singular_name.' <a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a>';
					//echo '</div>';
				}					
				
				elseif( $action_type == 'assign' ){
		 
					//echo '<div notify-id='.$id.' class="item">';
					echo '<b>'.$action_by_user->display_name.'</b> has assigned the '.$post_type->labels->singular_name.' <a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a>';
					//echo '</div>';
				}				
				
				elseif( $action_type == 're-assign' ){
		 
					//echo '<div notify-id='.$id.' class="item">';
					echo '<b>'.$action_by_user->display_name.'</b> has re-assigned the '.$post_type->labels->singular_name.' <a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a>';
					//echo '</div>';
				}				
				elseif( $action_type == 'complete' ){
		 
					//echo '<div notify-id='.$id.' class="item">';
					echo '<b>'.$action_by_user->display_name.'</b> has marked as completed the '.$post_type->labels->singular_name.' <a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a>';
					//echo '</div>';
				}				
				
				elseif( $action_type == 'pending' ){
		 
					//echo '<div notify-id='.$id.' class="item">';
					echo '<b>'.$action_by_user->display_name.'</b> has marked as pending the '.$post_type->labels->singular_name.' <a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a>';
					//echo '</div>';
				}					
				elseif( $action_type == 'on-hold' ){
		 
					//echo '<div notify-id='.$id.' class="item">';
					echo '<b>'.$action_by_user->display_name.'</b> has marked as on-hold the '.$post_type->labels->singular_name.' <a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a>';
					//echo '</div>';
				}

				echo '</div>';
			}
	
			
			
		}
	
	else:
					echo '<div class="item">No notification found.';
					
					echo '</div>';
	endif;
		
		
		
		die();
	}
	add_action('wp_ajax_pm_ajax_notify_reload', 'pm_ajax_notify_reload');
	add_action('wp_ajax_nopriv_pm_ajax_notify_reload', 'pm_ajax_notify_reload');


function pm_notify(){
	
		$userid = get_current_user_id();
		global $wpdb;
		$limit = 10;
	
		$entries = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}project_manager_notify WHERE is_read='no' AND action_to='$userid' ORDER BY id DESC LIMIT $limit" );
		$entries_count = count($entries);
		echo '<div class="pm-notify">';

			echo '<div class="notify-button">
			<i class="fa fa-bars" aria-hidden="true"></i>
			
			<span class="notify-count">'.$entries_count.'</span>
			<span class="notify-reload"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>			
			
			</div>';
			
			echo '<div class="notify-list">';
			rsort($entries);
			
		if(!empty($entries)):
		foreach( $entries as $entry ){
				
			$id = $entry->id;				
			$action_type = $entry->action_type;
			$action_by = $entry->action_by;	
			$action_to = $entry->action_to;
			$is_read = $entry->is_read;
			$is_logged = $entry->is_logged;
			$datetime = $entry->datetime;
			$post_id = $entry->post_id;			
			
			$post_type = get_post_type($post_id);
			$post_type = get_post_type_object($post_type);			
			
			$current_user = get_user_by('ID', $userid);
			$action_by_user = get_user_by('ID', $action_by);
			
			if($action_to ==$userid){
				echo '<div notify-id='.$id.' class="item">';

				echo '<span notify-id='.$id.' class="notify-mark"><i class="fa fa-bell-o" aria-hidden="true"></i></span>';
				echo '<img src="'.get_avatar_url($action_by,  array('size'=>40)).'" class="thumb">';
				
				if( $action_type == 'submit' ){
		 
					//echo '<div notify-id='.$id.' class="item">';
					echo '<b>'.$action_by_user->display_name.'</b> has submitted the '.$post_type->labels->singular_name.' <a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a>';
					//echo '</div>';
				}	
				
				elseif( $action_type == 'update' ){
		 
					//echo '<div notify-id='.$id.' class="item">';
					echo '<b>'.$action_by_user->display_name.'</b> has updated the '.$post_type->labels->singular_name.' <a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a>';
					//echo '</div>';
				}					
				
				elseif( $action_type == 'assign' ){
		 
					//echo '<div notify-id='.$id.' class="item">';
					echo '<b>'.$action_by_user->display_name.'</b> has assigned the '.$post_type->labels->singular_name.' <a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a>';
					//echo '</div>';
				}				
				
				elseif( $action_type == 're-assign' ){
		 
					//echo '<div notify-id='.$id.' class="item">';
					echo '<b>'.$action_by_user->display_name.'</b> has re-assigned the '.$post_type->labels->singular_name.' <a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a>';
					//echo '</div>';
				}				
				elseif( $action_type == 'complete' ){
		 
					//echo '<div notify-id='.$id.' class="item">';
					echo '<b>'.$action_by_user->display_name.'</b> has marked as completed the '.$post_type->labels->singular_name.' <a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a>';
					//echo '</div>';
				}				
				
				elseif( $action_type == 'pending' ){
		 
					//echo '<div notify-id='.$id.' class="item">';
					echo '<b>'.$action_by_user->display_name.'</b> has marked as pending the '.$post_type->labels->singular_name.' <a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a>';
					//echo '</div>';
				}					
				elseif( $action_type == 'on-hold' ){
		 
					//echo '<div notify-id='.$id.' class="item">';
					echo '<b>'.$action_by_user->display_name.'</b> has marked as on-hold the '.$post_type->labels->singular_name.' <a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a>';
					//echo '</div>';
				}

				echo '</div>';
			}
	
			
		}
	
	else:
					echo '<div class="item">No notification found.';
					
					echo '</div>';
	endif;
	
	echo '</div></div>';
	
}

add_action('project_manager_action_notify','pm_notify');
















function project_manager_single_project_template($single_template) {

    global $post;

    if ($post->post_type == 'project') {
        $single_template = PM_PLUGIN_DIR . 'templates/single-project/single-project.php';
    }

    return $single_template;
}

add_filter( 'single_template', 'project_manager_single_project_template' );





function project_manager_single_task_template($single_template) {

    global $post;

    if ($post->post_type == 'task') {
        $single_template = PM_PLUGIN_DIR . 'templates/single-task/single-task.php';
    }

    return $single_template;
}
add_filter( 'single_template', 'project_manager_single_task_template' );



function project_manager_single_to_do_template($single_template) {

    global $post;

    if ($post->post_type == 'to_do') {
        $single_template = PM_PLUGIN_DIR . 'templates/single-to-do/single-to-do.php';
    }

    return $single_template;
}
add_filter( 'single_template', 'project_manager_single_to_do_template' );















add_action( 'admin_bar_menu', 'social_media_links', 900 );

function social_media_links($wp_admin_bar)
{

	$args = array(
		'id'     => 'social_media',
		'title'	=>	'Social Media',
		'meta'   => array( 'class' => 'first-toolbar-group' ),
	);
	$wp_admin_bar->add_node( $args );	

	
			$args = array();
	
			array_push($args,array(
				'id'		=>	'twitter',
				'title'		=>	'Twitter',
				'href'		=>	'http://www.twitter.com',
				'parent'	=>	'social_media',
			));
			

			array_push($args,array(
				'id'     	=> 'youtube',
				'title'		=>	'YouTube',
				'href'		=>	'http://www.YouTube.com',
				'parent' 	=> 'social_media',
				'meta'   	=> array( 'class' => 'first-toolbar-group' ),
			));

			array_push($args,array(
				'id'		=>	'fb',
				'title'		=>	'Facebook',
				'href'		=>	'http://www.facebook.com',
				'parent'	=>	'social_media',
			));
			
			sort($args);
			for($a=0; $a < sizeOf($args); $a++)
			{
				$wp_admin_bar->add_node($args[$a]);
			}

			
	
} 






	function pm_ajax_search_user(){
		
		$search = $_POST['user_name'];
		
		$users = get_users( array( 
			'search' => $search.'*', 
			'number'=> '5', 
		) );
		
		foreach ( $users as $user ) {
			echo '<div class="name user-'.$user->ID.'" user-id="'.$user->ID.'">'.get_avatar( $user->ID, 30 ).' <span>'. esc_html( $user->display_name ) . '</span></div>';
		}
		
		die();
	}
	add_action('wp_ajax_pm_ajax_search_user', 'pm_ajax_search_user');
	add_action('wp_ajax_nopriv_pm_ajax_search_user', 'pm_ajax_search_user');
		
		
		
		
		
	function pm_ajax_get_user_details(){
		
		$user_id = $_POST['user_id'];
		
		$user_info = get_userdata( $user_id );
		
		echo '<div class="_hide"><i class="fa fa-times"></i></div>';
		echo '<div class="_userdata _username"><i class="fa fa-user"></i> '.$user_info->user_login.'</div>';
		echo '<div class="_userdata _userrole"><i class="fa fa-unlock-alt"></i> ' . implode(', ', $user_info->roles) . '</div>';
		echo '<div class="_userdata _userremove" user-id="'.$user_id.'" ><i class="fa fa-times"></i> Remove</div>';
		
		
		die();
	}
	add_action('wp_ajax_pm_ajax_get_user_details', 'pm_ajax_get_user_details');
	add_action('wp_ajax_nopriv_pm_ajax_get_user_details', 'pm_ajax_get_user_details');
	
	
	function pm_ajax_admin_add_comment(){
		
		$post_id = $_POST['post_id'];
		$comment_content = $_POST['comment_content'];
		
		$current_user 	= wp_get_current_user();
		$user_login 	= $current_user->user_login;
		$user_ID 		= $current_user->ID;
		
		$comment_ID = wp_insert_comment( array(
			'comment_post_ID' => $post_id,
			'comment_author' => "$user_login",
			'comment_content' => "$comment_content",
			'comment_date' => current_time('mysql'),
		) );
		
		$date = human_time_diff( strtotime( current_time('mysql') ), current_time( 'timestamp' ) );
		
		
		echo '<div class="comment" id="comment-'.$comment_ID.'">';
		echo '<div class="thumb">'.get_avatar( $user_ID, 50 ).'</div>';
		echo '<div class="details">';
		echo '<div class="name">'.$user_login.'</div>';
		echo '<div class="date"><i class="fa fa-clock-o"></i> '.__( $date. ' ago', PM_TEXTDOMAIN   ).'</div>';
		echo '<div class="content">'.wpautop( $comment_content ).'</div>';
		echo '</div></div>';

		
		die();
	}
	add_action('wp_ajax_pm_ajax_admin_add_comment', 'pm_ajax_admin_add_comment');
	add_action('wp_ajax_nopriv_pm_ajax_admin_add_comment', 'pm_ajax_admin_add_comment');
	
	
	function pm_ajax_search_project(){
		
		$search = strip_tags(trim($_GET['q'])); 
		
		$data = array();
		
		
		$wp_query = new WP_Query ( array (
			'post_type' => 'project',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			's' => "$search",
		));
		
		if ( $wp_query->have_posts() ) :
		while ( $wp_query->have_posts() ) : $wp_query->the_post();
		
			$data[] = array('id' => get_the_ID() , 'text' => get_the_title() );
			
		
		endwhile;
		wp_reset_query();
		endif;
		
		echo json_encode( $data );
		
		die();
	}
	add_action('wp_ajax_pm_ajax_search_project', 'pm_ajax_search_project');
	add_action('wp_ajax_nopriv_pm_ajax_search_project', 'pm_ajax_search_project');
	
	function pm_ajax_search_task(){
		
		$search = strip_tags(trim($_GET['q'])); 
		
		$data = array();
		
		
		$wp_query = new WP_Query ( array (
			'post_type' => 'task',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			's' => "$search",
		));
		
		if ( $wp_query->have_posts() ) :
		while ( $wp_query->have_posts() ) : $wp_query->the_post();
		
			$data[] = array('id' => get_the_ID() , 'text' => get_the_title() );
			
		
		endwhile;
		wp_reset_query();
		endif;
		
		echo json_encode( $data );
		
		die();
	}
	add_action('wp_ajax_pm_ajax_search_task', 'pm_ajax_search_task');
	add_action('wp_ajax_nopriv_pm_ajax_search_task', 'pm_ajax_search_task');
	
	
	
	function pm_ajax_submit_todo_work_progress(){
		
		$todo_id = $_POST['todo_id']; 
		$content = $_POST['content']; 
		
		
		
		$post_id = $_POST['todo_id'];
		$comment_content = $content;
		
		$current_user 	= wp_get_current_user();
		$user_login 	= $current_user->user_login;
		$user_ID 		= $current_user->ID;
		
		$comment_ID = wp_insert_comment( array(
			'comment_post_ID' => $todo_id,
			'comment_author' => "$user_login",
			'comment_content' => "$comment_content",
			'comment_date' => current_time('mysql'),
		) );
		
		$date = human_time_diff( strtotime( current_time('mysql') ), current_time( 'timestamp' ) );
		
		
		echo '<div class="comment" id="comment-'.$comment_ID.'">';
		echo '<div class="thumb">'.get_avatar( $user_ID, 50 ).'</div>';
		echo '<div class="details">';
		echo '<div class="name">'.$user_login.'</div>';
		echo '<div class="date"><i class="fa fa-clock-o"></i> '.__( $date. ' ago', PM_TEXTDOMAIN   ).'</div>';
		echo '<div class="content">'.wpautop( $comment_content ).'</div>';
		echo '</div></div>';
		
		
		
		
		
		
		
		/*
		
		
		
		$pm_to_do_meta 	= get_post_meta( $todo_id, 'pm_to_do_meta', true );
		$progresses 	= isset( $pm_to_do_meta['progresses'] ) ? $pm_to_do_meta['progresses'] : array();
		$gmt_offset 	= get_option('gmt_offset');
		$progress_id	= time();
   
		$progress_data['author'] = get_current_user_id();
		$progress_data['content'] = $content;
		$progress_data['time'] = date('Y-m-d H:i:s', strtotime('+'.$gmt_offset.' hour'));
		
		$pm_to_do_meta[ $progress_id ] = $progress_data;
		
		update_post_meta( $todo_id, 'to_do_progresses', $pm_to_do_meta );
		
		
		$timago		= human_time_diff( strtotime( $progress_data['time'] ), current_time('timestamp') ) . ' ago';
		
		echo '<div class="progress-single" id="progress-'.$progress_id.'">';
		echo '<div class="thumb">'.get_avatar( get_current_user_id(), 50 ).'</div>';
	
		echo '<div class="details">';
		echo '<div class="author">'.get_the_author_meta( 'display_name', get_current_user_id() ) .'</div>';
		echo '<div class="date"><i class="fa fa-clock-o"></i> '.$timago.'</div>';
		echo '<a class="progress-edit" href="">Edit</a>';
		echo '<div class="progress-content">'.$content.'</div>';
		echo '</div>';
	
		echo '</div>';
		
		*/

		die();
	}
	add_action('wp_ajax_pm_ajax_submit_todo_work_progress', 'pm_ajax_submit_todo_work_progress');
	add_action('wp_ajax_nopriv_pm_ajax_submit_todo_work_progress', 'pm_ajax_submit_todo_work_progress');
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function pm_toast_message() {
		echo "<div class='toast pm-shake' style='display:none'></div>";
	}
	add_action( 'admin_footer', 'pm_toast_message' );
	
	
	function pm_get_status( $post_id = 0 ) {
		
		if( $post_id == 0 ) return '';

		switch( get_post_status( $post_id ) ) {
			
		case 'publish':
			return __( 'On progress', PM_TEXTDOMAIN );
			break;
		case 'pending':
			return __( 'Waiting aproval !', PM_TEXTDOMAIN );
			break;
		case 'trash':
			return __( 'Trashed', PM_TEXTDOMAIN );
			break;
			
		default:
			return __( 'On working', PM_TEXTDOMAIN );
		}
	}
	
	
	function pm_get_permalink( $post_id = 0 ) {
		
		if( $post_id == 0 ) return '';
		
		if( is_admin() ) return get_admin_url().'post.php?post='.$post_id.'&action=edit';
		else return get_permalink( $post_id );
	}
	
	
	
	
	
	
	
	
	

	
	
	
	
	
	
	
	

	
	
	
	
	
	
	
