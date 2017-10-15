<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

$class_pm_functions = new class_pm_functions();
$task_status_list = $class_pm_functions->task_status_list();


$project_query = new WP_Query(
    array (
        'post_type' => 'project',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => -1,

    ) );

$all_user = get_users();
//echo '<pre>'.var_export($all_user, true).'</pre>';


$req_project_id = isset($_GET['project_id']) ?  sanitize_text_field($_GET['project_id']): '';
$req_user_id = isset($_GET['user_id']) ?  sanitize_text_field($_GET['user_id']): '';
$req_task_status = isset($_GET['task_status']) ?  sanitize_text_field($_GET['task_status']): '';

//var_dump($task_status);












?>

<div class="task-archive">

    <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="GET">


        <select class="project-id" name="project_id">
            <option value="">All project</option>
            <?php
            if ( $project_query->have_posts() ) :
                while ( $project_query->have_posts() ) : $project_query->the_post();

                        ?>
                        <option <?php if($req_project_id==get_the_id()) echo 'selected';?>  value="<?php echo get_the_id(); ?>"><?php echo get_the_title(); ?></option>
                        <?php

                endwhile;
            endif;


            ?>



        </select>


        <select name="user_id">
            <option value="">All user</option>
            <?php
            if(!empty($all_user))
            foreach ($all_user as $user){

                $user_ID = $user->ID;

                ?>
                <option <?php if($req_user_id==$user_ID) echo 'selected';?> value="<?php echo $user_ID; ?>"><?php echo $user->display_name; ?></option>
                <?php


            }

            ?>



        </select>

        <select name="task_status">
            <option value="">All status</option>
            <?php
            if(!empty($task_status_list))
                foreach ($task_status_list as $status_index=>$status){



                    ?>
                    <option <?php if($req_task_status==$status_index) echo 'selected';?> value="<?php echo $status_index; ?>"><?php echo $status; ?></option>
                    <?php


                }

            ?>



        </select>


        <?php wp_nonce_field( 'nonce_pm_task_filter' ); ?>
        <input type="submit" value="Submit">
    </form>

<?php

if ( get_query_var('paged') ) {

    $paged = get_query_var('paged');

} elseif ( get_query_var('page') ) {

    $paged = get_query_var('page');

} else {

    $paged = 1;

}

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
        //'meta_key' => 'task_workers',
        //'meta_value' => array($current_user_id),
        //'meta_compare' => 'NOT IN',
        'meta_query' => $meta_query,
        //'tax_query' => $tax_query,
        'order' => 'DESC',
        'posts_per_page' => 10,
        'paged' => $paged,

    ) );



?>
    <div class="task-list">
    <?php

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

    ?>


</div>

