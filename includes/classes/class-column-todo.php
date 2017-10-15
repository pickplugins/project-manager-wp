<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_pm_to_do_column{
	
	public function __construct(){

		add_action( 'manage_to_do_posts_columns', array( $this, 'add_core_to_do_columns' ), 16, 1 );
		add_action( 'manage_to_do_posts_custom_column', array( $this, 'custom_columns_content' ), 10, 2 );
	}
	
	
	public function add_core_to_do_columns( $columns ) {

		$new = array();
		
		$count = 0;
		foreach( $columns as $col_id => $col_label ) { $count++;

			if ( $count == 3 ) {
				
				$new['pm-task'] 	= esc_html__( 'Task Name', PM_TEXTDOMAIN );
				$new['pm-project'] 	= esc_html__( 'Project Name', PM_TEXTDOMAIN );
				$new['pm-status'] 	= esc_html__( 'ToDo Status', PM_TEXTDOMAIN );
				//$new['pm-todo'] 	= esc_html__( 'Task Todo', PM_TEXTDOMAIN );	
				$new['pm-date'] 	= esc_html__( 'ToDo Date', PM_TEXTDOMAIN );	
			}
			
			if( 'title' === $col_id ) {
				$new[$col_id] = '<i class="fa fa-task-circle fs_18"></i> ' . esc_html__( 'ToDo Title', PM_TEXTDOMAIN );
			
			} else {
				$new[ $col_id ] = $col_label;
			}
		}
		
		unset( $new['date'] );
			
		return $new;
	}
	
	
	public function custom_columns_content( $column, $post_id ) {
		
		$class_pm_functions = new class_pm_functions();
		$to_do_status_list 		= $class_pm_functions->to_do_status_list();
		
		$pm_to_do_meta 	= get_post_meta( $post_id, 'pm_to_do_meta', true );
		
		$to_do_status = get_post_meta( $post_id, 'to_do_status', true );		
		$to_do_deadline = get_post_meta( $post_id, 'to_do_deadline', true );				
		$to_do_workers = get_post_meta( $post_id, 'to_do_workers', true );
		
		
		switch ( $column ) {
		case 'pm-task':

			$task_id	= get_post_meta( get_the_ID(), 'pm_to_do_meta_task_id', true );
			
			//$task_meta	= get_post_meta( $task_id, 'pm_task_meta', true );
			//$deadline	= isset( $task_meta['deadline'] ) ? $task_meta['deadline'] : '';
			//$dateremain	= human_time_diff( current_time( 'timestamp' ), strtotime( $deadline ) ) . __(' remaining', PM_TEXTDOMAIN);
			

			echo '<a class="row-title" href="'.pm_get_permalink( $task_id ).'">'.get_the_title( $task_id ).'</a>';
			
			echo '<div class="row-actions">';
			//echo '<div class="pm_to_dos_ra_meta pm_to_dos_date_remaining">'.__( $dateremain , PM_TEXTDOMAIN ).'</div>';
			echo '</div>';
			
			break;
		
		
		case 'pm-project':

			$task_id	= get_post_meta( get_the_ID(), 'task_id', true );
			$project_id		= get_post_meta( $task_id, 'project_id', true );
			
			//$project_meta	= get_post_meta( $project_id, 'pm_project_meta', true );
			//$deadline		= isset( $project_meta['deadline'] ) ? $project_meta['deadline'] : '';
			//$dateremain		= human_time_diff( current_time( 'timestamp' ), strtotime( $deadline ) ) . __(' remaining', PM_TEXTDOMAIN);
			

			echo '<a class="row-title" href="'.pm_get_permalink( $project_id ).'">'.get_the_title( $project_id ).'</a>';
			
			echo '<div class="row-actions">';
			//echo '<div class="pm_to_dos_ra_meta pm_to_dos_date_remaining">'.__( $dateremain , PM_TEXTDOMAIN ).'</div>';
			echo '</div>';
			
			break;
		
			
		case 'pm-status':
			
			if( 'pending' === get_post_status($post_id) ) echo '<div class="pm_pending">Pending</div>';
			else {
				
				$pm_to_do_status	= isset( $pm_to_do_meta['status'] ) ? $pm_to_do_meta['status'] : 'default';
				$pm_statuses 		= $class_pm_functions->pm_statuses();
				
				foreach( $to_do_status_list as $slug => $title ) {
					if( $slug == $to_do_status ) {
						echo '<div class="to_do_status '.$slug.'">'.$title.'</div>';
					}
				}
			}
			
			//$arr_workers = isset( $pm_to_do_meta['workers'] ) ? $pm_to_do_meta['workers'] : array();
			
			echo '<div class="row-actions">';
			//echo '<div class="pm_to_dos_ra_meta pm_to_do_worker">'.__( 'Admins: '. count($arr_workers), PM_TEXTDOMAIN ).'</div>';
			echo '</div>';
			
			break;

			
			
		case 'pm-todo':

			$wp_query = new WP_Query (array (
				'post_type' => 'to_do',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
						'key'       => 'pm_to_do_meta',
						'value'     => $post_id,
						'compare'   => 'LIKE'
					)
				)
			));
			
			
			echo '<div class="pm_todo_count">'.__( 'Total Todo: '.$wp_query->found_posts, PM_TEXTDOMAIN ).'</div>';
			
			
			$completed = 0;
			if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
			
				$pm_to_do_meta	= get_post_meta( get_the_ID(), 'pm_to_do_meta', true );
				$pm_to_do_status	= isset( $pm_to_do_meta['status'] ) ? $pm_to_do_meta['status'] : '';
				
				if( $pm_to_do_meta == 'completed' ) $completed++;
			
			endwhile; wp_reset_query(); endif;
			
			
			$processing = (int)$wp_query->found_posts - (int)$completed;
			
			echo '<div class="row-actions">';
			
			echo '<div class="pm_to_dos_ra_meta pm_todo_count_completed">'.__( 'Completed: '. $completed, PM_TEXTDOMAIN ).'</div>';
			echo '<div class="pm_to_dos_ra_meta pm_todo_count_processing">'.__( 'Processing: '. $processing, PM_TEXTDOMAIN ).'</div>';
			
			echo '</div>';
			
			break;
			
			
		case 'pm-date':
			
			//$deadline 	= isset( $pm_to_do_meta['deadline'] ) ? $pm_to_do_meta['deadline'] : '';
			//$dateago 	= human_time_diff( strtotime( get_the_date( 'D M j g:i a', $post_id ) ), current_time( 'timestamp' ) ) . __(' ago', PM_TEXTDOMAIN);
			//$dateremain = human_time_diff( current_time( 'timestamp' ), strtotime( $deadline ) ) . __(' remaining', PM_TEXTDOMAIN);
			
		
			echo '<div class="to_do_deadline">'. __( $to_do_deadline, PM_TEXTDOMAIN ).'</div>';
			
			echo '<div class="row-actions">';
			//echo '<div class="pm_to_dos_ra_meta pm_to_dos_date_remaining">'.__( $dateremain , PM_TEXTDOMAIN ).'</div>';
			echo '</div>';
			
			break;

		}
	}
	
	
} new class_pm_to_do_column();