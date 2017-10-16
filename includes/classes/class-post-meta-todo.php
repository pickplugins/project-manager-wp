<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_pm_post_meta_to_do{
	
	public function __construct(){

		add_action('add_meta_boxes', array($this, 'meta_boxes_to_do'));
		add_action('save_post', array($this, 'meta_boxes_to_do_save'));
		
		
	}
	
	public function meta_boxes_to_do($post_type) {
		
		$post_types = array('to_do');
		if (in_array($post_type, $post_types)) {
		
			add_meta_box('to_do_metabox',
				__( 'Todo Data Box', PM_TEXTDOMAIN ),
				array($this, 'to_do_meta_box_function'),
				$post_type,
				'normal',
				'high'
			);
				
		}
	}
	
	public function to_do_meta_box_function($post) {
 
        wp_nonce_field('to_do_nonce_check', 'to_do_nonce_check_value');
		
		$class_pm_functions	= new class_pm_functions();
		$to_do_status_list 		= $class_pm_functions->to_do_status_list();
		
		$pm_to_do_meta 	= get_post_meta( $post->ID, 'pm_to_do_meta', true );
		
		$to_do_status = get_post_meta( $post->ID, 'to_do_status', true );		
		$to_do_deadline = get_post_meta( $post->ID, 'to_do_deadline', true );				
		$to_do_workers = get_post_meta( $post->ID, 'to_do_workers', true );
		$to_do_progresses = get_post_meta( $post->ID, 'to_do_progresses', true );		
		
		if(empty($to_do_progresses)){$to_do_progresses = array(); }
		
		
		$task_id 		= get_post_meta( $post->ID, 'task_id', true );
		$project_id 		= get_post_meta( $task_id, 'project_id', true );		
		
		
		//$progresses		= isset( $pm_to_do_meta['progresses'] ) ? $pm_to_do_meta['progresses'] : array();
		
		if(!empty($_GET['task_id'])){
			$task_id = $_GET['task_id'];
		}
		
		?> 
		
		<div class="to_do-meta"> 
			
            <div class="half">
            	<div class="inner">
                    <h3 class="post-title-display" >
						<span class="edit-post-title"><i class="fa fa-pencil" aria-hidden="true"></i></span>
						<?php echo  $post->post_title; ?>
                    </h3>
                    <input class="post_title" type="text" name="post_title" value="<?php echo  $post->post_title; ?>" />
                    
                    <div class="todo-details">
						<span class="edit-post-content"><i class="fa fa-pencil" aria-hidden="true"></i></span>
						<?php echo wpautop( $post->post_content ); ?>
                    </div>
					
                    <div class="post_content"><?php wp_editor( $post->post_content, 'content', array( 'editor_height' => 200 ) ); ?></div>
                    
                </div>
            </div>
            

            <div class="half">
            
            	<div class="inner">
                    <div class="task-name meta">
                        <div class="title">Task Name</div>
						<select name="task_id" class="pm-task-ajax-search">
						<?php echo '<option value="'.$task_id.'" selected>'.get_the_title( $task_id ).'</option>'; ?>
						</select>
                    </div> 
                    
                    <div class="project-link meta">
                        <div class="title">Task Link</div>
						<a href="<?php echo get_admin_url().'post.php?post='.$task_id.'&action=edit'; ?>">Go to task</a>
                    </div>  
                    
                    
                    <div class="project-link meta">
                        <div class="title">Project Link</div>
						<a href="<?php echo get_admin_url().'post.php?post='.$project_id.'&action=edit'; ?>">Go to project</a>
                    </div>  
                    
                    
                    
                    
                    
                                       
                </div>
                
				<div class="inner">
					<div class="to_do-worker meta">
                        <div class="title">Todo Worker's</div>
                        <div class="worker-list"> <?php 
							if(empty($to_do_workers)) {$to_do_workers = array(); }
							
							foreach( $to_do_workers as $worker_id ) {
								echo "<div worker-id=$worker_id id=worker-$worker_id class='worker worker-hover'>".get_avatar( $worker_id, 30 )."</div>"; 
								echo "<div class='worker-hover-window worker-hover-window-$worker_id'></div>";
								echo "<input id='worker-id-$worker_id' type='hidden' name='to_do_workers[]' value=$worker_id />";
							} ?>
                            <div id="worker_adding_window" class="worker add">
                                <i class="fa fa-plus"></i>
                                <div class="search-worker">
									<input type="text" class="worker-name" value="" />
									<div class="worker-result"></div>
                                </div>
                            </div>  
							<div class="worker close-search-window" style="display:none;"><i class="fa fa-times"></i></div>
                        </div>
                    </div>                    
                    
                    <div class="to_do-deadline meta">
                    	<div class="title">Task Deadline</div>
						
						<div class="deadline"><input type="text" name="to_do_deadline" id="to_do_deadline" value="<?php echo $to_do_deadline; ?>"placeholder="12/05/2016" /> </div>
                    </div>  
					
					<div class="to_do-status meta">
                    	<div class="title">Todo Status</div>
    					<select name="to_do_status" id="to_do_status">
						<?php

						
						
						foreach( $to_do_status_list as $slug => $title ) {
							
							$selected = ( $slug == $to_do_status ) ? 'selected' : '';
							
							echo '<option '.$selected.' value="'.$slug.'">'.$title.'</option>';
						}
						?>
						</select>
                    </div>   
                    
                </div>
            </div>            
			 

			
		</div> 
		
		<?php
   	}
	
	public function meta_boxes_to_do_save($post_id){
	 
		if (!isset($_POST['to_do_nonce_check_value'])) return $post_id;
		$nonce = $_POST['to_do_nonce_check_value'];
		if (!wp_verify_nonce($nonce, 'to_do_nonce_check')) return $post_id;

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	 
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
	 
		$task_id = stripslashes_deep( $_POST['task_id'] );
		update_post_meta( $post_id, 'task_id', $task_id );
		
		//$pm_to_do_meta_new = stripslashes_deep( $_POST['pm_to_do_meta'] );
		//$pm_to_do_meta_ori = get_post_meta( $post_id, 'pm_to_do_meta', true );
		
		$to_do_workers = stripslashes_deep( $_POST['to_do_workers'] );		
		update_post_meta( $post_id, 'to_do_workers', $to_do_workers );		
		
		$to_do_status = sanitize_text_field( $_POST['to_do_status'] );		
		update_post_meta( $post_id, 'to_do_status', $to_do_status );		
		
		$to_do_deadline = sanitize_text_field( $_POST['to_do_deadline'] );		
		update_post_meta( $post_id, 'to_do_deadline', $to_do_deadline );		
		
		$to_do_progresses = stripslashes_deep( $_POST['to_do_progresses'] );		
		update_post_meta( $post_id, 'to_do_progresses', $to_do_progresses );		
		
		
		//var_dump($to_do_status);
		
		//if( !is_array( $pm_to_do_meta_ori ) ) $pm_to_do_meta_ori = array();
		
		//update_post_meta( $post_id, 'pm_to_do_meta', array_merge( $pm_to_do_meta_ori, $pm_to_do_meta_new ) );
		
	}
	
} new class_pm_post_meta_to_do();