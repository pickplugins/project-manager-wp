<?php

/*
* @Author 		pickplugins
* Copyright: 	pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_pm_post_types{
	
	public function __construct(){
		
		add_action( 'init', array( $this, 'pm_posttype_project' ), 0 );
		add_action( 'init', array( $this, 'pm_posttype_task' ), 0 );
		add_action( 'init', array( $this, 'pm_posttype_to_do' ), 0 );
        add_action( 'init', array( $this, 'pm_posttype_attendance' ), 0 );
		add_action( 'init', array( $this, 'pm_posttype_salary' ), 0 );
		add_action( 'init', array( $this, 'pm_posttype_bookmark' ), 0 );
		add_action( 'init', array( $this, 'pm_posttype_notice' ), 0 );





		//add_action( 'init', array( $this, 'pm_posttype_member' ), 0 );		
		add_action( 'init', array( $this, 'pm_register_post_status'), 10, 0 );
		
	}
	
	public function pm_posttype_project(){
		if ( post_type_exists( "project" ) )
		return;

		$singular  = __( 'Project', PM_TEXTDOMAIN );
		$plural    = __( 'Projects', PM_TEXTDOMAIN );
	 
	 
		register_post_type( "project",
			apply_filters( "register_post_type_project", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, PM_TEXTDOMAIN ),
					'all_items'             => sprintf( __( 'All %s', PM_TEXTDOMAIN ), $plural ),
					'add_new' 				=> __( 'Add '.$singular, PM_TEXTDOMAIN ),
					'add_new_item' 			=> sprintf( __( 'Add %s', PM_TEXTDOMAIN ), $singular ),
					'edit' 					=> __( 'Edit', PM_TEXTDOMAIN ),
					'edit_item' 			=> sprintf( __( 'Edit %s', PM_TEXTDOMAIN ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', PM_TEXTDOMAIN ), $singular ),
					'view' 					=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', PM_TEXTDOMAIN ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', PM_TEXTDOMAIN ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', PM_TEXTDOMAIN ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', PM_TEXTDOMAIN ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', PM_TEXTDOMAIN ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array('',),
				'show_in_nav_menus' 	=> true,
				//'taxonomies' => array('project_tags'),
				'menu_icon' => 'dashicons-editor-help',
			) )
		); 
			
			$singular  = __( 'Project Category', PM_TEXTDOMAIN );
			$plural    = __( 'Projects Categories', PM_TEXTDOMAIN );
	 
			register_taxonomy( "project_cat",
				apply_filters( 'register_taxonomy_project_cat_object_type', array( 'project' ) ),
	       	 	apply_filters( 'register_taxonomy_project_cat_args', array(
		            'hierarchical' 			=> true,
		            'show_admin_column' 	=> true,					
		            'update_count_callback' => '_update_post_term_count',
		            'label' 				=> $plural,
		            'labels' => array(
						'name'              => $plural,
						'singular_name'     => $singular,
						'menu_name'         => ucwords( $plural ),
						'search_items'      => sprintf( __( 'Search %s', PM_TEXTDOMAIN ), $plural ),
						'all_items'         => sprintf( __( 'All %s', PM_TEXTDOMAIN ), $plural ),
						'parent_item'       => sprintf( __( 'Parent %s', PM_TEXTDOMAIN ), $singular ),
						'parent_item_colon' => sprintf( __( 'Parent %s:', PM_TEXTDOMAIN ), $singular ),
						'edit_item'         => sprintf( __( 'Edit %s', PM_TEXTDOMAIN ), $singular ),
						'update_item'       => sprintf( __( 'Update %s', PM_TEXTDOMAIN ), $singular ),
						'add_new_item'      => sprintf( __( 'Add New %s', PM_TEXTDOMAIN ), $singular ),
						'new_item_name'     => sprintf( __( 'New %s Name', PM_TEXTDOMAIN ),  $singular )
	            	),
		            'show_ui' 				=> true,
		            'public' 	     		=> true,
				    'rewrite' => array(
						'slug' => 'project_cat', // This controls the base slug that will display before each term
						'with_front' => false, // Don't display the category base before "/locations/"
						'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
				),
		        ) )
		    );
			
			
			
			$singular  = __( 'Project Tag', PM_TEXTDOMAIN );
			$plural    = __( 'Project Tags', PM_TEXTDOMAIN );
	 
			register_taxonomy( "project_tags",
				apply_filters( 'register_taxonomy_project_tag_object_type', array( 'project' ) ),
				apply_filters( 'register_taxonomy_project_tag_args', array(
					'hierarchical' 			=> false,
					'show_admin_column' 	=> true,					
					'update_count_callback' => '_update_post_term_count',
					'label' 				=> $plural,
					'labels' => array(
						'name'              => $plural,
						'singular_name'     => $singular,
						'menu_name'         => ucwords( $plural ),
						'search_items'      => sprintf( __( 'Search %s', PM_TEXTDOMAIN ), $plural ),
						'all_items'         => __(  sprintf( 'All %s',$plural   ), PM_TEXTDOMAIN ),
						'parent_item'       => sprintf( __( 'Parent %s', PM_TEXTDOMAIN ), $singular ),
						'parent_item_colon' => sprintf( __( 'Parent %s:', PM_TEXTDOMAIN ), $singular ),
						'edit_item'         => sprintf( __( 'Edit %s', PM_TEXTDOMAIN ), $singular ),
						'update_item'       => sprintf( __( 'Update %s', PM_TEXTDOMAIN ), $singular ),
						'add_new_item'      => sprintf( __( 'Add New %s', PM_TEXTDOMAIN ), $singular ),
						'new_item_name'     => sprintf( __( 'New %s Name', PM_TEXTDOMAIN ),  $singular )
					),
					'show_ui' 				=> true,
					'public' 	     		=> true,
					'rewrite' => array(
						'slug' => 'project_tags', // This controls the base slug that will display before each term
						'with_front' => false, // Don't display the category base before "/locations/"
						'hierarchical' => false // This will allow URL's like "/locations/boston/cambridge/"
				),
				) )
			);
			
			
		}

	public function pm_posttype_task(){
		
		if ( post_type_exists( "task" ) ) return;

		$singular  = __( 'Task', PM_TEXTDOMAIN );
		$plural    = __( 'Tasks', PM_TEXTDOMAIN );
	 
	 
		register_post_type( "task",
			apply_filters( "register_post_type_task", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, PM_TEXTDOMAIN ),
					'all_items'             => __( sprintf(  'All %s', $plural ), PM_TEXTDOMAIN ),
					'add_new' 				=> __( 'Add '.$singular, PM_TEXTDOMAIN ),
					'add_new_item' 			=> sprintf( __( 'Add %s', PM_TEXTDOMAIN ), $singular ),
					'edit' 					=> __( 'Edit', PM_TEXTDOMAIN ),
					'edit_item' 			=> sprintf( __( 'Edit %s', PM_TEXTDOMAIN ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', PM_TEXTDOMAIN ), $singular ),
					'view' 					=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', PM_TEXTDOMAIN ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', PM_TEXTDOMAIN ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', PM_TEXTDOMAIN ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', PM_TEXTDOMAIN ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', PM_TEXTDOMAIN ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array(''),
				'show_in_nav_menus' 	=> false,
				'show_in_menu' 			=> 'edit.php?post_type=project',
				'menu_icon' => 'dashicons-megaphone',
			) )
		); 

	 
			
	 
		}
			
	public function pm_posttype_to_do(){
		
		if ( post_type_exists( "to_do" ) ) return;

		$singular  = __( 'To Do', PM_TEXTDOMAIN );
		$plural    = __( 'To Do\'s', PM_TEXTDOMAIN );
	 
	 
		register_post_type( "to_do",
			apply_filters( "register_post_type_to_do", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, PM_TEXTDOMAIN ),
					'all_items'             => __( sprintf(  'All %s', $plural ), PM_TEXTDOMAIN ),
					'add_new' 				=> __( 'Add '.$singular, PM_TEXTDOMAIN ),
					'add_new_item' 			=> sprintf( __( 'Add %s', PM_TEXTDOMAIN ), $singular ),
					'edit' 					=> __( 'Edit', PM_TEXTDOMAIN ),
					'edit_item' 			=> sprintf( __( 'Edit %s', PM_TEXTDOMAIN ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', PM_TEXTDOMAIN ), $singular ),
					'view' 					=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', PM_TEXTDOMAIN ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', PM_TEXTDOMAIN ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', PM_TEXTDOMAIN ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', PM_TEXTDOMAIN ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', PM_TEXTDOMAIN ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array(''),
				'show_in_nav_menus' 	=> false,
				'show_in_menu' 			=> 'edit.php?post_type=project',
				'menu_icon' => 'dashicons-megaphone',
			) )
		); 

	 
			
	 
		}





    public function pm_posttype_attendance(){

        if ( post_type_exists( "attendance" ) ) return;

        $singular  = __( 'Attendance', PM_TEXTDOMAIN );
        $plural    = __( 'Attendance', PM_TEXTDOMAIN );


        register_post_type( "attendance",
            apply_filters( "register_post_type_attendance", array(
                'labels' => array(
                    'name' 					=> $plural,
                    'singular_name' 		=> $singular,
                    'menu_name'             => __( $singular, PM_TEXTDOMAIN ),
                    'all_items'             => __( sprintf(  'All %s', $plural ), PM_TEXTDOMAIN ),
                    'add_new' 				=> __( 'Add '.$singular, PM_TEXTDOMAIN ),
                    'add_new_item' 			=> sprintf( __( 'Add %s', PM_TEXTDOMAIN ), $singular ),
                    'edit' 					=> __( 'Edit', PM_TEXTDOMAIN ),
                    'edit_item' 			=> sprintf( __( 'Edit %s', PM_TEXTDOMAIN ), $singular ),
                    'new_item' 				=> sprintf( __( 'New %s', PM_TEXTDOMAIN ), $singular ),
                    'view' 					=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
                    'view_item' 			=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
                    'search_items' 			=> sprintf( __( 'Search %s', PM_TEXTDOMAIN ), $plural ),
                    'not_found' 			=> sprintf( __( 'No %s found', PM_TEXTDOMAIN ), $plural ),
                    'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', PM_TEXTDOMAIN ), $plural ),
                    'parent' 				=> sprintf( __( 'Parent %s', PM_TEXTDOMAIN ), $singular )
                ),
                'description' => sprintf( __( 'This is where you can create and manage %s.', PM_TEXTDOMAIN ), $plural ),
                'public' 				=> true,
                'show_ui' 				=> true,
                'capability_type' 		=> 'post',
                'map_meta_cap'          => true,
                'publicly_queryable' 	=> true,
                'exclude_from_search' 	=> false,
                'hierarchical' 			=> false,
                'rewrite' 				=> true,
                'query_var' 			=> true,
                'supports' 				=> array('title'),
                'show_in_nav_menus' 	=> false,
                'show_in_menu' 			=> 'edit.php?post_type=project',
                'menu_icon' => 'dashicons-megaphone',
            ) )
        );




    }



	public function pm_posttype_salary(){

		if ( post_type_exists( "salary" ) ) return;

		$singular  = __( 'Salary', PM_TEXTDOMAIN );
		$plural    = __( 'Salary', PM_TEXTDOMAIN );


		register_post_type( "salary",
			apply_filters( "register_post_type_salary", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, PM_TEXTDOMAIN ),
					'all_items'             => __( sprintf(  'All %s', $plural ), PM_TEXTDOMAIN ),
					'add_new' 				=> __( 'Add '.$singular, PM_TEXTDOMAIN ),
					'add_new_item' 			=> sprintf( __( 'Add %s', PM_TEXTDOMAIN ), $singular ),
					'edit' 					=> __( 'Edit', PM_TEXTDOMAIN ),
					'edit_item' 			=> sprintf( __( 'Edit %s', PM_TEXTDOMAIN ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', PM_TEXTDOMAIN ), $singular ),
					'view' 					=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', PM_TEXTDOMAIN ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', PM_TEXTDOMAIN ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', PM_TEXTDOMAIN ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', PM_TEXTDOMAIN ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', PM_TEXTDOMAIN ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array('title'),
				'show_in_nav_menus' 	=> false,
				'show_in_menu' 			=> 'edit.php?post_type=project',
				'menu_icon' => 'dashicons-megaphone',
			) )
		);




	}



	public function pm_posttype_bookmark(){

		if ( post_type_exists( "bookmark" ) ) return;

		$singular  = __( 'Bookmark', PM_TEXTDOMAIN );
		$plural    = __( 'Bookmarks', PM_TEXTDOMAIN );


		register_post_type( "bookmark",
			apply_filters( "register_post_type_bookmark", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, PM_TEXTDOMAIN ),
					'all_items'             => __( sprintf(  'All %s', $plural ), PM_TEXTDOMAIN ),
					'add_new' 				=> __( 'Add '.$singular, PM_TEXTDOMAIN ),
					'add_new_item' 			=> sprintf( __( 'Add %s', PM_TEXTDOMAIN ), $singular ),
					'edit' 					=> __( 'Edit', PM_TEXTDOMAIN ),
					'edit_item' 			=> sprintf( __( 'Edit %s', PM_TEXTDOMAIN ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', PM_TEXTDOMAIN ), $singular ),
					'view' 					=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', PM_TEXTDOMAIN ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', PM_TEXTDOMAIN ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', PM_TEXTDOMAIN ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', PM_TEXTDOMAIN ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', PM_TEXTDOMAIN ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array('title','custom-fields'),
				'show_in_nav_menus' 	=> false,
				//'show_in_menu' 			=> 'edit.php?post_type=project',
				'menu_icon' => 'dashicons-megaphone',
			) )
		);




		$singular  = __( 'Bookmark Category', PM_TEXTDOMAIN );
		$plural    = __( 'Bookmark Categories', PM_TEXTDOMAIN );

		register_taxonomy( "bookmark_cat",
			apply_filters( 'register_taxonomy_project_cat_object_type', array( 'bookmark' ) ),
			apply_filters( 'register_taxonomy_bookmark_cat_args', array(
				'hierarchical' 			=> true,
				'show_admin_column' 	=> true,
				'update_count_callback' => '_update_post_term_count',
				'label' 				=> $plural,
				'labels' => array(
					'name'              => $plural,
					'singular_name'     => $singular,
					'menu_name'         => ucwords( $plural ),
					'search_items'      => sprintf( __( 'Search %s', PM_TEXTDOMAIN ), $plural ),
					'all_items'         => sprintf( __( 'All %s', PM_TEXTDOMAIN ), $plural ),
					'parent_item'       => sprintf( __( 'Parent %s', PM_TEXTDOMAIN ), $singular ),
					'parent_item_colon' => sprintf( __( 'Parent %s:', PM_TEXTDOMAIN ), $singular ),
					'edit_item'         => sprintf( __( 'Edit %s', PM_TEXTDOMAIN ), $singular ),
					'update_item'       => sprintf( __( 'Update %s', PM_TEXTDOMAIN ), $singular ),
					'add_new_item'      => sprintf( __( 'Add New %s', PM_TEXTDOMAIN ), $singular ),
					'new_item_name'     => sprintf( __( 'New %s Name', PM_TEXTDOMAIN ),  $singular )
				),
				'show_ui' 				=> true,
				'public' 	     		=> true,
				'rewrite' => array(
					'slug' => 'bookmark_cat', // This controls the base slug that will display before each term
					'with_front' => false, // Don't display the category base before "/locations/"
					'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
				),
			) )
		);


		$singular  = __( 'Bookmark tag', PM_TEXTDOMAIN );
		$plural    = __( 'Bookmark tags', PM_TEXTDOMAIN );

		register_taxonomy( "bookmark_tag",
			apply_filters( 'register_taxonomy_project_tag_object_type', array( 'bookmark' ) ),
			apply_filters( 'register_taxonomy_bookmark_tag_args', array(
				'hierarchical' 			=> true,
				'show_admin_column' 	=> true,
				'update_count_callback' => '_update_post_term_count',
				'label' 				=> $plural,
				'labels' => array(
					'name'              => $plural,
					'singular_name'     => $singular,
					'menu_name'         => ucwords( $plural ),
					'search_items'      => sprintf( __( 'Search %s', PM_TEXTDOMAIN ), $plural ),
					'all_items'         => sprintf( __( 'All %s', PM_TEXTDOMAIN ), $plural ),
					'parent_item'       => sprintf( __( 'Parent %s', PM_TEXTDOMAIN ), $singular ),
					'parent_item_colon' => sprintf( __( 'Parent %s:', PM_TEXTDOMAIN ), $singular ),
					'edit_item'         => sprintf( __( 'Edit %s', PM_TEXTDOMAIN ), $singular ),
					'update_item'       => sprintf( __( 'Update %s', PM_TEXTDOMAIN ), $singular ),
					'add_new_item'      => sprintf( __( 'Add New %s', PM_TEXTDOMAIN ), $singular ),
					'new_item_name'     => sprintf( __( 'New %s Name', PM_TEXTDOMAIN ),  $singular )
				),
				'show_ui' 				=> true,
				'public' 	     		=> true,
				'hierarchical' => false,
				'rewrite' => array(
					'slug' => 'bookmark_tag', // This controls the base slug that will display before each term
					'with_front' => false, // Don't display the category base before "/locations/"
					'hierarchical' => false // This will allow URL's like "/locations/boston/cambridge/"
				),
			) )
		);






	}








	public function pm_posttype_member(){
		
		if ( post_type_exists( "member" ) ) return;

		$singular  = __( 'Member', PM_TEXTDOMAIN );
		$plural    = __( 'Members', PM_TEXTDOMAIN );
	 
	 
		register_post_type( "member",
			apply_filters( "register_post_type_member", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, PM_TEXTDOMAIN ),
					'all_items'             => __( sprintf(  'All %s', $plural ), PM_TEXTDOMAIN ),
					'add_new' 				=> __( 'Add '.$singular, PM_TEXTDOMAIN ),
					'add_new_item' 			=> sprintf( __( 'Add %s', PM_TEXTDOMAIN ), $singular ),
					'edit' 					=> __( 'Edit', PM_TEXTDOMAIN ),
					'edit_item' 			=> sprintf( __( 'Edit %s', PM_TEXTDOMAIN ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', PM_TEXTDOMAIN ), $singular ),
					'view' 					=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', PM_TEXTDOMAIN ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', PM_TEXTDOMAIN ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', PM_TEXTDOMAIN ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', PM_TEXTDOMAIN ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', PM_TEXTDOMAIN ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array(''),
				'show_in_nav_menus' 	=> false,
				'show_in_menu' 			=> 'edit.php?post_type=project',
				'menu_icon' => 'dashicons-megaphone',
			) )
		); 

	}




	public function pm_posttype_notice(){

		if ( post_type_exists( "notice" ) ) return;

		$singular  = __( 'Notice', PM_TEXTDOMAIN );
		$plural    = __( 'Notices', PM_TEXTDOMAIN );


		register_post_type( "notice",
			apply_filters( "register_post_type_notice", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, PM_TEXTDOMAIN ),
					'all_items'             => __( sprintf(  'All %s', $plural ), PM_TEXTDOMAIN ),
					'add_new' 				=> __( 'Add '.$singular, PM_TEXTDOMAIN ),
					'add_new_item' 			=> sprintf( __( 'Add %s', PM_TEXTDOMAIN ), $singular ),
					'edit' 					=> __( 'Edit', PM_TEXTDOMAIN ),
					'edit_item' 			=> sprintf( __( 'Edit %s', PM_TEXTDOMAIN ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', PM_TEXTDOMAIN ), $singular ),
					'view' 					=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', PM_TEXTDOMAIN ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', PM_TEXTDOMAIN ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', PM_TEXTDOMAIN ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', PM_TEXTDOMAIN ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', PM_TEXTDOMAIN ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', PM_TEXTDOMAIN ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array('title', 'editor'),
				'show_in_nav_menus' 	=> false,
				'show_in_menu' 			=> 'edit.php?post_type=project',
				'menu_icon' => 'dashicons-megaphone',
			) )
		);

	}
	
	
	
	
	
	
	
	public function pm_register_post_status() {
		
		$class_pm_functions = new class_pm_functions();
		$pm_statuses = $class_pm_functions->pm_statuses();
		
		foreach ( $pm_statuses as $slug => $custom_status ) {

			$args = array(
				'label'                     => $custom_status,
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( "$custom_status <span class='count'>(%s)</span>", "$custom_status <span class='count'>(%s)</span>", PM_TEXTDOMAIN ),
			);

			register_post_status( $slug, $args );
		}
		
	}
	
	
} new class_pm_post_types();