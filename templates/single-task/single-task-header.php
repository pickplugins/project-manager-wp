<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


$task_id = get_the_id();

$class_pm_functions = new class_pm_functions();
$task_status_list = $class_pm_functions->task_status_list();
$to_do_status_list = $class_pm_functions->to_do_status_list();

$pm_task_meta = get_post_meta( $task_id, 'pm_task_meta', true );
$task_workers = get_post_meta( $task_id, 'task_workers', true );
$task_deadline = get_post_meta( $task_id, 'task_deadline', true );
$task_status = get_post_meta( $task_id, 'task_status', true );
$project_id = get_post_meta( $task_id, 'project_id', true );



$paged = isset( $_GET['paged'] ) ? $_GET['paged'] : 1;


//var_dump($task_workers);

?>
<div class="header full">


    <div class="full">
        <div class="inner">
            <h3 class="post-title-display" >

                <?php  the_title(); ?>
            </h3>


            <div class="task-details">

                <?php the_content(); ?>
            </div>



        </div>
    </div>




    <div class="full">

        <div class="inner task-action">
            <h3>Task action</h3>
            <div class="task-admins meta">
                <div class="title">Task worker's</div>
                <div class="admin-list">
                    <?php
                    $task_workers = isset( $task_workers ) ? $task_workers : array();




                    if(!empty($task_workers))
                    foreach( $task_workers as $admin_id ){

                        echo "<div  user-id=$admin_id id=user-$admin_id class='user user-hover'>".get_avatar( $admin_id, 30 ).'</div>';
                        echo "<div class='user-hover-window user-hover-window-$admin_id'></div>";


                    }
                    ?>


                </div>
            </div>

            <div class="task-deadline meta">
                <div class="title">Task Deadline</div>

                <div class="deadline">

                    <?php

                    $date = date_create($task_deadline);
                    echo date_format($date, 'd M, Y'); ?>
                </div>
            </div>

            <div class="task-status meta">
                <div class="title">Task status</div>

                <div class="status <?php echo $task_status; ?>"><?php echo $task_status_list[$task_status]; ?></div>
            </div>

            <div class="task-project meta">
                <div class="title">Project</div>

                <div class="project-link"><i class="fa fa-external-link" aria-hidden="true"></i> <a href="<?php echo get_permalink($project_id); ?>" ><?php echo get_the_title($project_id); ?></a></div>
            </div>

            <div class="task-project meta">
                <div class="title">Create To-Do</div>

                <div class="project-link"><i class="fa fa-external-link" aria-hidden="true"></i> <a href="<?php echo admin_url();?>post-new.php?post_type=to_do&task_id=<?php echo ($task_id); ?>" >Create To-Do</a></div>
            </div>



        </div>


    </div>


    <div class="report full">
        <div class="inner">
            <h3>Report</h3>


            <?php

            $wp_query_report = new WP_Query (array (

                'post_type' => 'to_do',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key'     => 'task_id',
                        'value'   => $task_id,
                        'compare' => '=',
                    ),
                ),
            ));

            $found_posts = $wp_query_report->found_posts;

            //var_dump($found_posts);
            $to_do_count_none = 0;
            $to_do_count_hold = 0;
            $to_do_count_completed = 0;
            $to_do_count_running = 0;

            if ( $wp_query_report->have_posts() ) :
                while ( $wp_query_report->have_posts() ) : $wp_query_report->the_post();

                    $to_do_status_post = get_post_meta(get_the_ID(), 'to_do_status', true);
                    //echo get_the_title();
                    //var_dump($task_status);

                    if($to_do_status_post=='none'){

                        $to_do_count_none +=1;

                    }
                    elseif($to_do_status_post=='hold'){
                        $to_do_count_hold +=1;
                    }
                    elseif($to_do_status_post=='completed'){
                        $to_do_count_completed +=1;
                    }
                    elseif($to_do_status_post=='running'){
                        $to_do_count_running +=1;
                    }



                endwhile;
                wp_reset_query();
                //wp_reset_postdata();
            endif;



            ?>

            <div class="full">
                <div class="inner">

                    <div class="meta">
                        <div class="title">Total To-do</div>
                        <div class="task-count"><?php echo $found_posts; ?></div>

                    </div>

                    <div class="meta">
                        <div class="title">Completed To-do</div>
                        <div class="task-count"><?php echo $to_do_count_completed; ?></div>

                    </div>

                    <div class="meta">
                        <div class="title">On Hold To-do</div>
                        <div class="task-count"><?php echo $to_do_count_hold; ?></div>

                    </div>


                    <div class="meta">
                        <div class="title">Running To-do</div>
                        <div class="task-count"><?php echo $to_do_count_running; ?></div>

                    </div>

                    <div class="meta">
                        <div class="title">None To-do</div>
                        <div class="task-count"><?php echo $to_do_count_none; ?></div>

                    </div>




                </div>
            </div>








        </div>

    </div>







    <div class="task-list full">
        <div class="inner">

            <h3>To-do List</h3>

            <?php

            //	}

            //var_dump($task_id);

            $meta_query[] = array(
                'key'     => 'task_id',
                'value'   => $task_id,
                'compare' => '=',
            );



            //var_dump($task_status);

            $wp_query = new WP_Query (array (
                'post_type' => 'to_do',
                'post_status' => 'publish',
                'order' => 'DESC',
                'orderby' => 'date',
                'posts_per_page' => 5,
                'paged' => (int)$paged,
                'meta_query' => $meta_query,
            ));


            if ( $wp_query->have_posts() ) :
                while ( $wp_query->have_posts() ) : $wp_query->the_post();

                    $pm_task_meta	= get_post_meta( get_the_ID(), 'pm_task_meta', true );
                    $to_do_status	= get_post_meta( get_the_ID(), 'to_do_status', true );
                    $to_do_deadline	= get_post_meta( get_the_ID(), 'to_do_deadline', true );
                    $to_do_workers	= get_post_meta( get_the_ID(), 'to_do_workers', true );

                    $workers		= isset( $pm_task_meta['workers'] ) ? $pm_task_meta['workers'] : array();
                    //$deadline		= isset( $pm_task_meta['deadline'] ) ? $pm_task_meta['deadline'] : __('No Deadline !', PM_TEXTDOMAIN );
                    $task_id 	= isset( $pm_task_meta['task_id'] ) ? $pm_task_meta['task_id'] : '';


                    ?>
                    <div class="task">
                        <div class="half">
                            <div class="inner">
                                <div class="title"><a href="<?php echo pm_get_permalink(get_the_ID()); ?>"><?php echo get_the_title(); ?></a></div>
                                <div class="details"><?php echo get_the_excerpt(); ?></div>
                            </div>
                        </div>

                        <div class="half">
                            <div class="inner">

                                <div class="task-user meta">
                                    <div class="title">To-do User's</div>
                                    <div class="user-list">

                                        <?php

                                        if(!empty($to_do_workers))
                                        foreach( $to_do_workers as $worker_id )
                                            echo '<div class="user">'.get_avatar( $worker_id, 32 ).'</div>';
                                        ?>

                                    </div>
                                </div>

                                <div class="task-deadline meta">
                                    <div class="title">To-do Deadline</div>
                                    <div class="deadline">

                                        <?php

                                        $date = date_create($to_do_deadline);
                                        echo date_format($date, 'd M, Y'); ?>


                                    </div>
                                </div>

                                <div class="task-status meta">
                                    <div class="title">To-do Status</div>
                                    <div class="status <?php echo $to_do_status; ?>">
											<span class="status-icon">

											<?php

                                            if($to_do_status=='running'){

                                                ?>
                                                <i class="fa fa-bolt"></i>
                                                <?php

                                            }
                                            elseif($to_do_status=='none'){
                                                ?>
                                                <i class="fa fa-circle-o"></i>
                                                <?php
                                            }

                                            elseif($to_do_status=='hold'){
                                                ?>
                                                <i class="fa fa-ban"></i>
                                                <?php
                                            }
                                            elseif($to_do_status=='completed'){
                                                ?>
                                                <i class="fa fa-check"></i>
                                                <?php
                                            }
                                            else{

                                            }


                                            ?>

											</span>
                                        <span class="status-text"><?php if(!empty($to_do_status_list[$task_status])) echo $to_do_status_list[$to_do_status]; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    // }
                endwhile;

                $big = 999999999;
                $paginate = array(
                    'base' => '%_%',
                    'format' => '?paged=%#%',
                    'current' => max( 1, $paged ),
                    'total' => $wp_query->max_num_pages
                );
                echo '<div class="paginate">'.paginate_links($paginate).'</div>';

                wp_reset_query();
            else:
                echo __('No Task Found',PM_TEXTDOMAIN);
            endif;

            ?>




        </div>



    </div>










</div>

