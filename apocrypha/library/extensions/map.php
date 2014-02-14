<?php
/**
 * Apocrypha Interactive Map Functions
 * Andrew Clayton
 * Version 1.0.0
 * 2-4-2013
*/

/*---------------------------------------------
1.0 - MAP CLASS
----------------------------------------------*/
 class Apoc_Map {
 
	// Declare variables
	public $version = 0.01;

	// Construct the class
	function __construct() {
		add_action( 'init', array( &$this, 'generate_rewrite_rules' ) );  
		add_action( 'template_redirect', array( &$this , 'map_template' ) );
	}
	
	// Define the rule for identifying comment edits
	function generate_rewrite_rules() {
	
		$rule	= '^map/?';
		$query	= 'index.php?name=esomap';
		add_rewrite_rule( $rule , $query , 'top' );
	}
	
	// Redirect the template to use the map template
	function map_template() {
		
		global $wp_query;
		if ( $wp_query->query_vars['name'] == 'esomap' ) {
			
			// Check for eligibility
			$can_view = false;
			$roles = apocrypha()->user->roles;
			if ( in_array( $roles[0] , array( 'administrator' , 'editor' , 'author' , 'guildinitiate' , 'guildmember' ) ) )
				$can_view = true;
			
			// Grab the template
			if ( $can_view ) :
				$this->setup_map();
				include ( THEME_DIR . '/library/templates/map.php' );
			else :
				include ( THEME_DIR . '/404.php' );
			endif;
			exit();
		}
	}
	
	/**
	 * Customize map view properties
	 */
	function setup_map() {
		
		// Set wp_query parameters
		global $wp_query;
		$wp_query->is_404 = false;
		$wp_query->is_map = true;
		
		// Set theme objects
		apocrypha()->context = array( 'singular' , 'map' );
		apocrypha()->seo->title = "Interactive Map of Tamriel";
		apocrypha()->seo->description = "A richly interactive map of the entirety of Tamriel which is available in The Elder Scrolls Online.";
		
		// Add custom scripts and styles
		add_action( 'wp_enqueue_scripts' , array( $this , 'enqueue_scripts' ) );
	}
	
	/**
	 * Add interactive map scripts and styles
	 */	
	function enqueue_scripts() {
		
		// Register
		wp_register_script( 'mapsapi' , 'http://maps.googleapis.com/maps/api/js?key=AIzaSyCOp6ztSZl-ZbiVkzq_5MabejnPgWbht8A&sensor=false' , 'jquery' , false , true );
		wp_register_script( 'esomap' , THEME_URI . '/library/js/map-control.js' , 'mapsapi' , $ver=$this->version , true	);
		wp_register_style( 'mapstyle' , THEME_URI . '/library/css/map-style.css' , 'primary' , $ver=$this->version, false );
		
		// Enqueue
		wp_enqueue_script( 'esomap' );
		wp_enqueue_script( 'mapsapi' );
		wp_enqueue_style( 'mapstyle' );
	}
	
}
$map = new Apoc_Map();


function is_interactive_map() {
	global $wp_query;
	return $wp_query->is_map;
}