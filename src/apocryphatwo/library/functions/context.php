<?php
/**
 * Apocrypha Theme Context Functions
 * Andrew Clayton
 * Version 1.0
 * 8-1-2013
 */
 
/** 
 * Populates the apocrypha global with some useful context
 * @since 1.0
 */
add_action( 'template_redirect' , 'populate_apocrypha_global' );
function populate_apocrypha_global() {
	global $apocrypha;
	
	// Site name
	$apocrypha->site = SITENAME;
	
	// Page context
	get_page_context();
	
	// Current template
	global $pagenow;
	$apocrypha->template = $pagenow;
	
	// Mobile Devices
	$apocrypha->is_mobile = wp_is_mobile();
	
	// Information on the current user
	$apocrypha->user = wp_get_current_user();
}
	

/**
 * Runs through a series of conditional checks to figure out the page context
 * Stores the results in the framework global
 * @since 1.0
 */
function get_page_context() {
	
	// Get the theme global
	global $apocrypha;
	
	// If the context has already been set, don't repeat the process
	if ( isset ( $apocrypha->context ) )
		return $apocrypha->context;
		
	// Set up some intial variables
	$apocrypha->context = array();
	$object 			= get_queried_object();
	$object_id 			= get_queried_object_id();
	
	// Home page
	if ( is_home() ) {
		$apocrypha->context[] = 'home';
		$apocrypha->context[] = 'archive';
	}
		
	// BuddyPress
	elseif ( function_exists( 'is_bbpress' ) && is_bbpress() )
		$apocrypha->context[] = 'bbpress';
		
	// BuddyPress
	elseif ( function_exists( 'is_buddypress' ) && is_buddypress() )
		$apocrypha->context[] = 'buddypress';
		
	// Singular view
	elseif ( is_singular() ) {
		$apocrypha->context[] = 'singular';
		$apocrypha->context[] = "singular-{$object->post_type}";
		$apocrypha->context[] = "singular-{$object->post_type}-{$object_id}";		
	}
	
	// Archive view
	elseif ( is_archive() ) {
		$apocrypha->context[] = 'archive';
		
		// Taxonomy archive
		if ( is_tax() || is_category() || is_tag() ) {
			$slug = ( ( 'post_format' == $object->taxonomy ) ? str_replace( 'post-format-', '', $object->slug ) : $object->slug );
			
			$apocrypha->context[] = 'taxonomy';
			$apocrypha->context[] = "taxonomy-{$object->taxonomy}";
			$apocrypha->context[] = "taxonomy-{$object->taxonomy}-" . sanitize_html_class( $slug, $object->term_id );
		}
		
		// Author archive
		if ( is_author() ) {
			$author_id = get_query_var( 'author' );
			$apocrypha->context[] = 'author';
			$apocrypha->context[] = 'author-' . sanitize_html_class( get_the_author_meta( 'user_nicename', $author_id ), $author_id );
		}
	
		// Date archive
		if ( is_date() ) {
			$apocrypha->context[] = 'date';

			if ( is_year() )
				$apocrypha->context[] = 'year';

			if ( is_month() )
				$apocrypha->context[] = 'month';

			if ( is_day() )
				$apocrypha->context[] = 'day';
		}
	}
	
	// Search results
	elseif ( is_search() )
		$apocrypha->context[] = 'search';
		
	// Error 404
	elseif ( is_404() )
		$hybrid->context[] = 'error-404';
		
	return array_map( 'esc_attr', apply_filters( 'apocrypha_context', $apocrypha->context ) );
}

/**
 * Returns a set of classes to apply to the main body element
 * @since 1.0
 */
function display_body_class( $class = '' ) {
	
	// Load some info
	$classes = array();
	global $wp_query, $apocrypha;
	
	// Is the current user logged in
	$classes[] = ( $apocrypha->user->data->ID > 0 ) ? 'logged-in' : 'logged-out';
	
	// Bring in the page context
	$classes = array_merge( $classes, get_page_context() );
	
	// Singular post classes
	if ( is_singular() ) {

		// Get the queried post object
		$post = get_queried_object();

		// Checks for custom template
		$template = str_replace( array ( "{$post->post_type}-template-", "{$post->post_type}-" ), '', basename( get_post_meta( get_queried_object_id(), "_wp_{$post->post_type}_template", true ), '.php' ) );
		if ( !empty( $template ) )
			$classes[] = "{$post->post_type}-template-{$template}";
	}
	
	// Paged views
	if ( ( ( $page = $wp_query->get( 'paged' ) ) || ( $page = $wp_query->get( 'page' ) ) ) && $page > 1 )
		$classes[] = 'paged page-' . intval( $page );
	
	// Bring in any user input classes
	if ( !empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	}
	
	// Browser detection
	global $wp_query, $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome;
	$browsers = array( 
		'gecko' 	=> $is_gecko, 
		'opera' 	=> $is_opera, 
		'lynx' 		=> $is_lynx, 
		'ns4' 		=> $is_NS4, 
		'safari' 	=> $is_safari, 
		'chrome' 	=> $is_chrome, 
		'msie' 		=> $is_IE 
	);
	foreach ( $browsers as $key => $value ) {
		if ( $value ) {
			$classes[] = $key;
			break; 
		}
	}
	
	// Apply the filters for WordPress 'body_class'
	$classes = apply_filters( 'body_class', $classes, $class );

	// Construct the classes into a string and return it
	$class = join( ' ', $classes );
	echo $class;
}

/**
 * Returns a set of classes to apply to individual posts
 * @since 1.0
 */
function display_entry_class( $class = '', $post_id = null ) {
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
	else {
		$classes = array( 'hentry', 'error' );
	}
	
	// Apply the filters for WordPress 'post_class'
	$classes = apply_filters( 'post_class', $classes, $class, $post_id );
	
	// Join all the classes into one string and return them
	$class = join( ' ', $classes );
	echo $class;
}

/**
 * Returns a set of classes to apply to post comments
 * @since 1.0
 */
function display_comment_class( $class = '' ) {
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