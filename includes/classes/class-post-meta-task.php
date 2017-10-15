<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_pm_post_meta_task{
	
	public function __construct(){

		add_action('add_meta_boxes', array($this, 'meta_boxes_task'));
		add_action('save_post', array($this, 'meta_boxes_task_save'));
		
		
	}
	
	public function meta_boxes_task($post_type) {
		
		$post_types = array('task');
		if (in_array($post_type, $post_types)) {
		
			add_meta_box('task_metabox',
				__( 'Task data', PM_TEXTDOMAIN ),
				array($this, 'task_meta_box_function'),
				$post_type,
				'normal',
				'high'
			);
				
		}
	}
	
	public function task_meta_box_function($post) {
 
        wp_nonce_field('task_nonce_check', 'task_nonce_check_value');
		global $post;
		
		$task_id = $post->ID;
		
		$class_pm_functions = new class_pm_functions();
		$to_do_status_list = $class_pm_functions->to_do_status_list();
		
		$project_id = get_post_meta(get_the_ID(), 'project_id', true);
		$project_post_data = get_post($project_id);
		global $current_user;
		
		//$pm_task_meta = get_post_meta( $post->ID, 'pm_task_meta', true );
		$task_status = get_post_meta( $post->ID, 'task_status', true );		
		$task_deadline = get_post_meta( $post->ID, 'task_deadline', true );				
		$task_workers = get_post_meta( $post->ID, 'task_workers', true );			
		
		$project_id = get_post_meta( $post->ID, 'project_id', true );
		
		//var_dump($project_id);
		// $project_id 		= isset( $pm_task_meta['project_id'] ) ? $pm_task_meta['project_id'] : '';
		
		
		//$pm_task_status 	= isset( $pm_task_meta['status'] ) ? $pm_task_meta['status'] : '';
		
		// echo '<pre>'; print_r($pm_task_meta); echo '</pre>';
		
		if(!empty($_GET['project_id'])){
			
			$project_id = $_GET['project_id'];
			
			}
		
		$paged = isset( $_GET['paged'] ) ? $_GET['paged'] : 1;
		$to_do_status = isset( $_GET['to_do_status'] ) ? $_GET['to_do_status'] : '';	
		
		
		?> 
		
		<div class="task-meta"> 
			
            <div class="half">
            	<div class="inner">
                    <h3 class="post-title-display" >
						<span class="edit-post-title"><i class="fa fa-pencil" aria-hidden="true"></i></span>
						<?php echo  $post->post_title; ?>
                    </h3>
                    <input class="post_title" type="text" name="post_title" value="<?php echo  $post->post_title; ?>" />
                    
                    <div class="task-details">
						<span class="edit-post-content"><i class="fa fa-pencil" aria-hidden="true"></i></span>
						<?php echo $post->post_content; ?>
                    </div>
                    
                    <div class="post_content"><?php wp_editor( $post->post_content, 'content', array( 'editor_height' => 200 ) ); ?></div>
					
                </div>

            
            </div>
            

            <div class="half">
            
            	<div class="inner">

                    <div class="project-name meta">
                        <div class="title">Project Name</div>
						<!--
						<div class="selectRow">
							<input type="hidden" class="pm-project-ajax-search" name='pm_task_meta[project_id]' />
						</div> -->
						
						<select name="project_id" class="pm-project-ajax-search">
						<?php echo '<option value="'.$project_id.'" selected>'.get_the_title( $project_id ).'</option>'; ?>
						</select> 

                    </div> 
                    
                    
                    <div class="project-link meta">
                        <div class="title">Project Link</div>
						<a href="<?php echo get_admin_url().'post.php?post='.$project_id.'&action=edit'; ?>">Go to project</a>
                    </div>                     
                    
                    
                    
                    
                                       
					
                </div>
                
				<div class="inner">

					<div class="task-worker meta">
                        <div class="title">Task Worker's</div>
                        <div class="worker-list">
                            <?php 
							//$task_workers = isset( $task_workers ) ? $task_workers : array();
							if(empty($task_workers)) {$task_workers = array(); }
							
							foreach( $task_workers as $worker_id ) {
									
								echo "<div worker-id=$worker_id id=worker-$worker_id class='worker worker-hover'>".get_avatar( $worker_id, 30 )."</div>"; 
								echo "<div class='worker-hover-window worker-hover-window-$worker_id'></div>";
								echo "<input type='hidden' id='worker-id-$worker_id' name='task_workers[]' value=$worker_id />";
							}
							?>
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
                    
  
                    <div class="task-deadline meta">
                    	<div class="title">Task Deadline</div>
						
						<div class="deadline"><input type="text" name="task_deadline" id="task_deadline" value="<?php echo $task_deadline; ?>" placeholder="12/05/2016" /> </div>
                    </div>  
					
					<div class="task-status meta">
                    	<div class="title">Task Status</div>
						<select name="task_status" id="task_status">
						<?php
						$task_status_list = $class_pm_functions->task_status_list();
						
						foreach( $task_status_list as $slug => $title ) {
							
							$selected = ( $slug == $task_status ) ? 'selected' : '';
							
							echo '<option '.$selected.' value="'.$slug.'">'.$title.'</option>';
						}
						?>
						</select>
                    </div>   
                    
                    
                
                </div>
                

            
            </div>            
			
            
            
            
            
            <div class="report full">
            	<div class="inner">   
                <h3>Report</h3>
                
                
<?php

	$wp_query_report = new WP_Query (array (
	
		'post_type' => 'to_do',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key'     => 'task_id',
				'value'   => $task_id,
				'compare' => '=',
				),
			),
		));

	$found_posts = $wp_query_report->found_posts;

//var_dump($found_posts);
	$to_do_count_none = 0;
	$to_do_count_hold = 0;	
	$to_do_count_completed = 0;	
	$to_do_count_running = 0;		
		
	if ( $wp_query_report->have_posts() ) :
	while ( $wp_query_report->have_posts() ) : $wp_query_report->the_post();

		$to_do_status_post = get_post_meta(get_the_ID(), 'to_do_status', true);
		//echo get_the_title();
		//var_dump($task_status);
		
		if($to_do_status_post=='none'){
			
			$to_do_count_none +=1; 
			
			}
		elseif($to_do_status_post=='hold'){
			$to_do_count_hold +=1; 
			}
		elseif($to_do_status_post=='completed'){
			$to_do_count_completed +=1; 
			}			
		elseif($to_do_status_post=='running'){
			$to_do_count_running +=1; 
			}			
			
			
		
	endwhile;
	wp_reset_query();
	//wp_reset_postdata();
	endif;



?>        
                
		<div class="full">
			<div class="inner">
            
                <div class="meta">
                    <div class="title">Total To-Do</div>
                    <div class="task-count"><?php echo $found_posts; ?></div>
                
                </div>
            
                <div class="meta">
                    <div class="title">Completed To-Do</div>
                    <div class="task-count"><?php echo $to_do_count_completed; ?></div>
                
                </div>  
                
                <div class="meta">
                    <div class="title">On Hold To-Do</div>
                    <div class="task-count"><?php echo $to_do_count_hold; ?></div>
                
                </div>                 
                
                
                <div class="meta">
                    <div class="title">Running To-Do</div>
                    <div class="task-count"><?php echo $to_do_count_running; ?></div>
                
                </div>                 
                          
                <div class="meta">
                    <div class="title">None To-Do</div>
                    <div class="task-count"><?php echo $to_do_count_none; ?></div>
                
                </div>             
            
            
            </div>
        </div>           
                
                
                
                
                
                
                
                
                </div>
                
           </div>         
            
            
 
            
            
            
            
            
            
            
            
            
            
            <div class="to-do-list full">
            	<div class="inner">
                
                    <h3>To-Do List</h3>
                    
                    
                    <?php
                    if($current_user->ID==$project_post_data->post_author){
						
						?>
                        <a class="button add-to-do" href="post-new.php?post_type=to_do&task_id=<?php echo $task_id; ?>">Add To-Do</a>
                        <select class="filter-to-do-status">
                       		<option <?php if($to_do_status=='') echo 'selected'; ?> value="">All</option>                        
                       		<option <?php if($to_do_status=='none') echo 'selected'; ?> value="none">None</option>
                        	<option <?php if($to_do_status=='completed') echo 'selected'; ?> value="completed">Completed</option>
                        	<option <?php if($to_do_status=='hold') echo 'selected'; ?> value="hold">Hold</option> 
                            <option <?php if($to_do_status=='running') echo 'selected'; ?> value="running">Running</option> 
                        </select>
						
						
                        <?php
						
						}
					
					?>
                    
                    
	<?php

	
	
	$meta_query[] = array(
				'key'     => 'task_id',
				'value'   => $task_id,
				'compare' => '=',
			); 
		
	if(!empty($to_do_status)){
		
		$meta_query[] = array(
					'key'     => 'to_do_status',
					'value'   => $to_do_status,
					'compare' => '=',
				);	
		
		}
	
	
	
	
	
	$wp_query = new WP_Query (array (
		'post_type' => 'to_do',
		'post_status' => 'publish',
		'order' => 'DESC',	
		'orderby' => 'date',
		'posts_per_page' => 5,
		'paged' => (int)$paged,
		'meta_query' => $meta_query,
	));

	if ( $wp_query->have_posts() ) :
	while ( $wp_query->have_posts() ) : $wp_query->the_post();
	
		$task_id		= get_post_meta( get_the_ID(), 'task_id', true );
		//$pm_to_do_meta	= get_post_meta( get_the_ID(), 'pm_to_do_meta', true );
		$to_do_status	= get_post_meta( get_the_ID(), 'to_do_status', true );		
		$to_do_deadline	= get_post_meta( get_the_ID(), 'to_do_deadline', true );
		$to_do_workers	= get_post_meta( get_the_ID(), 'to_do_workers', true );					
		
		//$workers		= isset( $pm_to_do_meta['workers'] ) ? $pm_to_do_meta['workers'] : array();
		//$deadline		= isset( $pm_to_do_meta['deadline'] ) ? $pm_to_do_meta['deadline'] : __('No Deadline !', PM_TEXTDOMAIN );
		
		
	
	?>
    <div class="to-do">
        <div class="half">
			<div class="inner">
				<div class="title"><a href="<?php echo pm_get_permalink(get_the_ID()); ?>"><?php echo get_the_title(); ?></a></div>
				<div class="details"><?php echo get_the_excerpt(); ?></div> 
            </div>
        </div>
                
        <div class="half">
			<div class="inner">
                <div class="to-do-user meta">
					<div class="title">To Do by</div>
					<div class="user-list">
					<?php
						
						if(empty($to_do_workers)) {$to_do_workers = array(); }
					
						foreach( $to_do_workers as $worker_id )
						echo '<div class="user">'.get_avatar( $worker_id, 32 ).'</div>';
					?>
					</div>
                </div>

                <div class="to-do-deadline meta">
                    <div class="title">To-Do Deadline</div>
                    <div class="deadline"><?php echo $to_do_deadline; ?></div>
                </div>                        

                <div class="to-do-status meta">
					<div class="title">To-Do Status</div>
					<div class="status <?php echo $to_do_status; ?>">
						<span class="status-icon">
                        <?php 
						
						if($to_do_status=='running'){
							
							?>
                            <i class="fa fa-bolt"></i>
                            <?php
							
							}
							
						elseif($to_do_status=='none'){
							?>
                            <i class="fa fa-circle-o"></i>
                            <?php							
							}	
							
						elseif($to_do_status=='hold'){
							?>
                            <i class="fa fa-ban"></i>
                            <?php							
							}
						elseif($to_do_status=='completed'){
							?>
                            <i class="fa fa-check"></i>
                            <?php
							}							
						else{
							
							}							
														
							
						?>
                        
                         
                        
                        </span>
						<span class="status-text"><?php if(!empty($to_do_status_list[$to_do_status])) echo $to_do_status_list[$to_do_status]; ?></span>
                    </div>
                </div>                         
            </div>
		</div>                

    </div>
    <?php
	endwhile;
	
	
	$big = 999999999;
	$paginate = array(
		'base' => '%_%',
		'format' => '?paged=%#%',
		'current' => max( 1, $paged ),
		'total' => $wp_query->max_num_pages
	);
	echo '<div class="paginate">'.paginate_links($paginate).'</div>';
	
	
	
	wp_reset_query();
	else:
		echo __('<p>No To-Do found</p>',PM_TEXTDOMAIN);	
	endif;
	
	?> 
				</div>
           
            </div>
            
            <div class="half task-comment full">
            	<div class="inner">
                
                    <h3>Task Conversation</h3>
                    
                    <div class="comment-input full">
                    	
                        <?php wp_editor( '', 'comment-content', array( 'editor_height' => 200, 'drag_drop_upload' => true, 'tiny' => true) ); ?>
                    	<span class="button add-comment ajax-add-comment" post_id="<?php echo $post->ID; ?>">Add Comment</span>
						<div class="comment-loading-icon"><i class="fa fa-cog fa-spin"></i></div>
                    </div>
 
                    <div class="comment-list">

					<?php
						$comments = get_comments( "post_id=$post->ID" );
						
						foreach( $comments as $comment ) {
							
							$date = human_time_diff( strtotime( $comment->comment_date ), current_time( 'timestamp' ) );
							
							echo '<div class="comment" id="comment-'.$comment->comment_ID.'">';
							echo '<div class="thumb">'.get_avatar( 1, 50 ).'</div>';
                            echo '<div class="details">';
                                
							echo '<div class="name">'.$comment->comment_author.'</div>';					
							echo '<div class="date"><i class="fa fa-clock-o"></i> '.__( $date. ' ago', PM_TEXTDOMAIN   ).'</div>';
                            
							echo '<div class="content">'.wpautop( $comment->comment_content ).'</div>';
                            echo '</div></div>';
							
						}

					?>
                    </div>
                    
                    
                    
                </div>
                
            </div>
            
            
            
            
            
            
                        
		</div> 
		
		<?php
   	}
	
	public function meta_boxes_task_save($post_id){
	 
		if (!isset($_POST['task_nonce_check_value'])) return $post_id;
		$nonce = $_POST['task_nonce_check_value'];
		if (!wp_verify_nonce($nonce, 'task_nonce_check')) return $post_id;

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	 
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
	 
		//$pm_task_meta = stripslashes_deep( $_POST['pm_task_meta'] );
		$task_status = sanitize_text_field( $_POST['task_status'] );
		$task_deadline = sanitize_text_field( $_POST['task_deadline'] );	
		$task_workers = stripslashes_deep( $_POST['task_workers'] );					
		$project_id = sanitize_text_field( $_POST['project_id'] );
		
		
		
		//update_post_meta( $post_id, 'pm_task_meta', $pm_task_meta );
		update_post_meta( $post_id, 'task_status', $task_status );	
		update_post_meta( $post_id, 'task_deadline', $task_deadline );	
		update_post_meta( $post_id, 'task_workers', $task_workers );		
				
		update_post_meta( $post_id, 'project_id', $project_id );
		
		// Saving the Meta Data from ARRAY
		
	}
	
} new class_pm_post_meta_task();