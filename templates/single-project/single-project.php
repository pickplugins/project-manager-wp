<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

	get_header();
	do_action('project_manager_action_before_single_project');

	while ( have_posts() ) : the_post(); 
	?>
	<div id="project-<?php the_ID(); ?>" <?php post_class('single-project entry-content'); ?>>

    <?php do_action('project_manager_action_single_project'); ?>

    </div>
	<?php
	endwhile;
		

	//echo '</div>';
	do_action('project_manager_action_after_single_project');
	
	//get_sidebar();
	get_footer();