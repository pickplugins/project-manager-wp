<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


$project_id = get_the_id();

$class_pm_functions = new class_pm_functions();
$task_status_list = $class_pm_functions->task_status_list();
$project_status_list = $class_pm_functions->project_status_list();

$pm_project_meta = get_post_meta( $project_id, 'pm_project_meta', true );
$project_admin = get_post_meta( $project_id, 'project_admin', true );
$project_clients = get_post_meta( $project_id, 'project_clients', true );
$project_deadline = get_post_meta( $project_id, 'project_deadline', true );
$project_status = get_post_meta( $project_id, 'project_status', true );


$paged = isset( $_GET['paged'] ) ? $_GET['paged'] : 1;




?>
<div class="header full">


    <div class="full">
        <div class="inner">
            <h3 class="post-title-display" >

                <?php  the_title(); ?>
            </h3>


            <div class="project-details">

                <?php the_content(); ?>
            </div>



        </div>
    </div>




    <div class="full">

        <div class="inner project-action">
            <h3>Project Action</h3>
            <div class="project-admins meta">
                <div class="title">Project Admin's</div>
                <div class="admin-list">
                    <?php
                    //$project_admin = isset( $project_admin ) ? $project_admin : array();
                    if(!empty($project_admin))
                    foreach( $project_admin as $admin_id ) {

                        echo "<div  user-id=$admin_id id=user-$admin_id class='user user-hover'>".get_avatar( $admin_id, 30 ).'</div>';
                        echo "<div class='user-hover-window user-hover-window-$admin_id'></div>";


                    }
                    ?>


                </div>
            </div>

            <div class="project-clients meta">
                <div class="title">Project Client's</div>
                <div class="client-list">
                    <?php

                    if(!empty($project_clients))
                    foreach( $project_clients as $client_id ) {

                        echo "<div  client-id=$client_id id=client-$client_id class='client client-hover'>".get_avatar( $client_id, 30 ).'</div>';
                        echo "<div class='client-hover-window client-hover-window-$client_id'></div>";

                    }
                    ?>


                </div>


            </div>

            <div class="project-status meta">
                <div class="title">Project Deadline</div>

                <div class="deadline"><?php echo $project_deadline; ?></div>
            </div>

            <div class="project-deadline meta">
                <div class="title">Project Status</div>

                <div class="deadline"><?php echo $project_status_list[$project_status]; ?></div>
            </div>

            <div class="project-deadline meta">
                <div class="title">Project Status</div>

                <div class="project-link"><i class="fa fa-external-link" aria-hidden="true"></i> <a href="<?php echo admin_url();?>post-new.php?post_type=task&project_id=<?php echo ($project_id); ?>" >Create Task</a></div>
            </div>



        </div>



    </div>


    <div class="report full">
        <div class="inner">
            <h3>Report</h3>


            <?php

            $wp_query_report = new WP_Query (array (

                'post_type' => 'task',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key'     => 'project_id',
                        'value'   => $project_id,
                        'compare' => '=',
                    ),
                ),
            ));

            $found_posts = $wp_query_report->found_posts;

            //var_dump($found_posts);
            $task_count_none = 0;
            $task_count_hold = 0;
            $task_count_completed = 0;
            $task_count_running = 0;

            if ( $wp_query_report->have_posts() ) :
                while ( $wp_query_report->have_posts() ) : $wp_query_report->the_post();

                    $task_status_post = get_post_meta(get_the_ID(), 'task_status', true);
                    //echo get_the_title();
                    //var_dump($task_status);

                    if($task_status_post=='none'){

                        $task_count_none +=1;

                    }
                    elseif($task_status_post=='hold'){
                        $task_count_hold +=1;
                    }
                    elseif($task_status_post=='completed'){
                        $task_count_completed +=1;
                    }
                    elseif($task_status_post=='running'){
                        $task_count_running +=1;
                    }



                endwhile;
                wp_reset_query();
                //wp_reset_postdata();
            endif;



            ?>

            <div class="full">
                <div class="inner">

                    <div class="meta">
                        <div class="title">Total task</div>
                        <div class="task-count"><?php echo $found_posts; ?></div>

                    </div>

                    <div class="meta">
                        <div class="title">Completed task</div>
                        <div class="task-count"><?php echo $task_count_completed; ?></div>

                    </div>

                    <div class="meta">
                        <div class="title">On Hold task</div>
                        <div class="task-count"><?php echo $task_count_hold; ?></div>

                    </div>


                    <div class="meta">
                        <div class="title">Running task</div>
                        <div class="task-count"><?php echo $task_count_running; ?></div>

                    </div>

                    <div class="meta">
                        <div class="title">None task</div>
                        <div class="task-count"><?php echo $task_count_none; ?></div>

                    </div>




                </div>
            </div>








        </div>

    </div>







    <div class="task-list full">
        <div class="inner">

            <h3>Task List</h3>

            <?php

            //	}


            $meta_query[] = array(
                'key'     => 'project_id',
                'value'   => $project_id,
                'compare' => '=',
            );

            if(!empty($task_status)){

                $meta_query[] = array(
                    'key'     => 'task_status',
                    'value'   => $task_status,
                    'compare' => '=',
                );

            }

            //var_dump($task_status);

            $wp_query = new WP_Query (array (
                'post_type' => 'task',
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
                    $task_status	= get_post_meta( get_the_ID(), 'task_status', true );
                    $task_deadline	= get_post_meta( get_the_ID(), 'task_deadline', true );
                    $task_workers	= get_post_meta( get_the_ID(), 'task_workers', true );

                    $workers		= isset( $pm_task_meta['workers'] ) ? $pm_task_meta['workers'] : array();
                    //$deadline		= isset( $pm_task_meta['deadline'] ) ? $pm_task_meta['deadline'] : __('No Deadline !', PM_TEXTDOMAIN );
                    $project_id 	= isset( $pm_task_meta['project_id'] ) ? $pm_task_meta['project_id'] : '';


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
                                    <div class="title">Task User's</div>
                                    <div class="user-list">

                                        <?php
                                        foreach( $task_workers as $worker_id )
                                            echo '<div class="user">'.get_avatar( $worker_id, 32 ).'</div>';
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
                                    <div class="title">Task Status</div>
                                    <div class="status <?php echo $task_status; ?>">
											<span class="status-icon">

											<?php

                                            if($task_status=='running'){

                                                ?>
                                                <i class="fa fa-bolt"></i>
                                                <?php

                                            }
                                            elseif($task_status=='none'){
                                                ?>
                                                <i class="fa fa-circle-o"></i>
                                                <?php
                                            }

                                            elseif($task_status=='hold'){
                                                ?>
                                                <i class="fa fa-ban"></i>
                                                <?php
                                            }
                                            elseif($task_status=='completed'){
                                                ?>
                                                <i class="fa fa-check"></i>
                                                <?php
                                            }
                                            else{

                                            }


                                            ?>

											</span>
                                        <span class="status-text"><?php if(!empty($task_status_list[$task_status])) echo $task_status_list[$task_status]; ?></span>
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

