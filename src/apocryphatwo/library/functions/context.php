<?php
/**
 * Apocrypha Theme Context Functions
 * Andrew Clayton
 * Version 1.0.0
 * 8-15-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*---------------------------------------------
1.0 - APOC_CONTEXT CLASS
----------------------------------------------*/

/**
 * Apoc_Context class gives the context of the current page that is being requested.
 * It runs on the 'template_redirect' hook.
 *
 * @author Andrew Clayton
 * @version 1.0.0
 */
class Apoc_Context {

	function __construct() {
	
		// Get the user agent
		$this->device 				= $this->get_user_agent();
		
		// Get the currently logged-in user
		$this->user					= &wp_get_current_user();
		
		// Get the current object
		$this->queried_object		= get_queried_object();
		$this->queried_object_id	= $this->queried_object->ID;
	
		// Get the page context
		$this->page					= $this->get_page_context();
		$this->paged				= ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		// Get the search term		
		if( is_search() )
			$this->search_query		= get_search_query();
	}
	
	/**
	 * Populates the globals that were registered earlier after WordPress loads the query
	 * @version 1.0.0
	 */
	function get_user_agent() {
		
		// Is it a mobile device?
		$agent = new stdClass();
		$agent->mobile = wp_is_mobile();
		
		// What browser is being used?
		global $is_chrome , $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome ,$is_lynx;
		$browsers = array( 
			'chrome' 	=> $is_chrome, 
			'gecko' 	=> $is_gecko, 
			'msie' 		=> $is_IE,
			'safari' 	=> $is_safari, 
			'opera' 	=> $is_opera, 
			'lynx' 		=> $is_lynx, 
			'ns4' 		=> $is_NS4, 
		);
		foreach ( $browsers as $key => $value ) {
			if ( $value ) {
				$agent->browser = $key;
				break; 
			}
		}
		
		// Return the user agent
		return $agent;
	}
	
	
	/**
	 * Runs through a series of conditional checks to figure out the page context
	 * Stores the results in the framework global
	 * @version 1.0.0
	 */
	public function get_page_context() {
		
		// Set up some intial variables
		$context 	= array();
		$object 	= $this->queried_object;
		$object_id 	= $this->queried_object_id;
		
		// Home page
		if ( is_home() ) {
			$context[] = 'home';
			$context[] = 'archive';
		}
		
		// BuddyPress
		elseif ( class_exists( 'BuddyPress' ) && is_buddypress() )
			$context[] = 'community';
			
		// bbPress
		elseif ( class_exists( 'bbPress' ) && is_bbpress() )
			$context[] = 'forums';
			
		// Singular view
		elseif ( is_singular() ) {
			$context[] = 'singular';
			$context[] = "singular-{$object->post_type}";
			$context[] = "singular-{$object->post_type}-{$object_id}";		
			
			// Checks for custom template
			$template = get_post_meta( $object_id , "_wp_{$post->post_type}_template", true );
			if ( '' != $template ) {
				$template = str_replace( array ( "{$post->post_type}-template-", "{$post->post_type}-" ), '', basename( $template , '.php' ) );
				$context[] = "{$post->post_type}-template";
			}	
			
			// Entropy Rising Homepage
			if ( is_page('entropy-rising') ) {
				$context[] = 'entropy-rising';
				$context[] = 'archive';
			}
		}
		
		// Archive view
		elseif ( is_archive() ) {
			$context[] = 'archive';
			
			// Taxonomy archive
			if ( is_tax() || is_category() || is_tag() ) {
				$slug = ( ( 'post_format' == $object->taxonomy ) ? str_replace( 'post-format-', '', $object->slug ) : $object->slug );
				
				$context[] = 'taxonomy';
				$context[] = "taxonomy-{$object->taxonomy}";
				$context[] = "taxonomy-{$object->taxonomy}-" . sanitize_html_class( $slug, $object->term_id );
			}
			
			// Author archive
			if ( is_author() ) {
				$author_id = get_query_var( 'author' );
				$context[] = 'author';
				$context[] = 'author-' . sanitize_html_class( get_the_author_meta( 'user_nicename', $author_id ), $author_id );
			}
		
			// Date archive
			if ( is_date() ) {
				$context[] = 'date';

				if ( is_year() )
					$context[] = 'year';

				if ( is_month() )
					$context[] = 'month';

				if ( is_day() )
					$context[] = 'day';
			}
		}
		
		// Search results
		elseif ( is_search() )
			$context[] 	= 'search';
			
		// Error 404
		elseif ( is_404() )
			$context[] 	= 'error-404';
			
		return array_map( 'esc_attr', $context );
	}
		
}	// end Apoc_Context class


/*---------------------------------------------
2.0 - STANDALONE FUNCTIONS
----------------------------------------------*/

/**
 * Displays the page body class stored in the theme object
 * Allows this to be hijacked by plugins using the 'body_class' filter
 *
 * @version 1.0.0
 */
function apoc_body_class( $classes = array() ) {

	// Bring in page context
	$apoc 		= apocrypha();
	$classes 	= array_merge( $classes, $apoc->context );
		
	// Bring in user agent
	$classes[] 	=  $apoc->device->browser;
	
	// Is user logged in?
	$classes[]	= ( 0 < $apoc->user->ID ) ? 'logged-in' : 'logged-out';
	
	// Apply the filters for WordPress 'body_class'
	$classes = apply_filters( 'body_class' , $classes );

	// Construct the classes into a string and return it
	$class = join( ' ' , $classes );
	echo $class;
}
	
/**
 * Returns a set of classes to apply to individual posts
 * @version 1.0.0
 */
function apoc_entry_class( $class = '', $post_id = null ) {
	
	// Some starter variables
	static $post_alt;
	$post = get_post( $post_id );
	
	// Make sure we have something to work with
	if ( !empty( $post ) ) {
		$post_id = $post->ID;
	
		// Start with some basic classes for every post
		$classes = array( 'hentry' , $post->post_type , $post->post_status );
		
		// Evens and odds
		$classes[] = 'post-' . ++$post_alt;
		$classes[] = ( $post_alt % 2 ) ? 'odd' : 'even alt';
		
		// Post author
		$classes[] = 'author-' . sanitize_html_class( get_the_author_meta( 'user_nicename' ), get_the_author_meta( 'ID' ) );
		
		// Homepage
		if ( is_home() && is_sticky() && !is_paged() )
			$classes[] = 'sticky';
			
		// Password-protected posts
		if ( post_password_required() )
			$classes[] = 'protected';
			
		// Has more link
		if ( !is_singular() && false !== strpos( $post->post_content, '<!--more-->' ) )
			$classes[] = 'has-more-link';
			
		// Add post category tag
		if ( 'post' == $post->post_type ) {
			foreach ( array( 'category', 'post_tag' ) as $tax ) {
				foreach ( (array)get_the_terms( $post->ID, $tax ) as $term ) {
					if ( !empty( $term->slug ) )
						$classes[] = $tax . '-' . sanitize_html_class( $term->slug, $term->term_id );
				}
			}
		}
	} 
	
	// Otherwise, it's not a post
	else $classes = array( 'error' );
	
	// Apply the filters for WordPress 'post_class'
	$classes = apply_filters( 'post_class', $classes, $class, $post_id );
	
	// Join all the classes into one string and return them
	$class = join( ' ', $classes );
	echo $class;
}

/**
 * Returns a set of classes to apply to post comments
 * @version 1.0.0
 */
function apoc_comment_class( $class = '' ) {
	
	// Setup the comment
	global $comment;
	$classes = array();
	
	// Add 'reply' to use same CSS as forums
	$classes[] = 'reply';

	// Bring in default WP comment classes
	$classes = array_merge( $classes , get_comment_class( $class ) );
	
	// Get the comment type
	$comment_type = get_comment_type();

	// Guest comments
	if ( $comment->user_id == 0 ) 
		$classes[] = 'guest';
	
	// Comment by the original post author
	if ( $post = get_post( get_the_ID() ) ) {
		if ( $comment->user_id === $post->post_author )
			$classes[] = 'post-author';
	}
	
	// Make sure comment classes doesn't have any duplicates
	$classes = array_unique( $classes );
	
	// Join all the classes into one string and return them
	$class = join( ' ', $classes );
	echo $class;
}
?>