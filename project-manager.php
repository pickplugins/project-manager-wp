<?php
/*
Plugin Name: Project Manager
Plugin URI: http://pickplugins.com
Description: Awesome Project Manager.
Version: 1.0.0
Text Domain: project-manager
Author: pickplugins
Author URI: http://pickplugins.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


class ProjectManager{
	
	public function __construct(){
	
		$this->pm_define_constants();
		
		$this->pm_declare_classes();
		$this->pm_declare_shortcodes();
		$this->pm_declare_actions();
		//$this->pm_declare_pickform();
		
		$this->pm_loading_script();
		//$this->pm_loading_plugin();
		$this->pm_loading_functions();
		
		register_activation_hook( __FILE__, array( $this, 'pm_activation' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ));
	}
	
	public function pm_activation() {

		//$class_pm_post_types = new class_pm_post_types();
		//$class_pm_post_types->pm_posttype_project();
		//flush_rewrite_rules();




        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table = $wpdb->prefix .'project_manager_notify';

        $sql = "CREATE TABLE IF NOT EXISTS ".$table ." (
			id int(100) NOT NULL AUTO_INCREMENT,
			action_type VARCHAR( 50 )	NOT NULL,			
			action_by int(100) NOT NULL,
			action_to int(100) NOT NULL,
			is_read VARCHAR( 50 )	NOT NULL,
			is_logged VARCHAR( 50 )	NOT NULL,			
			datetime DATETIME NOT NULL,		
			post_id int(100) NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

	

	}
	
	public function load_textdomain() {

		$locale = apply_filters( 'plugin_locale', get_locale(), 'project-manager' );
		load_textdomain('project-manager',WP_LANG_DIR .'/project-manager/project-manager-'. $locale .'.mo');
		load_plugin_textdomain( 'project-manager', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' ); 
	}
	
	
	public function pm_loading_functions() {
		
		require_once( PM_PLUGIN_DIR . 'includes/functions.php');
        require_once( PM_PLUGIN_DIR . 'includes/functions-attendance.php');
		require_once( PM_PLUGIN_DIR . 'includes/update.php');		
	}
	
	public function pm_loading_plugin() {
		
		//add_action( 'activated_plugin', array( $this, 'redirect_welcome' ));
		//require_once( PM_PLUGIN_DIR . 'includes/classes/class-admin-setup-wizard.php');
	}
	
	public function pm_loading_script() {
	
		add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
        //add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
		add_action( 'wp_enqueue_scripts', array( $this, 'pm_front_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'pm_admin_scripts' ) );
	}
	
	public function pm_declare_pickform() {

		//require_once( PM_PLUGIN_DIR . 'includes/pickform/class-pickform.php');
	}
	
	public function pm_declare_actions() {
	
		require_once( PM_PLUGIN_DIR . 'templates/single-project/single-project-hook.php');
        require_once( PM_PLUGIN_DIR . 'templates/single-task/single-task-hook.php');
        require_once( PM_PLUGIN_DIR . 'templates/single-to-do/single-to-do-hook.php');
		//require_once( PM_PLUGIN_DIR . 'includes/actions/action-single-question.php');
		//require_once( PM_PLUGIN_DIR . 'includes/actions/action-answer-section.php');
		//require_once( PM_PLUGIN_DIR . 'includes/actions/action-answer-posting.php');
		//require_once( PM_PLUGIN_DIR . 'includes/actions/action-myaccount.php');
		//require_once( PM_PLUGIN_DIR . 'includes/actions/action-breadcrumb.php');
	}
	
	public function pm_declare_shortcodes() {

        require_once( PM_PLUGIN_DIR . 'includes/shortcodes/class-shortcode-project-archive.php');
        require_once( PM_PLUGIN_DIR . 'includes/shortcodes/class-shortcode-task-archive.php');
        require_once( PM_PLUGIN_DIR . 'includes/shortcodes/class-shortcode-to-do-archive.php');
        require_once( PM_PLUGIN_DIR . 'includes/shortcodes/class-shortcode-attendance.php');
		require_once( PM_PLUGIN_DIR . 'includes/shortcodes/class-shortcode-templates-list.php');

		require_once( PM_PLUGIN_DIR . 'includes/shortcodes/class-shortcode-bookmark.php');



		//require_once( PM_PLUGIN_DIR . 'includes/shortcodes/class-shortcode-question-archive.php');
		//require_once( PM_PLUGIN_DIR . 'includes/shortcodes/class-shortcode-add-question.php');
		//require_once( PM_PLUGIN_DIR . 'includes/shortcodes/class-shortcode-myaccount.php');
		//require_once( PM_PLUGIN_DIR . 'includes/shortcodes/class-shortcode-registration.php');
		//require_once( PM_PLUGIN_DIR . 'includes/shortcodes/class-shortcode-breadcrumb.php');
	}
	
	public function pm_declare_classes() {
		
		require_once( PM_PLUGIN_DIR . 'includes/classes/class-post-types.php');	
		require_once( PM_PLUGIN_DIR . 'includes/classes/class-post-meta-project.php');	
		require_once( PM_PLUGIN_DIR . 'includes/classes/class-post-meta-task.php');			
		require_once( PM_PLUGIN_DIR . 'includes/classes/class-post-meta-todo.php');
        require_once( PM_PLUGIN_DIR . 'includes/classes/class-post-meta-attendance.php');
        require_once( PM_PLUGIN_DIR . 'includes/classes/class-post-meta-salary.php');

		//require_once( PM_PLUGIN_DIR . 'includes/classes/class-post-meta-answer.php');	
		require_once( PM_PLUGIN_DIR . 'includes/classes/class-functions.php');


		//require_once( PM_PLUGIN_DIR . 'includes/classes/class-settings.php');	
		require_once( PM_PLUGIN_DIR . 'includes/classes/class-column-project.php');	
		require_once( PM_PLUGIN_DIR . 'includes/classes/class-column-task.php');	
		require_once( PM_PLUGIN_DIR . 'includes/classes/class-column-todo.php');	
		//require_once( PM_PLUGIN_DIR . 'includes/classes/class-dynamic-css.php');	
	}
	
	public function pm_define_constants() {
		
		$this->define('PM_PLUGIN_URL', plugins_url('/', __FILE__)  );
		$this->define('PM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		$this->define('PM_TEXTDOMAIN', 'project-manager' );
		$this->define('PM_PLUGIN_NAME', __('Project Manager',PM_TEXTDOMAIN) );
	}
	
	private function define( $name, $value ) {
		if( $name && $value )
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}
	
	
	public function redirect_welcome($plugin){
		
		if( get_option( 'pm_complete_setting_wizard', 'no' ) == 'no' ) {
			if( $plugin == 'project-manager/project-manager.php' ) {
				wp_safe_redirect( admin_url( 'index.php?page=qa-setup' ) );
				exit;
			}
		}
	}
		
		
	public function pm_front_scripts(){


		wp_enqueue_script('jquery');
        wp_enqueue_media();

		wp_enqueue_script('pm_front_js', plugins_url( '/assets/front/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
		//wp_enqueue_script('pm_front_scripts-form', plugins_url( '/assets/front/js/scripts-form.js' , __FILE__ ) , array( 'jquery' ));		
		wp_localize_script( 'pm_front_js', 'pm_ajax', array( 'pm_ajaxurl' => admin_url( 'admin-ajax.php')));

		wp_enqueue_script('woa-timer-js', plugins_url( '/assets/front/js/timer.jquery.js' , __FILE__ ) , array( 'jquery' ));

        wp_enqueue_script('pm_meta_scripts_attendence', plugins_url( '/assets/admin/js/meta-scripts-attendence.js' , __FILE__ ) , array( 'jquery' ));
		
		//wp_enqueue_style('pm_style', PM_PLUGIN_URL.'assets/front/css/style.css');	
		
		//global
		//wp_enqueue_style('font-awesome', PM_PLUGIN_URL.'assets/global/css/font-awesome.css');
		wp_enqueue_style('project-archive', PM_PLUGIN_URL.'assets/front/css/project-archive.css');
        wp_enqueue_style('task-archive', PM_PLUGIN_URL.'assets/front/css/task-archive.css');
        wp_enqueue_style('to-do-archive', PM_PLUGIN_URL.'assets/front/css/to-do-archive.css');
        wp_enqueue_style('single-project', PM_PLUGIN_URL.'assets/front/css/single-project.css');
        wp_enqueue_style('single-task', PM_PLUGIN_URL.'assets/front/css/single-task.css');
        wp_enqueue_style('single-to-do', PM_PLUGIN_URL.'assets/front/css/single-to-do.css');
        wp_enqueue_style('attendance', PM_PLUGIN_URL.'assets/front/css/attendance.css');
        wp_enqueue_style('font-awesome', PM_PLUGIN_URL.'assets/global/css/font-awesome.css');
        wp_enqueue_style('pm-notify', PM_PLUGIN_URL.'assets/front/css/pm-notify.css');		
		

		// pickform
		//wp_enqueue_script('pickform', plugins_url( '/assets/global/pickform/scripts.js' , __FILE__ ) , array( 'jquery' ));
		//wp_enqueue_style('pickform', PM_PLUGIN_URL.'assets/front/css/pickform.css');	
		//wp_enqueue_script('jquery.steps', plugins_url( '/assets/front/js/jquery.steps.js' , __FILE__ ) , array( 'jquery' ));
		
		// ParaAdmin
		//wp_enqueue_script('pm_ParaAdmin', plugins_url( '/assets/global/ParaAdmin/ParaAdmin.js' , __FILE__ ) , array( 'jquery' ));		
		//wp_enqueue_style('pm_paraAdmin', PM_PLUGIN_URL.'assets/global/ParaAdmin/ParaAdmin.css');
		
		//wp_enqueue_script('plupload-all');	
		//wp_enqueue_script('plupload_js', plugins_url( '/assets/global/js/scripts-plupload.js' , __FILE__ ) , array( 'jquery' ));
		
		//wp_localize_script( 'pm_front_js', 'pm_ajax', array( 'pm_ajaxurl' => admin_url( 'admin-ajax.php')));
	}

	public function pm_admin_scripts(){
		
		wp_enqueue_script('jquery');
		
		wp_enqueue_script('pm_meta_scripts_projects', plugins_url( '/assets/admin/js/meta-scripts-projects.js' , __FILE__ ) , array( 'jquery' ));
		wp_localize_script( 'pm_meta_scripts_projects', 'pm_ajax', array( 'pm_ajaxurl' => admin_url( 'admin-ajax.php')));
		
		wp_enqueue_script('pm_meta_scripts_tasks', plugins_url( '/assets/admin/js/meta-scripts-tasks.js' , __FILE__ ) , array( 'jquery' ));
		wp_localize_script( 'pm_meta_scripts_tasks', 'pm_ajax', array( 'pm_ajaxurl' => admin_url( 'admin-ajax.php')));
		
		wp_enqueue_script('pm_meta_scripts_todos', plugins_url( '/assets/admin/js/meta-scripts-todos.js' , __FILE__ ) , array( 'jquery' ));
		wp_localize_script( 'pm_meta_scripts_todos', 'pm_ajax', array( 'pm_ajaxurl' => admin_url( 'admin-ajax.php')));

        wp_enqueue_script('pm_meta_scripts_attendence', plugins_url( '/assets/admin/js/meta-scripts-attendence.js' , __FILE__ ) , array( 'jquery' ));
        wp_enqueue_script('pm_meta_scripts_salary', plugins_url( '/assets/admin/js/meta-scripts-salary.js' , __FILE__ ) , array( 'jquery' ));
		
		// wp_enqueue_script('pm_dragable_js',  plugins_url( '/assets/admin/js/jquery-ui.min.js' , __FILE__ ) , array( 'jquery' ));
		
		//select2
		wp_enqueue_style( 'jquery-ui-select2-style' ,  PM_PLUGIN_URL.'assets/global/css/select2.min.css');
		wp_enqueue_script('pm_select2_scripts', plugins_url( '/assets/global/js/select2.min.js' , __FILE__ ) , array( 'jquery' ));
		
		//datepicker
		wp_enqueue_style( 'jquery-ui-datepicker-style' ,  PM_PLUGIN_URL.'assets/admin/css/jquery-ui.css');
		wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_style('pm_admin-metabox-attendance', PM_PLUGIN_URL.'assets/admin/css/admin-metabox-attendance.css');
        wp_enqueue_style('pm_admin-metabox-salary', PM_PLUGIN_URL.'assets/admin/css/admin-metabox-salary.css');


		wp_enqueue_style('pm_admin_style', PM_PLUGIN_URL.'assets/admin/css/style.css');
		//wp_enqueue_style('pm_admin_addons', PM_PLUGIN_URL.'assets/admin/css/addons.css');
		// wp_enqueue_style('pm_admin-metabox', PM_PLUGIN_URL.'assets/admin/css/admin-metabox.css');
		// wp_enqueue_style('pm_admin-metabox-task', PM_PLUGIN_URL.'assets/admin/css/admin-metabox-task.css');
        //
        //
        //
		
		//wp_enqueue_script('pm_ParaAdmin', plugins_url( '/assets/admin/ParaAdmin/js/ParaAdmin.js' , __FILE__ ) , array( 'jquery' ));		
		//wp_enqueue_style('pm_paraAdmin', PM_PLUGIN_URL.'assets/admin/ParaAdmin/css/ParaAdmin.css');
		
		//global
		wp_enqueue_style('font-awesome', PM_PLUGIN_URL.'assets/global/css/font-awesome.css');
		//wp_enqueue_style('pm_global_style', PM_PLUGIN_URL.'assets/global/css/style.css');
		
		//wp_enqueue_style( 'wp-color-picker' );
		//wp_enqueue_script( 'pm_color_picker', plugins_url('/assets/admin/js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	}
	
	
} new ProjectManager();