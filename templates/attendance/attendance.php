<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 



$meta_query[] = array(

	'key' => 'year',
	'value' => date('Y'),
	'compare' => '=',

);


$wp_query = new WP_Query(
	array (
		'post_type' => 'attendance',
		'post_status' => 'publish',
		'orderby' => 'date',
		'meta_query' => $meta_query,
		//'tax_query' => $tax_query,
		'order' => 'DESC',
		'posts_per_page' => -1,


	) );



if ( $wp_query->have_posts() ) :

	?>
	<select onchange="if (this.value) window.location.href=this.value">
	<?php

	while ( $wp_query->have_posts() ) : $wp_query->the_post();

		?>
		<option <?php if($_GET['attendance_id']==get_the_id()) echo 'selected'; ?>  value="http://192.168.0.90/server/?attendance_id=<?php echo get_the_id(); ?>"><?php echo get_the_title(); ?></option>
		<?php

	endwhile;

	?>
	</select>
	<?php
endif;



if($_GET['attendance_id']){

	include( PM_PLUGIN_DIR . 'templates/attendance/attendance-by-month.php');

}
else{



	include( PM_PLUGIN_DIR . 'templates/attendance/attendance-current-month.php');
}








