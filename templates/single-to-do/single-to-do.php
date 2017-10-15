<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

	get_header();
	do_action('project_manager_action_before_single_to_do');
	do_action('project_manager_action_breadcrumb');	
	

	while ( have_posts() ) : the_post(); 
	?>
	<div id="to-do-<?php the_ID(); ?>" <?php post_class('single-to-do entry-content'); ?>>

    <?php do_action('project_manager_action_single_to_do'); ?>

    </div>
	<?php
	endwhile;
		

	//echo '</div>';
	do_action('project_manager_action_after_single_to_do');
	
	//get_sidebar();
	get_footer();