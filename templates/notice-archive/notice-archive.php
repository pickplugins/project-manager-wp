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
        'post_type' => 'notice',
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

<div class="notice-archive">


	<?php //echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>



    <div class="notice-list">
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



    $args['post_type'] = 'notice';
    $args['post_status'] = 'publish';
    $args['orderby'] = 'date';
    //$args['meta_query'] = $meta_query;
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

        $today_date = new DateTime();
	    $start_date = new DateTime(get_the_date());
	    //$since_start = $start_date->diff(new DateTime('2018-01-10'));
	    $date_diff = $start_date->diff(new DateTime($today_date->format('Y-m-d')));


	     //echo "<pre>".var_export($date_diff->d, true)."</pre>";












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
        <span class="pin-icon"><i class="fa fa-thumb-tack" aria-hidden="true"></i></span>
        <?php
        if($date_diff->d<7):
        ?><span class="new">New</span>
        <?php
        endif;

        ?>
        <a href="<?php echo $to_do_link;?>" class="notice-title"><?php echo $to_do_title; ?></a>
        <div class="meta">
            <span class="date"><?php echo get_the_date('d M, Y'); ?></span>
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

       //echo '<pre>'.var_export($to_do_count_by_wroker, true).'</pre>';




    else:

        echo __('No notice found.','classified_maker');

    endif;

    ?>


</div>

