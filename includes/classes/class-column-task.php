<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_pm_task_column{
	
	public function __construct(){

		add_action( 'manage_task_posts_columns', array( $this, 'add_core_task_columns' ), 16, 1 );
		add_action( 'manage_task_posts_custom_column', array( $this, 'custom_columns_content' ), 10, 2 );
	}
	
	
	public function add_core_task_columns( $columns ) {

		$new = array();
		
		$count = 0;
		foreach( $columns as $col_id => $col_label ) { $count++;

			if ( $count == 3 ) {
				
				$new['pm-project'] 	= esc_html__( 'Project Name', PM_TEXTDOMAIN );
				$new['pm-status'] 	= esc_html__( 'Task Status', PM_TEXTDOMAIN );
				$new['pm-todo'] 	= esc_html__( 'Task Todo', PM_TEXTDOMAIN );	
				$new['pm-date'] 	= esc_html__( 'Task Date', PM_TEXTDOMAIN );	
			}
			
			if( 'title' === $col_id ) {
				$new[$col_id] = '<i class="fa fa-task-circle fs_18"></i> ' . esc_html__( 'Task Title', PM_TEXTDOMAIN );
			
			} elseif ( 'author' === $col_id ) {
				$new[$col_id] = '' . esc_html__( 'Created By', PM_TEXTDOMAIN );
			
			} elseif( 'taxonomy-task_tags' === $col_id ) {
				$new[$col_id] = '' . esc_html__( 'Question Tags', PM_TEXTDOMAIN );
			
			} elseif( 'taxonomy-task_cat' === $col_id ) {
				$new[$col_id] = '' . esc_html__( 'Questions Categories', PM_TEXTDOMAIN );
			
			} else {
				$new[ $col_id ] = $col_label;
			}
		}
		
		unset( $new['date'] );
			
		return $new;
	}
	
	
	public function custom_columns_content( $column, $post_id ) {
		
		$class_pm_functions = new class_pm_functions();
		$task_status_list 	= $class_pm_functions->task_status_list();
		
		$pm_task_meta 	= get_post_meta( $post_id, 'pm_task_meta', true );
		$task_status = get_post_meta( $post_id, 'task_status', true );		
		$task_deadline = get_post_meta( $post_id, 'task_deadline', true );				
		$task_workers = get_post_meta( $post_id, 'task_workers', true );	
		
		switch ( $column ) {
		case 'pm-project':

			
			$project_id		= get_post_meta( $post_id, 'project_id', true );
			//$project_meta	= get_post_meta( $project_id, 'pm_project_meta', true );
			//$deadline		= isset( $project_meta['deadline'] ) ? $project_meta['deadline'] : '';
			//$dateremain		= human_time_diff( current_time( 'timestamp' ), strtotime( $deadline ) ) . __(' remaining', PM_TEXTDOMAIN);
			

			echo '<a class="row-title" href="'.pm_get_permalink( $project_id ).'">'.get_the_title( $project_id ).'</a>';
			
			echo '<div class="row-actions">';
			//echo '<div class="pm_tasks_ra_meta pm_tasks_date_remaining">'.__( $dateremain , PM_TEXTDOMAIN ).'</div>';
			echo '</div>';
			
			break;

			
		case 'pm-status':
			
			if( 'pending' === get_post_status($post_id) ) echo '<div class="pm_pending">Pending</div>';
			else {
				
				//$pm_task_status 		= isset( $pm_task_meta['status'] ) ? $pm_task_meta['status'] : '';
				
						
				foreach( $task_status_list as $slug => $title ) {
					if( $slug == $task_status ) {
						echo '<div class="task-status '.$slug.'">'.$title.'</div>';
					}
				}
			}
			
			//$arr_workers = isset( $pm_task_meta['workers'] ) ? $pm_task_meta['workers'] : array();
			
			echo '<div class="row-actions">';
			//echo '<div class="pm_tasks_ra_meta pm_task_worker">'.__( 'Admins: '. count($arr_workers), PM_TEXTDOMAIN ).'</div>';
			echo '</div>';
			
			break;

			
			
		case 'pm-todo':

			$wp_query = new WP_Query (array (
				'post_type' => 'to_do',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
						'key'       => 'task_id',
						'value'     => get_the_ID(),
						'compare'   => '='
					)
				)
			));
			
			
			echo '<div class="pm_todo_count">'.__( 'Total Todo: '.$wp_query->found_posts, PM_TEXTDOMAIN ).'</div>';
			
			
			$completed = 0;
			if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
			
				$pm_task_meta	= get_post_meta( get_the_ID(), 'pm_task_meta', true );
				$pm_task_status	= isset( $pm_task_meta['status'] ) ? $pm_task_meta['status'] : '';
				
				//if( $pm_task_meta == 'completed' ) $completed++;
				$completed++;
			
			endwhile; wp_reset_query(); endif;
			
			
			//$processing = (int)$wp_query->found_posts - (int)$completed;
			
			echo '<div class="row-actions">';
			
			//echo '<div class="pm_tasks_ra_meta pm_todo_count_completed">'.__( 'Completed: '. $completed, PM_TEXTDOMAIN ).'</div>';
			//echo '<div class="pm_tasks_ra_meta pm_todo_count_processing">'.__( 'Processing: '. $processing, PM_TEXTDOMAIN ).'</div>';
			
			echo '</div>';
			
			break;
			
			
		case 'pm-date':
			
			//$deadline 	= isset( $pm_task_meta['deadline'] ) ? $pm_task_meta['deadline'] : '';
			//$dateago 	= human_time_diff( strtotime( get_the_date( 'D M j g:i a', $post_id ) ), current_time( 'timestamp' ) ) . __(' ago', PM_TEXTDOMAIN);
			//$dateremain = human_time_diff( current_time( 'timestamp' ), strtotime( $deadline ) ) . __(' remaining', PM_TEXTDOMAIN);
			
		
			echo '<div class="task_deadline">'. __( $task_deadline, PM_TEXTDOMAIN ).'</div>';
			
			echo '<div class="row-actions">';
			//echo '<div class="pm_tasks_ra_meta pm_tasks_date_remaining">'.__( $dateremain , PM_TEXTDOMAIN ).'</div>';
			echo '</div>';
			
			break;

		}
	}
	
	
} new class_pm_task_column();