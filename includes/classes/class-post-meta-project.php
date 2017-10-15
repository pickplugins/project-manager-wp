<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_pm_post_meta_project{
	
	public function __construct(){

		add_action('add_meta_boxes', array($this, 'meta_boxes_project'));
		add_action('save_post', array($this, 'meta_boxes_project_save'));
		
		
	}
	
	public function project_metabox_callback( $post ){
		
		$class_pm_functions = new class_pm_functions();
		
		$post_statuss = array_merge( $class_pm_functions->pm_project_status(), array( 'draft'=>__('Draft',PM_TEXTDOMAIN), 'pending'=>__('Pending',PM_TEXTDOMAIN), 'publish'=>__('Published',PM_TEXTDOMAIN), 'private'=>__('Private',PM_TEXTDOMAIN), 'trash'=>__('Trash',PM_TEXTDOMAIN)) );	
		
	}
	

	
	public function meta_boxes_project($post_type) {
		
		$post_types = array('project');
		if (in_array($post_type, $post_types)) {
		
			add_meta_box('project_metabox',
				__( 'Project data', PM_TEXTDOMAIN ),
				array($this, 'project_meta_box_function'),
				$post_type,
				'normal',
				'high'
			);
				
		}
	}
	
	public function project_meta_box_function($post) {
 
        wp_nonce_field('project_nonce_check', 'project_nonce_check_value');

		global $post;
		
		$project_id = $post->ID;
		
		$class_pm_functions = new class_pm_functions();
		$task_status_list = $class_pm_functions->task_status_list();
		$project_status_list = $class_pm_functions->project_status_list();		
		
		$pm_project_meta = get_post_meta( $post->ID, 'pm_project_meta', true );
		$project_admin = get_post_meta( $post->ID, 'project_admin', true );		
		$project_clients = get_post_meta( $post->ID, 'project_clients', true );			
		$project_deadline = get_post_meta( $post->ID, 'project_deadline', true );			
		$project_status = get_post_meta( $post->ID, 'project_status', true );			
		
		//$pm_project_status = isset( $pm_project_meta['status'] ) ? $pm_project_meta['status'] : '';
		
		//$project_post_data = get_post($project_id);
		// echo '<pre>'; print_r( $pm_project_meta ); echo '</pre>';
		$paged = isset( $_GET['paged'] ) ? $_GET['paged'] : 1;
		$task_status = isset( $_GET['task_status'] ) ? $_GET['task_status'] : '';		
		
		
		//var_dump($task_status);
		
		?> 
		
		<div class="project-meta"> 
			
            <div class="half">
            	<div class="inner">
                    <h3 class="post-title-display" >
						<span class="edit-post-title"><i class="fa fa-pencil"></i></span>
						<?php echo ( ! empty( $post->post_title ) ) ? $post->post_title : __('<span>Project Title</span>', PM_TEXTDOMAIN ); ?>
                    </h3>
                    <input class="post_title" type="text" name="post_title" value="<?php echo  $post->post_title; ?>" />
                    
                    <div class="project-details">
						<span class="edit-post-content"><i class="fa fa-pencil"></i></span>
						<?php echo ( ! empty( $post->post_content ) ) ? wpautop( $post->post_content ) : __('<span>Project Description</span>', PM_TEXTDOMAIN ); ?>
                    </div>
                    
					<div class="post_content"><?php wp_editor( $post->post_content, 'content', array( 'editor_height' => 200 ) ); ?></div>
                    
                </div>
            </div>
            

            <div class="half">
            
            	<div class="inner">
                    <div class="project-admins meta">
						<div class="title">Project Admin's</div>
                        <div class="admin-list">
							<?php 
							//$project_admin = isset( $project_admin ) ? $project_admin : array();
							if(empty($project_admin)) {$project_admin = array(); }
							foreach( $project_admin as $admin_id ) {
									
								echo "<div  user-id=$admin_id id=user-$admin_id class='user user-hover'>".get_avatar( $admin_id, 30 ).'</div>'; 
								echo "<div class='user-hover-window user-hover-window-$admin_id'></div>";
								echo "<input type='hidden' id='user-input-$admin_id' name='project_admin[]' value=$admin_id />";
								
							}
							?>
                            <div id="user_adding_window" class="user add">
                                <i class="fa fa-plus"></i>
                                <div class="search-user">
									<input type="text" class="user-name" value="" />
									<div class="user-result"></div>
                                </div>
                            </div>  
							<div class="user close-search-window" style="display:none;"><i class="fa fa-times"></i></div>
                        </div>
                    </div>
                    
                    <div class="project-clients meta">
                        <div class="title">Project Client's</div>
                        <div class="client-list">
							<?php 

							if(empty($project_clients)) {$project_clients = array(); }
							foreach( $project_clients as $client_id ) {
									
								echo "<div  client-id=$client_id id=client-$client_id class='client client-hover'>".get_avatar( $client_id, 30 );
								echo "<div class='client-hover-window client-hover-window-$client_id'></div>";
								echo "<input type='hidden' id='client-input-$client_id' name='project_clients[]' value=$client_id /></div>";
							}
							?>
                            <div class="client add">
                                <i class="fa fa-plus"></i>
								<div class="search-client">
									<input type="text" class="client-name" value="" />
									<div class="client-result"></div>
                                </div>
                            </div> 
							<div class="client close-search-window" style="display:none;"><i class="fa fa-times"></i></div>
		
    
                        </div>
                    

                    </div>                    
                    
                    <div class="project-deadline meta">
                    	<div class="title">Project Deadline</div>
						
						<div class="deadline"><input type="text" name="project_deadline" id="project_deadline" value="<?php echo $project_deadline; ?>"placeholder="12/05/2016" /> </div>
                    </div>     

                </div>
							
				<div class="inner">
				
					<div class="project-status meta">
                    	<div class="title">Project Status</div>
						<select name="project_status" id="project_status">
						<?php
						
						
						foreach( $project_status_list as $slug => $title ) {
							
							$selected = ( $slug == $project_status ) ? 'selected' : '';
							
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
	
		'post_type' => 'task',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key'     => 'project_id',
				'value'   => $project_id,
				'compare' => '=',
				),
			),
		));

	$found_posts = $wp_query_report->found_posts;

//var_dump($found_posts);
	$task_count_none = 0;
	$task_count_hold = 0;	
	$task_count_completed = 0;	
	$task_count_running = 0;		
		
	if ( $wp_query_report->have_posts() ) :
	while ( $wp_query_report->have_posts() ) : $wp_query_report->the_post();

		$task_status_post = get_post_meta(get_the_ID(), 'task_status', true);
		//echo get_the_title();
		//var_dump($task_status);
		
		if($task_status_post=='none'){
			
			$task_count_none +=1; 
			
			}
		elseif($task_status_post=='hold'){
			$task_count_hold +=1; 
			}
		elseif($task_status_post=='completed'){
			$task_count_completed +=1; 
			}			
		elseif($task_status_post=='running'){
			$task_count_running +=1; 
			}			
			
			
		
	endwhile;
	wp_reset_query();
	//wp_reset_postdata();
	endif;



?>        
                
		<div class="full">
			<div class="inner">
            
                <div class="meta">
                    <div class="title">Total task</div>
                    <div class="task-count"><?php echo $found_posts; ?></div>
                
                </div>
            
                <div class="meta">
                    <div class="title">Completed task</div>
                    <div class="task-count"><?php echo $task_count_completed; ?></div>
                
                </div>  
                
                <div class="meta">
                    <div class="title">On Hold task</div>
                    <div class="task-count"><?php echo $task_count_hold; ?></div>
                
                </div>                 
                
                
                <div class="meta">
                    <div class="title">Running task</div>
                    <div class="task-count"><?php echo $task_count_running; ?></div>
                
                </div>                 
                        
                <div class="meta">
                    <div class="title">None task</div>
                    <div class="task-count"><?php echo $task_count_none; ?></div>
                
                </div>                         
                        
                        
                          
            
            </div>
        </div>           
                
                
                
                
                
                
                
                
                </div>
                
           </div>         
            
            
            
            <div class="task-list full">
            	<div class="inner">
                
                    <h3>Task List</h3>
                    
                    	<?php
                 		// if($current_user->ID==$project_post_data->post_author){
						
						?>
                        <a class="button add-to-do" href="post-new.php?post_type=task&project_id=<?php echo $project_id; ?>">Add task</a>
                        
						
                        <select class="filter-task-status">
                       		<option <?php if($task_status=='') echo 'selected'; ?> value="">All</option>                        
                       		<option <?php if($task_status=='none') echo 'selected'; ?> value="none">None</option>
                        	<option <?php if($task_status=='completed') echo 'selected'; ?> value="completed">Completed</option>
                        	<option <?php if($task_status=='hold') echo 'selected'; ?> value="hold">Hold</option> 
                            <option <?php if($task_status=='running') echo 'selected'; ?> value="running">Running</option> 
                        </select>
                        
                        
                        <?php
						
						//	}
					
					
						$meta_query[] = array(
									'key'     => 'project_id',
									'value'   => $project_id,
									'compare' => '=',
								); 
							
						if(!empty($task_status)){
							
							$meta_query[] = array(
										'key'     => 'task_status',
										'value'   => $task_status,
										'compare' => '=',
									);	
							
							}
							
							//var_dump($task_status);
						
						$wp_query = new WP_Query (array (
							'post_type' => 'task',
							'post_status' => 'publish',
							'order' => 'DESC',	
							'orderby' => 'date',
							'posts_per_page' => 5,
							'paged' => (int)$paged,
							'meta_query' => $meta_query,
						));
					
					
						if ( $wp_query->have_posts() ) :
						while ( $wp_query->have_posts() ) : $wp_query->the_post();
					
						$pm_task_meta	= get_post_meta( get_the_ID(), 'pm_task_meta', true );
						$task_status	= get_post_meta( get_the_ID(), 'task_status', true );	
						$task_deadline	= get_post_meta( get_the_ID(), 'task_deadline', true );	
						$task_workers	= get_post_meta( get_the_ID(), 'task_workers', true );		
						
						$workers		= isset( $pm_task_meta['workers'] ) ? $pm_task_meta['workers'] : array();
						//$deadline		= isset( $pm_task_meta['deadline'] ) ? $pm_task_meta['deadline'] : __('No Deadline !', PM_TEXTDOMAIN );
						$project_id 	= isset( $pm_task_meta['project_id'] ) ? $pm_task_meta['project_id'] : '';
						
						
						?>
						<div class="task">
							<div class="half">
								<div class="inner">
									<div class="title"><a href="<?php echo pm_get_permalink(get_the_ID()); ?>"><?php echo get_the_title(); ?></a></div>
									<div class="details"><?php echo get_the_excerpt(); ?></div> 
								</div>
							</div>
							
							<div class="half">
								<div class="inner">
									
									<div class="task-user meta">
										<div class="title">Task User's</div>
										<div class="user-list">
										
										<?php
										foreach( $task_workers as $worker_id )
										echo '<div class="user">'.get_avatar( $worker_id, 32 ).'</div>';
										?>
										
										</div>
									</div>
										
									<div class="task-deadline meta">
										<div class="title">Task Deadline</div>
										<div class="deadline"><?php echo $task_deadline; ?></div>
									</div>                        
											
									<div class="task-status meta">
										<div class="title">Task Status</div>
										<div class="status <?php echo $task_status; ?>">
											<span class="status-icon">
											
											<?php 
											
											if($task_status=='running'){
												
												?>
												<i class="fa fa-bolt"></i>
												<?php
												
												}
											elseif($task_status=='none'){
												?>
												<i class="fa fa-circle-o"></i>
												<?php							
												}							
												
											elseif($task_status=='hold'){
												?>
												<i class="fa fa-ban"></i>
												<?php							
												}
											elseif($task_status=='completed'){
												?>
												<i class="fa fa-check"></i>
												<?php
												}							
											else{
												
												}							
																			
												
											?>
											
											</span>
											<span class="status-text"><?php if(!empty($task_status_list[$task_status])) echo $task_status_list[$task_status]; ?></span>
										</div>
									</div>                         
								</div>
							</div>                
						</div>
						<?php
						// }
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
						echo __('No Task Found',PM_TEXTDOMAIN);	
						endif;	
					
					?> 
										
										

                
                </div>
            

            
            </div>
            
            
                        
		</div> <?php
   	}
	
	public function meta_boxes_project_save($post_id){
	 
		if (!isset($_POST['project_nonce_check_value'])) return $post_id;
		$nonce = $_POST['project_nonce_check_value'];
		if (!wp_verify_nonce($nonce, 'project_nonce_check')) return $post_id;

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	 
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		} else {
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}



		$project_admin = stripslashes_deep( $_POST['project_admin'] );
		update_post_meta( $post_id, 'project_admin', $project_admin );

		$project_clients = stripslashes_deep( $_POST['project_clients'] );
		update_post_meta( $post_id, 'project_clients', $project_clients );

		$project_deadline = sanitize_text_field( $_POST['project_deadline'] );
		update_post_meta( $post_id, 'project_deadline', $project_deadline );

		$project_status = sanitize_text_field( $_POST['project_status'] );
		update_post_meta( $post_id, 'project_status', $project_status );


		$pm_project_meta = stripslashes_deep( $_POST['pm_project_meta'] );
		
		update_post_meta( $post_id, 'pm_project_meta', $pm_project_meta );

	}
	
} new class_pm_post_meta_project();