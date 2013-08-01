<?php
/**
 * Apocrypha - Library of hidden things, and functional framework for the Tamriel Foundry WordPress theme.
 * Andrew Clayton
 * Version 2.0
 * 8-1-2013
 */

class Apocrypha {
	
	/**
	 * Constructor for the Apocrypha functions library
	 * @since 2.0
	 */
	 
	public $var1 = 'test';
	function __construct() {
	
		
	
		/* Define theme constants */
		//add_action( 'after_setup_theme', array( &$this, 'constants' ), 1 );
		
		/* Add theme supports */
		//add_action( 'after_setup_theme', array( &$this, 'supports' ), 2 );	
		
		/* Load framework core functions */
		//add_action( 'after_setup_theme', array( &$this, 'apocrypha_core' ), 3 );	
		
		/* Load framework extensions */
		//add_action( 'after_setup_theme', array( &$this, 'apocrypha_extensions' ), 11 );
		
		/* Load admin functions and files */
		//add_action( 'wp_loaded' , array( &$this, 'apocrypha_admin' ) );
	}
	
	
	/**
	 * Define constant paths for use within theme functions
	 * @since 0.1
	 */
	function constants() {
		
		/* Site URL */
		define( 'SITEURL' , get_home_url() );
		
		/* Theme directory */
		define( 'THEME_DIR' , get_template_directory() );
		
		/* Theme URI */
		define( 'THEME_URI' , get_template_directory_uri() );
		
		/* Theme styles */
		define( 'THEME_CSS' , trailingslashit( THEME_DIR ) . 'css' );
		
		/* Theme scripts */
		define( 'THEME_JS' , trailingslashit( THEME_DIR ) . 'js' );
		
		/* Framework directory */
		define( 'APOC_DIR' , trailingslashit( THEME_DIR ) . 'library' );
		
		/* Framework directory */
		define( 'APOC_URI' , trailingslashit( THEME_URI ) . 'library' );
		
		/* Framework functions */
		define( 'APOC_FUNCTIONS' , trailingslashit( APOC_DIR ) . 'functions' );
		
		/* Framework extensions */
		define( 'APOC_EXTENSIONS' , trailingslashit( APOC_DIR ) . 'extensions' );
		
		/* Admin functions */
		define( 'APOC_ADMIN' , trailingslashit( APOC_DIR ) . 'admin' );
	}
	
	/**
	 * Add theme supports
	 * @since 0.2
	 */
	function supports() {
			
		/* Add support for bbPress. */
		add_theme_support( 'bbpress' );

		/* Add support for Buddypress. */
		add_theme_support( 'buddypress' );
	
		/* Adds support for featured images. */
		add_theme_support( 'post-thumbnails' );
	}
	
	/**
	 * Load primary framework components
	 * @since 0.1
	 */
	 function apocrypha_core() {
	 
		/* Core functions */
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'core.php' );
		
		/* Context functions */
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'context.php' );
		
		/* User functions */
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'users.php' );
		
		/* Post functions */
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'posts.php' );
		
		/* Comment functions */
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'comments.php' );
		
		/* Page title, meta description, SEO stuff */
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'seo.php' );
		
		/* Template hierarchy */
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'template-hierarchy.php' );
		
		/* Shortcodes */
		require_once( trailingslashit( APOC_FUNCTIONS ) . 'shortcodes.php' );
	 }
	
	/**
	 * Load extension components
	 * @since 0.1
	 */
	function apocrypha_extensions() {
	
		/* Content Slider */
		require_once( trailingslashit( APOC_EXTENSIONS ) . 'content-slider.php' );
		
		/* Calendar Events */
		require_once( trailingslashit( APOC_EXTENSIONS ) . 'events.php' );
		
		/* Widgets */
		require_once( trailingslashit( APOC_EXTENSIONS ) . 'widgets.php' );
		
		/* Breadcrumbs */
		require_once( trailingslashit( APOC_EXTENSIONS ) . 'breadcrumb-trail.php' );
		
		/* Justin Tadlock's "Get The Image" */
		require_once( trailingslashit( APOC_EXTENSIONS ) . 'get-the-image.php' );

		/* Justin Tadlock's "Loop Pagination" */
		require_once( trailingslashit( APOC_EXTENSIONS ) . 'loop-pagination.php' );
		
		/* Login Functions */
		require_once( trailingslashit( APOC_EXTENSIONS ) . 'login.php' );
		
		/* BuddyPress Functions */
		if ( function_exists( 'bp_version' ) )
			require_once( trailingslashit( APOC_EXTENSIONS ) . 'buddypress.php' );
			
		/* bbPress Functions */
		if ( function_exists( 'bbp_version' ) )
			require_once( trailingslashit( APOC_EXTENSIONS ) . 'bbpress.php' );
	 }
	 
	 
	/**
	 * Load admin files and functions
	 * @since 0.1
	 */	 
	function apocrypha_admin() {
		/* Only load in the backend */
		if ( is_admin() )
			require_once( trailingslashit( APOC_ADMIN ) . 'admin.php' );
	}
}
?>