<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access




$project_query = new WP_Query(
    array (
        'post_type' => 'task',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'posts_per_page' => -1,

    ) );

$all_user = get_users();
//echo '<pre>'.var_export($all_user, true).'</pre>';


$req_task_id = isset($_GET['task_id']) ?  sanitize_text_field($_GET['task_id']): '';
$req_user_id = isset($_GET['user_id']) ?  sanitize_text_field($_GET['user_id']): '';
$req_to_do_status = isset($_GET['to_do_status']) ?  sanitize_text_field($_GET['to_do_status']): '';

//var_dump($task_status);



?>

<div class="to-do-archive">



    <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="GET">


        <select class="task-id" name="task_id">
            <option value="">All Task</option>
            <?php
            if ( $project_query->have_posts() ) :
                while ( $project_query->have_posts() ) : $project_query->the_post();

                    ?>
                    <option <?php if($req_task_id==get_the_id()) echo 'selected';?>  value="<?php echo get_the_id(); ?>"><?php echo get_the_title(); ?></option>
                    <?php

                endwhile;
            endif;


            ?>



        </select>


        <select name="user_id">
            <option value="">All User</option>
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

        <select name="to_do_status">
            <option value="">All status</option>
            <?php
            if(!empty($to_do_status_list))
                foreach ($to_do_status_list as $status_index=>$status){



                    ?>
                    <option <?php if($req_to_do_status==$status_index) echo 'selected';?> value="<?php echo $status_index; ?>"><?php echo $status; ?></option>
                    <?php


                }

            ?>



        </select>


        <?php wp_nonce_field( 'nonce_pm_task_filter' ); ?>
        <input type="submit" value="Submit">
    </form>







    <div class="to-do-list">
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

    if(!empty($req_task_id)){

        $meta_query[] = array(

            'key' => 'task_id',
            'value' => $req_task_id,
            'compare' => '=',

        );

    }


    if(!empty($req_to_do_status)){

        $meta_query[] = array(

            'key' => 'to_do_status',
            'value' => $req_to_do_status,
            'compare' => '=',

        );

    }


    if(!empty($req_user_id)) {

        $meta_query[] = array(

            'key' => 'to_do_workers',
            'value' => $req_user_id,
            'compare' => 'LIKE',

        );
    }
    else{
        $current_user_id = get_current_user_id();

        $meta_query[] = array(

            'key' => 'to_do_workers',
            'value' => $current_user_id,
            'compare' => 'LIKE',

        );

    }











    //echo "<pre>".var_export($current_user_id, true)."</pre>";





    $wp_query = new WP_Query(
        array (
            'post_type' => 'to_do',
            'post_status' => 'publish',
            'orderby' => 'date',
            'meta_query' => $meta_query,
            'tax_query' => $tax_query,
            'order' => 'DESC',
            'posts_per_page' => 10,
            'paged' => $paged,

        ) );





    if ( $wp_query->have_posts() ) :
    while ( $wp_query->have_posts() ) : $wp_query->the_post();

        $to_do_id = get_the_id();
        $to_do_title = get_the_title();
        $to_do_link = get_permalink();

        $task_id = get_post_meta($to_do_id, 'project_id', true);
        $project_id = get_post_meta($task_id, 'task_id', true);

        $to_do_deadline = get_post_meta($to_do_id, 'to_do_deadline', true);
        $to_do_status = get_post_meta($to_do_id, 'to_do_status', true);
        $to_do_workers = get_post_meta($to_do_id, 'to_do_workers', true);
        $to_do_clients = get_post_meta($to_do_id, 'to_do_clients', true);

        $to_do_deadline = new DateTime($to_do_deadline);
        $to_do_deadline = $to_do_deadline->format('d M, Y');



        $to_do_workers_html = '';

        if(!empty($to_do_workers))
        foreach($to_do_workers as $admin){

            $admin_data = get_user_by('ID', $admin);
            $display_name = $admin_data->display_name;
            $to_do_workers_html.= '<img title="'.$display_name.'" src="'.get_avatar_url($admin, array('size'=>30)).'" />';
        }


    ?>
    <div class="item">
        <a href="<?php echo $to_do_link;?>" class="to-do-title"><?php echo $to_do_title; ?></a>
        <div class="meta">

            <?php if(!empty($to_do_workers)):?>
            <span class="admin"><?php echo $to_do_workers_html; ?></span>
            <?php endif;?>
            <span class="p-link"><a href="<?php echo get_permalink($project_id); ?>">Project</a> </span>
            <span class="t-link"><a href="<?php echo get_permalink($task_id); ?>">Task</a> </span>
            <span class="status <?php echo $to_do_status; ?>">Status: <?php echo $to_do_status; ?></span>

            <span class="deadline">Deadline: <?php echo $to_do_deadline; ?></span>


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

        echo __('No to do found','classified_maker');

    endif;

    ?>


</div>

