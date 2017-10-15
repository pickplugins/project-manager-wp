<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


$to_do_id = get_the_id();

$class_pm_functions = new class_pm_functions();
$to_do_status_list = $class_pm_functions->to_do_status_list();


$task_id = get_post_meta( $to_do_id, 'task_id', true );
$to_do_workers = get_post_meta( $to_do_id, 'to_do_workers', true );
$to_do_deadline = get_post_meta( $to_do_id, 'to_do_deadline', true );
$to_do_status = get_post_meta( $to_do_id, 'to_do_status', true );


$paged = isset( $_GET['paged'] ) ? $_GET['paged'] : 1;




?>
<div class="header full">


    <div class="half">
        <div class="inner">
            <h3 class="post-title-display" >

                <?php  the_title(); ?>
            </h3>


            <div class="to_do-details">

                <?php the_content(); ?>
            </div>



        </div>
    </div>




    <div class="half">

        <div class="inner">
            <div class="to_do-worker meta">
                <div class="title">to_do worker's</div>
                <div class="worker-list">
                    <?php
                    //$to_do_admin = isset( $to_do_admin ) ? $to_do_admin : array();
                    if(!empty($to_do_workers))
                    foreach( $to_do_workers as $worker ) {

                        echo "<div  user-id=$worker id=user-$worker class='worker worker-hover'>".get_avatar( $worker, 30 ).'</div>';



                    }
                    ?>


                </div>
            </div>

            <div class="to_do-deadline meta">
                <div class="title">to_do Deadline</div>

                <div class="deadline"><?php echo $to_do_deadline; ?></div>
            </div>

        </div>

        <div class="inner">

            <div class="to_do-status meta">
                <div class="title">to_do Status</div>
                <select name="to_do_status" id="to_do_status">
                    <?php

                    if(!empty($to_do_status_list))
                    foreach( $to_do_status_list as $slug => $title ) {

                        $selected = ( $slug == $to_do_status ) ? 'selected' : '';

                        echo '<option '.$selected.' value="'.$slug.'">'.$title.'</option>';
                    }
                    ?>
                </select>
            </div>

        </div>

    </div>




</div>

