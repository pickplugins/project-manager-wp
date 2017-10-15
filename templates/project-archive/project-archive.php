<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

$class_pm_functions = new class_pm_functions();
$project_status_list = $class_pm_functions->project_status_list();

$req_project_admin = isset($_GET['project_admin']) ?  sanitize_text_field($_GET['project_admin']): '';
$req_project_status = isset($_GET['project_status']) ?  sanitize_text_field($_GET['project_status']): '';
$all_user = get_users();
$current_user_id = get_current_user_id();
?>

<div class="project-archive">


    <form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="GET">

        <select name="project_admin">
            <option value="">All admin</option>
            <?php
            if(!empty($all_user))
                foreach ($all_user as $user){

                    $user_ID = $user->ID;

                    ?>
                    <option <?php if($req_project_admin==$user_ID) echo 'selected';?> value="<?php echo $user_ID; ?>"><?php echo $user->display_name; ?></option>
                    <?php


                }

            ?>



        </select>

        <select name="project_status">
            <option value="">All status</option>
            <?php
            if(!empty($project_status_list))
                foreach ($project_status_list as $status_index=>$status){



                    ?>
                    <option <?php if($req_project_status==$status_index) echo 'selected';?> value="<?php echo $status_index; ?>"><?php echo $status; ?></option>
                    <?php


                }

            ?>



        </select>


        <?php wp_nonce_field( 'nonce_pm_project_filter' ); ?>
        <input type="submit" value="Submit">
    </form>



    <div class="project-list">
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



    if(!empty($req_project_status)){

        $meta_query[] = array(

            'key' => 'project_status',
            'value' => $req_project_status,
            'compare' => '=',

        );

    }


    if(!empty($req_project_admin)){

        $meta_query[] = array(

            'key' => 'project_admin',
            'value' => $req_project_admin,
            'compare' => 'LIKE',

        );

    }
    else{
        $meta_query[] = array(

            'key' => 'project_admin',
            'value' => $current_user_id,
            'compare' => 'LIKE',

        );

    }









    $wp_query = new WP_Query(
        array (
            'post_type' => 'project',
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

        $project_id = get_the_id();
        $project_title = get_the_title();
        $project_link = get_permalink();
        $project_deadline = get_post_meta($project_id, 'project_deadline', true);
        $project_status = get_post_meta($project_id, 'project_status', true);
        $project_admin = get_post_meta($project_id, 'project_admin', true);
        $project_clients = get_post_meta($project_id, 'project_clients', true);

        $project_deadline = new DateTime($project_deadline);
        $project_deadline = $project_deadline->format('d M, Y');

        $task_query = new WP_Query( array( 'post_type' => 'task', 'post_status' => 'publish', 'meta_key' => 'project_id', 'meta_value' => $project_id ) );

        $total_tasks =  $task_query->found_posts;

        $categories = wp_get_post_terms( $project_id, 'project_cat' );

        $categories_html = '';
        foreach($categories as $category){

            $category_id = $category->term_id;
            $categories_html.= '<a href="#">'.$category->name.'</a> ';
        }

        $admin_html = '';

        if(!empty($project_admin))
        foreach($project_admin as $admin){

            $admin_data = get_user_by('ID', $admin);
            $display_name = $admin_data->display_name;
            $admin_html.= '<img title="'.$display_name.'" src="'.get_avatar_url($admin, array('size'=>30)).'" />';
        }


    ?>
    <div class="item">
        <a href="<?php echo $project_link;?>" class="project-title"><?php echo $project_title; ?></a>
        <div class="meta">

            <?php if(!empty($project_admin)):?>
            <span class="admin"><?php echo $admin_html; ?></span>
            <?php endif;?>

            <span class="status <?php echo $project_status; ?>">Status: <?php echo $project_status_list[$project_status]; ?></span>
            <span class="total-tasks">Total tasks: <?php echo $total_tasks; ?></span>
            <span class="deadline">Deadline: <?php echo $project_deadline; ?></span>
            <?php if(!empty($categories)):?>
            <span class="categories"><?php echo  $categories_html; ?></span>
            <?php endif;?>

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

       echo __('No project found','classified_maker');

    endif;

    ?>


</div>

