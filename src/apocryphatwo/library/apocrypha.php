<?php
/**
 * Apocrypha - Library of hidden things, and functional framework for the Tamriel Foundry WordPress theme.
 * Andrew Clayton
 * Version 1.0
 * 8-1-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Apocrypha {
	
	/**
	 * Constructor for the Apocrypha functions library
	 * @since 1.0
	 */
	function __construct() {
	
		// Define theme constants
		add_action( 'after_setup_theme'	, array( &$this, 'constants' )	, 10 );
	
		// Add theme supports
		add_action( 'after_setup_theme'	, array( &$this, 'supports' )	, 20 );	
		
		// Load framework core functions
		add_action( 'after_setup_theme'	, array( &$this, 'core' )		, 30 );	
		
		// Load framework extensions
		add_action( 'after_setup_theme' , array( &$this, 'extensions' )	, 40 );
		
		// Load admin functions and files
		add_action( 'wp_loaded' 		, array( &$this, 'admin' ) 		, 10 );
	}
	
	
	/**
	 * Define constant paths for use within theme functions
	 * @since 1.0
	 */
	function constants() {
	
		// Site URL
		define( 'SITENAME' 			, get_bloginfo( 'name' ) );
		
		// Site URL
		define( 'SITEURL' 			, get_home_url() );
		
		// Theme directory
		define( 'THEME_DIR' 		, get_template_directory() );
		
		// Theme URI
		define( 'THEME_URI' 		, get_template_directory_uri() );
		
		// Framework directory
		define( 'APOC_DIR' 			, trailingslashit( THEME_DIR ) . 'library' );
		
		// Framework directory
		define( 'APOC_URI' 			, trailingslashit( THEME_URI ) . 'library' );
		
		// CSS Styles
		define( 'APOC_CSS' 			, trailingslashit( APOC_URI ) . 'css' );
		
		// Javascript
		define( 'APOC_JS' 			, trailingslashit( APOC_URI ) . 'js' );
		
		// Framework functions
		define( 'APOC_FUNCTIONS' 	, trailingslashit( APOC_DIR ) . 'functions' );
		
		// Framework extensions
		define( 'APOC_EXTENSIONS' 	, trailingslashit( APOC_DIR ) . 'extensions' );
		
		// Admin functions
		define( 'APOC_ADMIN'		, trailingslashit( APOC_DIR ) . 'admin' );
	}
	
	/**
	 * Add WordPress recognized theme supports
	 * @since 0.2
	 */
	function supports() {
			
		// Add support for bbPress.
		add_theme_support( 'bbpress' );

		// Add support for Buddypress.
		add_theme_support( 'buddypress' );
	
		// Adds support for featured images.
		add_theme_support( 'post-thumbnails' );
	}
	
	/**
	 * Load primary function libraries
	 * @since 1.0
	 */
	 function core() {
	 
		// Core functions
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'core.php' );
		
		// Context functions
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'context.php' );
		
		// Template hierarchy
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'template-hierarchy.php' );
		
		// Page title and meta description
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'seo.php' );
		
		// Login functions
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'login.php' );
		
		// User functions
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'users.php' );
		
		// Post functions
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'posts.php' );
		
		// Comment functions
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'comments.php' );
	
		// Loop Pagination
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'loop-pagination.php' );
	 }
	
	/**
	 * Load theme extensions
	 * @since 1.0
	 */
	function extensions() {
	
		// Breadcrumb Trail
		require_once( trailingslashit( APOC_EXTENSIONS ) . 'breadcrumbs.php' );
		
		
		
	
		// Content Slider
		require_once( trailingslashit( APOC_EXTENSIONS ) . 'slides.php' );
		
		// Widgets
		require_once( trailingslashit( APOC_EXTENSIONS ) . 'widgets.php' );
		
		// Calendar Events
		//require_once( trailingslashit( APOC_EXTENSIONS ) . 'events.php' );
		
		// Justin Tadlock's Get The Image
		require_once( trailingslashit( APOC_EXTENSIONS ) . 'get-the-image.php' );
		
		// Shortcodes
		require_once( trailingslashit( APOC_EXTENSIONS ) . 'shortcodes.php' );
		
		// BuddyPress Functions
		if ( function_exists( 'bp_version' ) )
			require_once( trailingslashit( APOC_EXTENSIONS ) . 'buddypress.php' );
			
		// bbPress Functions
		if ( function_exists( 'bbp_version' ) )
			require_once( trailingslashit( APOC_EXTENSIONS ) . 'bbpress.php' );
	 }
	 
	 
	/**
	 * Load admin functions if we are in the wp-admin backend
	 * @since 0.1
	 */	 
	function admin() {

		// Backend administrative functions
		if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) )
			require_once( trailingslashit( APOC_ADMIN ) . 'admin.php' );
		
		// AJAX functions and hooks
		elseif ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once( trailingslashit( APOC_ADMIN ) . 'ajax.php' );

	}
}
?>