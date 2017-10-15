<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


$userid = get_current_user_id();
$taxonomy = 'bookmark_cat';



if ( get_query_var('paged') ) {

	$paged = get_query_var('paged');

} elseif ( get_query_var('page') ) {

	$paged = get_query_var('page');

} else {

	$paged = 1;

}




?>

<div class="bookmark-submit">

	<?php

	if(!empty($_POST)){

		$domain = $_POST['domain'];
		$site_link = $_POST['site_link'];
		$blog_link = $_POST['blog_link'];
		$bookmark_cat = $_POST['bookmark_cat'];
		$guest_post_link = $_POST['guest_post_link'];
		$advertise_link = $_POST['advertise_link'];

		$meta_query[] = array(
			'key' => 'site_link',
			'value' => $site_link,
			'compare' => 'LIKE',
		);

		$wp_query = new WP_Query(
			array(
				'post_type' => 'bookmark',
				'post_status' => 'publish',
				'orderby' => 'Date',
				'meta_query' => $meta_query,
				'order' => 'DESC',
				'posts_per_page' => -1,
				//'paged' => $paged,
			) );

		$found_posts = $wp_query->found_posts;

echo '<pre>';
		var_dump($_POST);
		echo '</pre>';
		if(!empty($found_posts)){
			$posts = $wp_query->posts;
			$bookmark_post = $posts[0];
			$bookmark_ID = $bookmark_post->ID;

			wp_set_post_terms( $bookmark_ID, $bookmark_cat, $taxonomy );

			//update_post_meta( $bookmark_ID, 'site_link', $post_thumbnail_id );

			//$ads_post = array( 'ID'=> $bookmark_ID, );

			// Update the post into the database

			//$is_update = wp_update_post($ads_post);

			?>
			<div class=""><i class="fa fa-check-square-o" aria-hidden="true"></i> Bookmark updated</div>
			<?php

		}
		else{

			$post_bookmark = array(
				'post_title'    => $domain,
				'post_content'  => '',
				'post_status'   => 'publish',
				'post_type'   => 'bookmark',
				'post_author'   => $userid,
			);

			$bookmark_ID = wp_insert_post($post_bookmark);
			wp_set_post_terms( $bookmark_ID, $bookmark_cat, $taxonomy );

			update_post_meta( $bookmark_ID, 'site_link', $site_link );
			update_post_meta( $bookmark_ID, 'blog_link', $blog_link );
			update_post_meta( $bookmark_ID, 'guest_post_link', $guest_post_link );
			update_post_meta( $bookmark_ID, 'advertise_link', $advertise_link );


			?>
			<div class=""><i class="fa fa-check" aria-hidden="true"></i> Bookmark added</div>
			<?php




		}










	}

	?>

<form action="#" method="post">

	<p>
	<div class="">Domain</div>
	<input type="text" name="domain" value="">

	</p>


	<p>
		<div class="">Link</div>
		<input type="text" name="site_link" value="">

	</p>

	<p>
	<div class="">Blog link</div>
	<input type="text" name="blog_link" value="">

	</p>


	<p>
		<div class="" onabort="">Caetgory</div>
		<?php

		//wp_set_post_terms( $ads_ID, $cat_id, $taxonomy );



		//var_dump($category_parent_id);

		$args=array(
			'orderby' => 'name',
			'order' => 'ASC',
			'taxonomy' => $taxonomy,
			'hide_empty' => false,
			//'child_of'  => $category_parent_id,
		);

		$categories = get_categories($args);

		if(!empty($categories)){

			$html = '';
			?>
			<select name="bookmark_cat">
		<?php

			foreach($categories as $category){

				$name = $category->name;
				$category_id = $category->cat_ID;

				if($category_id == $cat_id ){

					$html.= '<option class="active" value="'.$category_id.'"><i class="fa fa-check"></i> '.$name.'</option>';
				}
				else{
					$html.= '<option  value="'.$category_id.'"><i class="fa fa-check"></i> '.$name.'</option>';
				}



			}
			echo $html;
			?>
			</select>
			<?php

		}

		?>

	</p>

	<p>
		<div class="">Guest post</div>
		<input type="text" name="guest_post_link" value="">

	</p>

	<p>
		<div class="">Advertise link</div>
		<input type="text" name="advertise_link" value="">

	</p>


	<input type="submit" name="submit" value="Submit">

</form>



	<div class="bookmark-list">

		<?php


		$wp_query = new WP_Query(
			array(
				'post_type' => 'bookmark',
				'post_status' => 'publish',
				'orderby' => 'Date',
				'meta_query' => $meta_query,
				'order' => 'DESC',
				'posts_per_page' => -1,
				'paged' => $paged,
			) );

		$found_posts = $wp_query->found_posts;



		if ( $wp_query->have_posts() ) :
			while ( $wp_query->have_posts() ) : $wp_query->the_post();

				$post_id = get_the_id();

				?>
				<div class=""><i class="fa fa-bookmark-o" aria-hidden="true"></i> <a class="" href="<?php echo get_permalink($post_id); ?>"> <?php echo get_the_title($post_id); ?></a></div>

				<?php

			endwhile;
			wp_reset_query();
		endif;




		?>
		<div class="paginate">

			<?php




			?>
		</div>

	</div>






</div>


<script>

    jQuery(document).ready(function($) {

        $(document).on('submit', '.pm-notify .notify-button', function(){


            //alert(notify_id);
            //$('.pm-notify .notify-reload').fadeIn();
            //$('.pm-notify .notify-count').fadeOut();


        })



    })
</script>




