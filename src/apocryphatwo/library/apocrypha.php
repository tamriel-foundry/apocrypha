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

	/** Singleton Instance ******************************************************/
	
	/**
	 * A dummy constructor to prevent multiple instantiation.
	 * @version 1.0.0
	 */	
	private function __construct() {}
	private function __clone() {}	
	
	/**
	 * @var Apocrypha The Apocrypha Theme instance
	 */
	private static $instance = NULL;
	
	/**
	 * The one true Apocrypha instance
	 *
	 * This method checks whether the theme class has already been initialized.
	 * This allows us to avoid having to call a theme global everywhere in order to 
	 * access class properties and methods.
	 *
	 * @return The Apocrypha Theme instance
	 * @version 1.0.0
	 */
	public static function instance() {
		if ( !isset( self::$instance ) ) {
		
			// Instantiate the class
			self::$instance = new Apocrypha;
					
			// Define theme constants
			self::$instance->constants();
			
			// Setup globals
			self::$instance->setup_globals();
		
			// Includes necessary libraries
			self::$instance->includes();
			
			// Register theme supports
			self::$instance->supports();
			
			// Setup actions
			self::$instance->actions();
		}
		
		// Return the theme class!
		return self::$instance;
	}
	
	/** Private Methods **********************************************************/
	
	/**
	 * Define constant paths for use within theme functions
	 * @version 1.0.0
	 */
	private function constants() {
		
		// Core constants
		define( 'SITENAME' 			, get_bloginfo( 'name' ) );
		define( 'SITEURL' 			, get_home_url() );
		define( 'THEME_DIR' 		, get_template_directory() );
		define( 'THEME_URI' 		, get_template_directory_uri() );
	}
	
	/**
	 * This method does most of the interesting work populating the Apocrypha Theme object
	 * @version 1.0.0
	 */	 
	private function setup_globals() {
			
		// Basic site info
		$this->site 					= SITENAME;
		$this->version					= '1.0.0';
		
		// User information
		$this->device 					= '';
		$this->user						= '';
		
		// Query information
		$this->seo						= '';
		$this->context 					= '';
		$this->queried_id				= '';
		$this->queried_object			= '';
		$this->paged					= 0;
		$this->post_type				= '';
		$this->comment_count			= NULL;
		
		// Options
		$this->posts_per_page			= 6;
		$this->search_type				= 'posts';
		
		// Directory paths
		$this->lib_dir					= trailingslashit( THEME_DIR ) . 'library/';
		$this->functions_dir			= $this->lib_dir . 'functions/';
		$this->extensions_dir			= $this->lib_dir . 'extensions/';
		$this->templates_dir			= $this->lib_dir . 'templates/';
		$this->admin_dir				= $this->lib_dir . 'admin/';
		
		// Directory URIs
		$this->css_uri					= trailingslashit( THEME_URI ) . 'library/css/';
		$this->js_uri					= trailingslashit( THEME_URI ) . 'library/js/';
		$this->images_uri				= trailingslashit( THEME_URI ) . 'library/images/';
	}
	
	/**
	 * Add WordPress recognized theme supports
	 * @version 1.0.0
	 */
	private function supports() {
	
		// Theme supports
		add_theme_support( 'html5' );
		add_theme_support( 'bbpress' );
		add_theme_support( 'buddypress' );
		add_theme_support( 'post-thumbnails' );
		
		// Theme does not support
		show_admin_bar( false );
	}
	
	/**
	 * Load primary function libraries
	 * @version 1.0.0
	 */
	 private function includes() {
	 
		// Core Functions
		require( $this->functions_dir 	. 'context.php' );
		require( $this->functions_dir 	. 'core.php' );
		require( $this->functions_dir 	. 'template-hierarchy.php' );
		require( $this->functions_dir 	. 'seo.php' );
		require( $this->functions_dir 	. 'login.php' );
		require( $this->functions_dir 	. 'users.php' );
		require( $this->functions_dir 	. 'posts.php' );
		require( $this->functions_dir 	. 'comments.php' );
		require( $this->functions_dir 	. 'pagination.php' );
	
		// Extensions
		require( $this->extensions_dir 	. 'breadcrumbs.php' );
		require( $this->extensions_dir 	. 'slides.php' );
		require( $this->extensions_dir 	. 'widgets.php' );
		require( $this->extensions_dir 	. 'events.php' );
		require( $this->extensions_dir 	. 'get-the-image.php' );
		require( $this->extensions_dir 	. 'shortcodes.php' );

		// Integrated Plugins
		if ( class_exists( 'BuddyPress' ) )
			require( $this->extensions_dir . 'buddypress.php' );
		if ( class_exists( 'bbPress' ) )
			require( $this->extensions_dir . 'bbpress.php' );

		// Admin-Only Functions
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require( $this->admin_dir . 'ajax.php' );		
		elseif ( is_admin() )
			require( $this->admin_dir . 'admin.php' );
	}
	
	/**
	 * Hook core functions to actions
	 * @version 1.0.0
	 */
	private function actions() {
	
		// Populate apocrypha globals
		add_action( 'template_redirect', array( $this , 'populate_globals' ) , 10 );	
	}				
		
	/** Public Methods **********************************************************/		
	
	/**
	 * Populates the globals that were registered earlier after WordPress loads the query
	 * @version 1.0.0
	 */
	function populate_globals() {
	
		// Initialize the Apoc_Context class
		$context 				= new Apoc_Context();
		$this->device			= $context->device;
		$this->user				= $context->user;
		$this->context			= $context->page;
		$this->queried_id		= $context->queried_object_id;
		$this->queried_object	= $context->queried_object;
		$this->paged			= $context->paged;
		$this->post_type		= $context->queried_object->post_type;
		
		// Initialize the Apoc_SEO class
		$this->seo				= new Apoc_SEO();

	}			
}

/**
 * The function responsible for accessing the Apocrypha instance.
 * We can use this function similarly to a global variable, without needing to declare it.
 *
 * @version 1.0.0
 * @return Apocrypha Theme Class
 */
function apocrypha() {
	return Apocrypha::instance();
}
?>