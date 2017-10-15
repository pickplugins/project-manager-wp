<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class class_pm_functions{
	
	public function __construct() {

		//add_action('add_meta_boxes', array($this, 'meta_boxes_project'));
		//add_action('save_post', array($this, 'meta_boxes_project_save'));

	}
	
	
	
	public function project_status_list() {
		
		$project_status = array('none'=>__('None', PM_TEXTDOMAIN), 'running'=>__('Running', PM_TEXTDOMAIN), 'hold'=>__('On Hold', PM_TEXTDOMAIN), 'completed'=>__('Completed', PM_TEXTDOMAIN), );
		
		$project_status_list = apply_filters('project_status_list', $project_status);
		
		return $project_status_list;
		}		
	
	
	
	public function task_status_list() {
		
		$task_status = array('none'=>__('None', PM_TEXTDOMAIN), 'running'=>__('Running', PM_TEXTDOMAIN), 'hold'=>__('On Hold', PM_TEXTDOMAIN), 'completed'=>__('Completed', PM_TEXTDOMAIN), );
		
		$task_status_list = apply_filters('task_status_list', $task_status);
		
		return $task_status_list;
		}	
	
	
	public function to_do_status_list() {
		
		$to_do_status = array('none'=>__('None', PM_TEXTDOMAIN), 'running'=>__('Running', PM_TEXTDOMAIN), 'hold'=>__('On Hold', PM_TEXTDOMAIN), 'completed'=>__('Completed', PM_TEXTDOMAIN), );
		
		$to_do_status_list = apply_filters('task_status_list', $to_do_status);
		
		return $to_do_status_list;
		}		
	
	
	
	
	
	
	
	
	public function pm_breadcrumb_menu_items_function() {
		
		$page_id_ask_project_id 	= get_option( 'pm_page_project_post', '' );
		$page_id_ask_project_title = get_the_title($page_id_ask_project_id);
		
		$pm_page_myaccount_id 		= get_option( 'pm_page_myaccount', '' );
		$pm_page_myaccount_title = get_the_title($pm_page_myaccount_id);

		$pm_page_project_archive_id 		= get_option( 'pm_page_project_archive', '' );

		$current_user = wp_get_current_user();
		if( $current_user->ID != 0 ) {
			$author_id = $current_user->user_login; 
			
			}
		else{$author_id='';}
		
		
		$menu_items = array(
			
			'add_project' => array(
				'title' => empty( $page_id_ask_project_title ) ? __( 'Ask Question', PM_TEXTDOMAIN ) : $page_id_ask_project_title,
				'link'	=> empty( $page_id_ask_project_id ) ? '' : get_the_permalink( $page_id_ask_project_id ),
			),
			
			'my_account' => array(
				'title' => empty( $pm_page_myaccount_title ) ? __( 'My Account', PM_TEXTDOMAIN ) : $pm_page_myaccount_title,
				'link'	=> empty( $pm_page_myaccount_id ) ? '' : get_the_permalink( $pm_page_myaccount_id ),
			),
			
			'my_project' => array(
				'title' => __( 'My Question', PM_TEXTDOMAIN ),
				'link'	=> empty( $pm_page_project_archive_id ) ? '#' : get_the_permalink( $pm_page_project_archive_id ).'?author='.$author_id,
			),			
			
			
		);
		
		return apply_filters( 'pm_filter_breadcrumb_menu_items', $menu_items );
	}
	
	public function pm_project_archive_filter_options() {
		
		$sorter = array(
			'' => __( 'Default Sorting', PM_TEXTDOMAIN ),
			'title' => __( 'Sort by Title', PM_TEXTDOMAIN ),
			'comment_count' => __( 'Sort by Comment Count', PM_TEXTDOMAIN ),
			'date_older' => __( 'Sort by Older Questions', PM_TEXTDOMAIN ),
		);
		
		return apply_filters( 'pm_project_archive_filter_options', $sorter );
	}
	
	public function pm_project_list_sections() {
		
		$sections = array(
			'project_icon' => array(
				'css_class'	=> 'project_icon',
				'title'		=> '<i class="fa fa-angle-down"></i>',
			),
			'project_title' => array(
				'css_class'	=> 'project_title',
				'title'		=> __('Question Title', PM_TEXTDOMAIN),
			),
			'project_status' => array(
				'css_class'	=> 'project_status',
				'title'		=> __('Status', PM_TEXTDOMAIN),
			),
			'project_date' => array(
				'css_class'	=> 'project_date',
				'title'		=> __('Date', PM_TEXTDOMAIN),
			),
			'project_answer' => array(
				'css_class'	=> 'project_answer',
				'title'		=> __('Answers', PM_TEXTDOMAIN),
			),
			
		);
		
		return array_merge($sections, apply_filters( 'pm_filters_project_list_sections',array() ) );
	}
	
	public function pm_statuses() {
		return array(
			'default'		=> __('Default',PM_TEXTDOMAIN),
			'running'		=> __('Running',PM_TEXTDOMAIN),
			'hold'			=> __('On Hold',PM_TEXTDOMAIN),
			'completed'		=> __('Completed',PM_TEXTDOMAIN),
		);
	}
		
	public function pm_get_pages() {
		$array_pages[''] = __('None',PM_TEXTDOMAIN);
		
		foreach( get_pages() as $page )
		if ( $page->post_title ) $array_pages[$page->ID] = $page->post_title;
		
		return $array_pages;
	}
	
	public function post_type_input_fields(){
		
		$input_fields['project_title'] = array(
			'meta_key'=>'project_title',
			'css_class'=>'project_title',
			'required'=>'yes',
			'placeholder'=>__('Write project title here',PM_TEXTDOMAIN),
			'title'=>__('Question Title', PM_TEXTDOMAIN),
			'option_details'=>__('Question title here', PM_TEXTDOMAIN),					
			'input_type'=>'text',
			'input_values'=>'',
		);
			
		$input_fields['project_content'] = array(
			'meta_key'=>'project_content',
			'css_class'=>'project_content',
			'required'=>'yes',
			'title'=>__('Question Descriptions', PM_TEXTDOMAIN),
			'option_details'=>__('Write project descriptions here', PM_TEXTDOMAIN),					
			'input_type'=>'wp_editor',
			'input_values'=>'',
		);		
		
		$input_fields['project_status'] = array(
			'meta_key'=>'project_status',
			'css_class'=>'project_status',
			'title'=>__('Question Status', PM_TEXTDOMAIN),
			'option_details'=>__('Set the project status', PM_TEXTDOMAIN),					
			'input_type'=>'select',
			'input_values'=>'',
			'input_args'=> apply_filters( 'pm_filter_quesstion_status', array( 'default'=>'Default', 'private'=>'Private' ) ),
		);		
							
		$input_fields['project_cat'] = array(
				'meta_key'=>'project_cat',
				'css_class'=>'project_cat',
				'required'=>'yes',
				'display'=>'yes',	
				'placeholder'=>'project_cat',
				'title'=>__('Question Category', PM_TEXTDOMAIN),
				'option_details'=>__('Select project category.', PM_TEXTDOMAIN),					
				'input_type'=>'select_hierarchy',
				'input_values'=>array(''),
				'input_args'=> pm_get_terms('project_cat'),
		);

		$input_fields['project_tags'] = array(
				'meta_key'=>'project_tags',
				'css_class'=>'project_tags',
				'placeholder'=>__('Tags 1, Tags 2',PM_TEXTDOMAIN),
				'title'=>__('Question tags', PM_TEXTDOMAIN),
				'option_details'=>__('Choose project tags, comma separated', PM_TEXTDOMAIN),
				'required'=>'no',
				'input_type'=>'text',
				'input_values'=>'',
		);
		
		$input_fields_all = apply_filters( 'pm_filter_project_input_fields', $input_fields );

		return $input_fields_all;
	}
	

} new class_pm_functions();