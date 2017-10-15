<?php
/*
* @Author 		pickplugins
* Copyright: 	pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 
	
add_shortcode('update_project','update_project');
add_shortcode('update_task','update_task');
add_shortcode('update_todo','update_todo');


function update_project(){
	
	$args = array(
				
				'post_type'=>'project',
				'post_status' => 'any',
				'posts_per_page'=> -1,
				
				);
				
				
				
	$wp_query = new WP_Query($args);	
				

	if ( $wp_query->have_posts() ) :
	while ( $wp_query->have_posts() ) : $wp_query->the_post();
	
	$pm_project_meta = get_post_meta(get_the_ID(), 'pm_project_meta', true);
	
	echo '<pre>'.var_export($pm_project_meta, true).'</pre>';
	
	
	$admins = $pm_project_meta['admins'];		
	$clients = $pm_project_meta['clients'];	
	$deadline = $pm_project_meta['deadline'];
	$status = $pm_project_meta['status'];	
	
	if($status=='default' || empty($status)){$status = 'none';}
	
	
	
	update_post_meta( get_the_ID(), 'project_admin', $admins );
	update_post_meta( get_the_ID(), 'project_clients', $clients );	
	update_post_meta( get_the_ID(), 'project_deadline', $deadline );	
	update_post_meta( get_the_ID(), 'project_status', $status );	
	
	echo get_the_title();
	
	endwhile;
	wp_reset_query();


	endif;	
	
	}


function update_task(){
	
	$args = array(
				
				'post_type'=>'task',
				'post_status' => 'any',
				'posts_per_page'=> -1,
				
				);
				
				
				
	$wp_query = new WP_Query($args);	
				
$i = 1;
	if ( $wp_query->have_posts() ) :
	while ( $wp_query->have_posts() ) : $wp_query->the_post();
	
	$pm_task_meta_project_id = get_post_meta(get_the_ID(), 'pm_task_meta_project_id', true);	
	$pm_task_meta = get_post_meta(get_the_ID(), 'pm_task_meta', true);
	//$project_id = get_post_meta(get_the_ID(), 'project_id', false);	
	//$project_id = $project_id[0][0]; 
	
	//echo '<pre>'.var_export($project_id, true).'</pre>';	
	//echo '<pre>'.var_export($pm_task_meta, true).'</pre>';
	
	$workers = $pm_task_meta['workers'];		
	$deadline = $pm_task_meta['deadline'];
	$status = $pm_task_meta['status'];
	
	//$project_id = $pm_task_meta_project_id;		
	if($status=='default' || empty($status)){$status = 'none';}
	//echo '<pre>'.var_export($workers, true).'</pre>';
	
	//update_post_meta( get_the_ID(), 'project_id', $project_id );	
	update_post_meta( get_the_ID(), 'task_workers', $workers );
	update_post_meta( get_the_ID(), 'task_deadline', $deadline );		
	update_post_meta( get_the_ID(), 'task_status', $status );	
	update_post_meta( get_the_ID(), 'project_id', $pm_task_meta_project_id );	
	
	echo $i.' - '.get_the_title().'<br />';
	$i++;
	endwhile;
	wp_reset_query();


	endif;	
	
	}



function update_todo(){
	
	$args = array(
				
				'post_type'=>'to_do',
				'post_status' => 'any',
				'posts_per_page'=> -1,
				
				);
				
				
				
	$wp_query = new WP_Query($args);	
				

	if ( $wp_query->have_posts() ) :
	while ( $wp_query->have_posts() ) : $wp_query->the_post();
	
	$pm_to_do_meta = get_post_meta(get_the_ID(), 'pm_to_do_meta', true);
	$pm_to_do_meta_task_id = get_post_meta(get_the_ID(), 'pm_to_do_meta_task_id', true);	
	//echo '<pre>'.var_export($pm_to_do_meta, true).'</pre>';
	
	
	$workers = $pm_to_do_meta['workers'];		
	$deadline = $pm_to_do_meta['deadline'];
	$status = $pm_to_do_meta['status'];
	$progresses = $pm_to_do_meta['progresses'];	
	
	if($status=='default' || empty($status)){$status = 'none';}
		
	update_post_meta( get_the_ID(), 'task_id', $pm_to_do_meta_task_id );	
	update_post_meta( get_the_ID(), 'to_do_workers', $workers );
	update_post_meta( get_the_ID(), 'to_do_deadline', $deadline );	
	update_post_meta( get_the_ID(), 'to_do_status', $status );	
	
	if(!empty($progresses))
	foreach($progresses as $progress){
		
		$author = $progress['author'];
		$user = get_user_by( 'ID', $author );
		$user_login = $user->user_login;
		$content = $progress['content'];
		$time = $progress['time'];
		
		
		$comment_ID = wp_insert_comment( array(
			'comment_post_ID' => get_the_ID(),
			'comment_author' => $user_login,
			'comment_content' => $content,
			'comment_date' => $time,
		) );
		
		
		}
	
	
	endwhile;
	wp_reset_query();


	endif;	
	
	}
















