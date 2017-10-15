<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

	get_header();
	do_action('project_manager_action_before_single_task');

	while ( have_posts() ) : the_post(); 
	?>
	<div id="task-<?php the_ID(); ?>" <?php post_class('single-task entry-content'); ?>>

    <?php do_action('project_manager_action_single_task'); ?>

    </div>
	<?php
	endwhile;
		

	//echo '</div>';
	do_action('project_manager_action_after_single_task');
	
	//get_sidebar();
	get_footer();