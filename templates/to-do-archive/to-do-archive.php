<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access



$class_pm_functions = new class_pm_functions();
$to_do_status_list = $class_pm_functions->to_do_status_list();


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


$to_do_keyword = isset($_GET['to_do_keyword']) ?  sanitize_text_field($_GET['to_do_keyword']): '';
$req_task_id = isset($_GET['task_id']) ?  sanitize_text_field($_GET['task_id']): '';
$req_user_id = isset($_GET['user_id']) ?  sanitize_text_field($_GET['user_id']): '';
$req_to_do_status = isset($_GET['to_do_status']) ?  sanitize_text_field($_GET['to_do_status']): '';
$posts_per_page = isset($_GET['posts_per_page']) ?  sanitize_text_field($_GET['posts_per_page']): '';


//echo '<pre>'.var_export($to_do_keyword, true).'</pre>';



?>

<div class="to-do-archive">


	<?php //echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>
    <form action="http://192.168.0.90/server/all-to-do/" method="GET">


        <input style="width:150px" type="text" name="to_do_keyword" value="" placeholder="Keyword" />


        <select style="width:100px" class="task-id" name="task_id">
            <option value="">All Task</option>
            <?php
            if ( $project_query->have_posts() ) :
                while ( $project_query->have_posts() ) : $project_query->the_post();

                    ?>
                    <option <?php if($req_task_id==get_the_id()) echo 'selected';?>  value="<?php echo get_the_id(); ?>"><?php echo get_the_title(); ?></option>
                    <?php

                endwhile;
            endif;
            wp_reset_query();

            ?>



        </select>


        <select  name="user_id">
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

        <select name="posts_per_page">
            <option <?php if($posts_per_page ==10) echo 'selected'; ?>  value="10">10</option>
            <option <?php if($posts_per_page ==20) echo 'selected'; ?> value="20">20</option>
            <option <?php if($posts_per_page ==30) echo 'selected'; ?> value="30">30</option>
            <option <?php if($posts_per_page ==50) echo 'selected'; ?> value="50">50</option>
            <option <?php if($posts_per_page ==100) echo 'selected'; ?> value="100">100</option>

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

	    //$meta_query['relation'] = 'AND';

        $meta_query[] = array(

            'key' => 'to_do_status',
            'value' => $req_to_do_status,
            'compare' => '=',

        );

    }


    if(!empty($req_user_id)) {

	    $req_user_id = (int) $req_user_id;


        $meta_query[] = array(

            'key' => 'to_do_workers',
            'value' =>serialize( strval( $req_user_id ) ),
            'compare' => 'LIKE',

        );
    }
    else{
        $current_user_id = get_current_user_id();
	   // var_dump($current_user_id);
        $meta_query[] = array(

            'key' => 'to_do_workers',
            'value' => serialize( strval( $current_user_id ) ),
            'compare' => 'LIKE',

        );

    }


    if(!empty($to_do_keyword)) {

	    $args['s'] = $to_do_keyword;
    }


    if(!empty($posts_per_page)) {

	    $args['posts_per_page'] = $posts_per_page;
    }
    else{
	    $posts_per_page = 10;
    }



    $args['post_type'] = 'to_do';
    $args['post_status'] = 'publish';
    $args['orderby'] = 'date';
    $args['meta_query'] = $meta_query;
    $args['order'] = 'DESC';
    //$args['posts_per_page'] = 10;
    $args['paged'] = $paged;

    //echo "<pre>".var_export($args, true)."</pre>";

    $wp_query = new WP_Query( $args );



    $to_do_count_by_wroker = array();


    $i = 0;
    if ( $wp_query->have_posts() ) :
    while ( $wp_query->have_posts() ) : $wp_query->the_post();

        $to_do_id = get_the_id();
        $to_do_title = get_the_title();
        $to_do_link = get_permalink();

        $task_id = get_post_meta($to_do_id, 'task_id', true);
        $project_id = get_post_meta($task_id, 'project_id', true);

        $to_do_deadline = get_post_meta($to_do_id, 'to_do_deadline', true);
        $to_do_status = get_post_meta($to_do_id, 'to_do_status', true);
        $to_do_workers = get_post_meta($to_do_id, 'to_do_workers', true);
        $to_do_clients = get_post_meta($to_do_id, 'to_do_clients', true);

        $to_do_deadline = new DateTime($to_do_deadline);
        $to_do_deadline = $to_do_deadline->format('d M, Y');


	   //echo "<pre>".var_export($to_do_workers, true)."</pre>";



        $to_do_workers_html = '';

        if(!empty($to_do_workers))
        foreach($to_do_workers as $worker){

            $to_do_count_by_wroker[$worker][$to_do_id] +=1;


            $worker_data = get_user_by('ID', $worker);
            $display_name = $worker_data->display_name;
            $to_do_workers_html.= '<img title="'.$display_name.'" src="'.get_avatar_url($worker, array('size'=>30)).'" />';
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


        $user_count_wp_query = new WP_Query(
            array (
                'post_type' => 'to_do',
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
                'posts_per_page' => -1,


            ) );




        $to_do_count_by_wroker = array();
        $i = 0;
        if ( $user_count_wp_query->have_posts() ) :
            while ( $user_count_wp_query->have_posts() ) : $user_count_wp_query->the_post();
                $to_do_id = get_the_id();
                $to_do_workers = get_post_meta($to_do_id, 'to_do_workers', true);

                if(!empty($to_do_workers))
                    foreach($to_do_workers as $worker){

                        $to_do_count_by_wroker[$worker][$to_do_id] +=1;

                    }

            endwhile;
        endif;

        //echo '<pre>'.var_export($to_do_count_by_wroker, true).'</pre>';

        ?>


    <h2>To-Do Count</h2>
        <table class="to-do-count">

            <thead>
                <tr class="thead">
                    <th colspan="">User</th>
                    <th colspan="">To-Do Count</th>

                </tr>
            </thead>
            <tbody id="" class="">

            <?php

            foreach ($to_do_count_by_wroker as $worker_id=>$worker_to_do){

                $worker_details = get_user_by('ID', $worker_id);
                $display_name = $worker_details->display_name;
                $to_do_count= count($worker_to_do);

                if($worker_id==get_current_user_id()){

                    $its_me_class = 'its-me';
                }
                else{
	                $its_me_class = '';
                }

                ?>
                <tr class="<?php echo $its_me_class; ?>">
                    <td colspan=""> &nbsp;&nbsp; <?php echo $display_name; ?></td>
                    <td colspan=""><?php echo $to_do_count; ?></td>

                </tr>
            <?php


            }

            ?>



            </tbody>




        </table>
        <?php



    else:

        echo __('No to do found','classified_maker');

    endif;

    ?>


</div>

