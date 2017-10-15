<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_pm_project_column{
	
	public function __construct(){

		add_action( 'manage_project_posts_columns', array( $this, 'add_core_project_columns' ), 16, 1 );
		add_action( 'manage_project_posts_custom_column', array( $this, 'custom_columns_content' ), 10, 2 );
	}
	
	
	public function add_core_project_columns( $columns ) {

		$new = array();
		
		$count = 0;
		foreach( $columns as $col_id => $col_label ) { $count++;

			if ( $count == 3 ) {
				
				$new['pm-status'] 	= esc_html__( 'Project Status', PM_TEXTDOMAIN );
				$new['pm-tasks'] 	= esc_html__( 'Project Tasks', PM_TEXTDOMAIN );	
				$new['pm-date'] 	= esc_html__( 'Project Date', PM_TEXTDOMAIN );	
			}
			
			if( 'title' === $col_id ) {
				$new[$col_id] = '<i class="fa fa-project-circle fs_18"></i> ' . esc_html__( 'Project Title', PM_TEXTDOMAIN );
			
			} elseif ( 'author' === $col_id ) {
				$new[$col_id] = '' . esc_html__( 'Created By', PM_TEXTDOMAIN );
			
			} elseif( 'taxonomy-project_tags' === $col_id ) {
				$new[$col_id] = '' . esc_html__( 'Project Tags', PM_TEXTDOMAIN );
			
			} elseif( 'taxonomy-project_cat' === $col_id ) {
				$new[$col_id] = '' . esc_html__( 'Project Categories', PM_TEXTDOMAIN );
			
			} else {
				$new[ $col_id ] = $col_label;
			}
		}
		
		unset( $new['date'] );
			
		return $new;
	}
	
	
	public function custom_columns_content( $column, $post_id ) {
		
		$class_pm_functions = new class_pm_functions();
		$project_status_list 	= $class_pm_functions->project_status_list();
		
	
		$pm_project_meta 	= get_post_meta( $post_id, 'pm_project_meta', true );
		
		$project_admin = get_post_meta( $post_id, 'project_admin', true );		
		$project_clients = get_post_meta( $post_id, 'project_clients', true );			
		$project_deadline = get_post_meta( $post_id, 'project_deadline', true );			
		$project_status = get_post_meta( $post_id, 'project_status', true );
		
		
		
		//var_dump($project_status);
		
		
		switch ( $column ) {
		case 'pm-status':
			
			if( 'pending' === get_post_status($post_id) ) echo '<div class="pm_pending">Pending</div>';
			else {
				
				//$pm_project_status = isset( $pm_project_meta['status'] ) ? $pm_project_meta['status'] : 'default';
				//$pm_statuses 	= $class_pm_functions->pm_statuses();
						//var_dump($project_status);
				foreach( $project_status_list as $slug => $title ) {
					if( $slug == $project_status ) {
						echo '<div class="project-status '.$slug.'">'.$title.'</div>';
					}
				}
			}
			
			//$arr_admins = isset( $pm_project_meta['admins'] ) ? $pm_project_meta['admins'] : array();
			//$arr_clients = isset( $pm_project_meta['clients'] ) ? $pm_project_meta['clients'] : array();
			
			echo '<div class="row-actions">';
			
			//echo '<div class="pm_projects_ra_meta pm_project_admins">'.__( 'Admins: '. count($project_admin), PM_TEXTDOMAIN ).'</div>';
			//echo '<div class="pm_projects_ra_meta pm_project_clients">'.__( 'Clients: '. count($project_clients), PM_TEXTDOMAIN ).'</div>';
			
			echo '</div>';
			
			
			
			
			break;

			
			
			
		case 'pm-tasks':

			$wp_query = new WP_Query (array (
				'post_type' => 'task',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
						'key'       => 'project_id',
						'value'     => get_the_ID(),
						'compare'   => '='
					)
				)
			));

			$completed = 0;
			if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
				
				$pm_task_meta	= get_post_meta( get_the_ID(), 'pm_task_meta', true );
				//$pm_task_status	= isset( $pm_task_meta['status'] ) ? $pm_task_meta['status'] : '';
				
				//if( $pm_task_status == 'completed' ) $completed++;
				$completed++;

			endwhile; wp_reset_query(); endif;

			echo '<div class="pm_tasks_count">'.__( 'Total Tasks: '.$wp_query->found_posts, PM_TEXTDOMAIN ).'</div>';
			
			
			$processing = (int)$wp_query->found_posts - (int)$completed;
			
			echo '<div class="row-actions">';
			
			//echo '<div class="pm_projects_ra_meta pm_tasks_count_completed">'.__( 'Completed: '. $completed, PM_TEXTDOMAIN ).'</div>';
			//echo '<div class="pm_projects_ra_meta pm_tasks_count_processing">'.__( 'Processing: '. $processing, PM_TEXTDOMAIN ).'</div>';
			
			echo '</div>';
			
			break;
			
			
		case 'pm-date':
			
			//$deadline 	= isset( $pm_project_meta['deadline'] ) ? $pm_project_meta['deadline'] : '';
			//$dateago 	= human_time_diff( strtotime( get_the_date( 'D M j g:i a', $post_id ) ), current_time( 'timestamp' ) ) . __(' ago', PM_TEXTDOMAIN);
			//$dateremain = human_time_diff( current_time( 'timestamp' ), strtotime( $deadline ) ) . __(' remaining', PM_TEXTDOMAIN);
			
		
			echo '<div class="pm_projects_date">'. sprintf(__('Deadline: %s', PM_TEXTDOMAIN ), $project_deadline).'</div>';
			
			echo '<div class="row-actions">';
			//echo '<div class="projects-date">'.__( $project_deadline , PM_TEXTDOMAIN ).'</div>';
			echo '</div>';
			
			break;

		}
	}
	
	
} new class_pm_project_column();