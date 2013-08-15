<?php
/**
 * Apocrypha - Library of hidden things, and functional framework for the Tamriel Foundry WordPress theme.
 *
 * @author Andrew Clayton
 * @version 1.0.0
 * 8-14-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Apocrypha {
	
	/**
	 * Constructor for the Apocrypha functions library
	 * @version 1.0.0
	 */
	function __construct() {
	
		// Define theme constants
		$this->constants();
	
		// Register theme supports
		$this->supports();
	
		// Includes necessary libraries
		$this->includes();
		
		// Setup globals
		add_action( 'template_redirect', array( $this, 'setup_globals' ) );
	}
	
	/**
	 * This method does most of the interesting work populating the Apocrypha Theme object
	 * @version 1.0.0
	 */	 
	public function setup_globals() {
		
		// Site info
		$this->site 	= SITENAME;
		
		// Current page
		$this->page 	= new Apoc_Context();

		
		// Current template
		global $pagenow;
		$apoc->template = $pagenow;
		
		// Mobile Devices
		$apoc->is_mobile = wp_is_mobile();
		
		// Information on the current user
		$apoc->user = wp_get_current_user();	

	}

	
	/**
	 * Define constant paths for use within theme functions
	 * @version 1.0.0
	 */
	private function constants() {
	
		// Site Information
		define( 'SITENAME' 			, get_bloginfo( 'name' ) );
		define( 'SITEURL' 			, get_home_url() );
		
		// Directories
		define( 'THEME_DIR' 		, get_template_directory() );
		define( 'APOC_DIR' 			, trailingslashit( THEME_DIR ) . 'library/' );	
		define( 'APOC_FUNCTIONS' 	, trailingslashit( APOC_DIR ) . 'functions/' );		
		define( 'APOC_EXTENSIONS' 	, trailingslashit( APOC_DIR ) . 'extensions/' );
		define( 'APOC_ADMIN'		, trailingslashit( APOC_DIR ) . 'admin/' );
		
		// Locations
		define( 'THEME_URI' 		, get_template_directory_uri() );
		define( 'APOC_URI' 			, trailingslashit( THEME_URI ) . 'library/' );		
		define( 'APOC_CSS' 			, trailingslashit( APOC_URI ) . 'css/' );
		define( 'APOC_JS' 			, trailingslashit( APOC_URI ) . 'js/' );
	}
	
	/**
	 * Add WordPress recognized theme supports
	 * @version 1.0.0
	 */
	private function supports() {
	
		// This is an HTML5 theme
		add_theme_support( 'html5' );
			
		// Add support for bbPress.
		add_theme_support( 'bbpress' );

		// Add support for Buddypress.
		add_theme_support( 'buddypress' );
	
		// Adds support for featured images.
		add_theme_support( 'post-thumbnails' );
	}
	
	/**
	 * Load primary function libraries
	 * @version 1.0.0
	 */
	 private function includes() {
	 
		/** Core Functions ******************************************************/
		require( APOC_FUNCTIONS . 'core.php' );
		require( APOC_FUNCTIONS . 'context.php' );
		require( APOC_FUNCTIONS . 'template-hierarchy.php' );
		require( APOC_FUNCTIONS . 'seo.php' );
		require( APOC_FUNCTIONS . 'login.php' );
		require( APOC_FUNCTIONS . 'users.php' );
		require( APOC_FUNCTIONS . 'posts.php' );
		require( APOC_FUNCTIONS . 'comments.php' );
		require( APOC_FUNCTIONS . 'pagination.php' );
	
		/** Extensions *********************************************************/
		require( APOC_EXTENSIONS . 'breadcrumbs.php' );
		require( APOC_EXTENSIONS . 'slides.php' );
		require( APOC_EXTENSIONS . 'widgets.php' );
		//require( APOC_EXTENSIONS . 'events.php' );
		require( APOC_EXTENSIONS . 'get-the-image.php' );
		require( APOC_EXTENSIONS . 'shortcodes.php' );

		/** Integrated Plugins *************************************************/		
		if ( class_exists( 'BuddyPress' ) )
			require( APOC_EXTENSIONS . 'buddypress.php' );
		if ( class_exists( 'bbPress' ) )
			require( APOC_EXTENSIONS . 'bbpress.php' );

		/** Admin-Only Functions ***********************************************/
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require( APOC_ADMIN . 'ajax.php' );		
		elseif ( is_admin() )
			require( APOC_ADMIN . 'admin.php' );
	}
}
?>