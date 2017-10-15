<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

$class_pm_functions = new class_pm_functions();
$task_status_list = $class_pm_functions->task_status_list();



//echo '<pre>'.var_export($all_user, true).'</pre>';

if(!empty($_POST)){

	$template_list_ids = isset($_POST['template_list_ids']) ?  sanitize_text_field($_POST['template_list_ids']): '';

	update_option('template_list_ids', $template_list_ids);

}
else{

    $template_list_ids = get_option('template_list_ids', '4,5');

}



if($_GET['has_key']){

    $has_key = $_GET['has_key'];
	$task_id = $_GET['task_id'];

	$has_key_value = get_post_meta($task_id, $has_key, true);

	if($has_key_value=='yes'){

		update_post_meta($task_id, $has_key, 'no' );
    }
    else{
	    update_post_meta($task_id, $has_key, 'yes' );
    }


}














if ( get_query_var('paged') ) {

	$paged = get_query_var('paged');

} elseif ( get_query_var('page') ) {

	$paged = get_query_var('page');

} else {

	$paged = 1;

}








?>

<div class="task-archive">

    <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="POST">

        <p>
            <textarea name="template_list_ids" ><?php echo $template_list_ids; ?></textarea>
        </p>




        <?php wp_nonce_field( 'nonce_' ); ?>
        <input type="submit" value="Update">
    </form>

<?php



$tax_query = array();
$meta_query = array();

/*
 *
 *
 *
 $tax_query[] = array(
    array(
        'taxonomy' => 'ads_cat',
        'field' => 'slug',
        'terms' => $trem,
    )
);


$meta_query[] = array(

    'key' => $meta_key,
    'value' => $meta_value,
    'compare' => '=',

);
 *
 * */

$current_user_id = get_current_user_id();

//echo "<pre>".var_export($current_user_id, true)."</pre>";

if(!empty($req_project_id)){

    $meta_query[] = array(

        'key' => 'project_id',
        'value' => $req_project_id,
        'compare' => '=',

    );

}

if(!empty($req_task_status)){

    $meta_query[] = array(

        'key' => 'task_status',
        'value' => $req_task_status,
        'compare' => '=',

    );

}


if(!empty($req_user_id)){

    $meta_query[] = array(

        'key' => 'task_workers',
        'value' => $req_user_id,
        'compare' => 'LIKE',

    );

}
else{
    $meta_query[] = array(

        'key' => 'task_workers',
        'value' => $current_user_id,
        'compare' => 'LIKE',

    );

}







$wp_query = new WP_Query(
    array (
        'post_type' => 'task',
        'post_status' => 'publish',
        'orderby' => 'date',
        'post__in' => explode(',',$template_list_ids),
        //'meta_key' => 'task_workers',
        //'meta_value' => array($current_user_id),
        //'meta_compare' => 'NOT IN',
        //'meta_query' => $meta_query,
        //'tax_query' => $tax_query,
        'order' => 'DESC',
        'posts_per_page' => 10,
        'paged' => $paged,

    ) );



?>
    <div class="task-list">
    <?php

    $task_id_html = '';

    if ( $wp_query->have_posts() ) :
    while ( $wp_query->have_posts() ) : $wp_query->the_post();

        $task_id = get_the_id();
        $task_title = get_the_title();
        $task_link = get_permalink();
        $project_id = get_post_meta($task_id, 'project_id', true);
        $task_deadline = get_post_meta($task_id, 'task_deadline', true);
        $task_status = get_post_meta($task_id, 'task_status', true);
        $task_workers = get_post_meta($task_id, 'task_workers', true);
        $task_clients = get_post_meta($task_id, 'task_clients', true);

	    $has_wp = get_post_meta($task_id, 'has_wp', true);
	    $has_html = get_post_meta($task_id, 'has_html', true);
	    $has_psd = get_post_meta($task_id, 'has_psd', true);

	    $task_id_html.= $task_id.',';



        //echo "<pre>".var_export($task_workers, true)."</pre>";

        $task_deadline = new DateTime($task_deadline);
        $task_deadline = $task_deadline->format('d M, Y');

        $task_query = new WP_Query( array( 'post_type' => 'to_do', 'post_status' => 'publish', 'meta_key' => 'task_id', 'meta_value' => $task_id ) );

        $total_to_do =  $task_query->found_posts;

        $categories = wp_get_post_terms( $task_id, 'task_cat' );

        $categories_html = '';


        $task_workers_html = '';

        if(!empty($task_workers))
        foreach($task_workers as $admin){

            $admin_data = get_user_by('ID', $admin);
            $display_name = $admin_data->display_name;
            $task_workers_html.= '<img title="'.$display_name.'" src="'.get_avatar_url($admin, array('size'=>30)).'" />';
        }


    ?>
    <div class="item">
        <a href="<?php echo $task_link;?>" class="task-title"><?php echo $task_title; ?></a>
        <div class="meta">

            <?php if(!empty($task_workers)):?>
            <span class="admin"><?php echo $task_workers_html; ?></span>
            <?php endif;?>
            <span class="p-link"><a href="<?php echo get_permalink($project_id); ?>">Project</a> </span>
            <span class="status <?php echo $task_status; ?>">Status: <?php echo $task_status_list[$task_status]; ?></span>
            <span class="total-tasks">Total To-Do: <?php echo $total_to_do; ?></span>
            <span class="deadline">Deadline: <?php echo $task_deadline; ?></span>

            <p></p>
            <div class="">

                <a href="http://192.168.0.90/server/templates-list/?task_id=<?php echo $task_id; ?>&has_key=has_wp" class="has_key <?php echo $has_wp; ?>">WordPress: <?php echo ucfirst($has_wp); ?></a>
                <a href="http://192.168.0.90/server/templates-list/?task_id=<?php echo $task_id; ?>&has_key=has_html" class="has_key <?php echo $has_html; ?>">HTML: <?php echo ucfirst($has_html); ?></a>
                <a href="http://192.168.0.90/server/templates-list/?task_id=<?php echo $task_id; ?>&has_key=has_psd" class="has_key <?php echo $has_psd; ?>">PSD: <?php echo ucfirst($has_psd); ?></a>

            </div>


        </div>

    </div>

    <?php



    endwhile;

    ?>
    </div>
    <div class="paginate">
        <?php


        $big = 999999999; // need an unlikely integer
        echo paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, $paged ),
            'total' => $wp_query->max_num_pages
        ) );

        ?>
    </div >
        <?php


        wp_reset_query();
    else:

        echo __('No task found','classified_maker');

    endif;


/*
 *
         $wp_query = new WP_Query(
	        array (
		        'post_type' => 'task',
		        'post_status' => 'publish',
		        'orderby' => 'date',
		        'order' => 'ASC',
		        'posts_per_page' => -1,


	        ) );


        if ( $wp_query->have_posts() ) :
        while ( $wp_query->have_posts() ) : $wp_query->the_post();

        $task_id = get_the_id();

        $task_id_html.= $task_id.',';
        endwhile;
        wp_reset_query();
        else:

        echo __('No task found','classified_maker');

        endif;
 *
 * */





    ?>
<pre>

    <?php //echo $task_id_html; ?>
</pre>

</div>

