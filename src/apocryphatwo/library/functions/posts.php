<?php
/**
 * Apocrypha Posts Functions
 * Andrew Clayton
 * Version 1.0.0
 * 8-3-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*---------------------------------------------
1.0 - APOC_POSTS CLASS
----------------------------------------------*/

/**
 * Registers support for custom post types.
 * Registers postmeta fields.
 * Filters default WordPress post behaviors
 *
 * @author Andrew Clayton
 * @version 1.0.0
 */
class Apoc_Posts {
 
 	/**
	 * Construct the class
	 * @version 1.0.0
	 */
	function __construct() {
		
		// Regster post actions
		add_action( 'init', array( $this , 'post_supports'	) );
		add_action( 'init', array( $this , 'post_meta'		) );
		
		// Register post filters
		add_filter( 'excerpt_more'		, array( $this , 'remove_excerpt_more' 	)		);
		add_filter( 'get_the_excerpt'	, array( $this , 'add_excerpt_more'		)		);
		add_filter( 'excerpt_length'	, array( $this , 'custom_excerpt_length') , 999 );		
	}
	
	/**
	 * Add additional supports for default post types.
	 * @version 1.0.0
	 */
	function post_supports() {

		// Page custom excerpts
		add_post_type_support( 'page', array( 'excerpt' ) );
	}
	
	/**
	 * Registers custom metadata callbacks for post types
	 * @version 1.0.0
	 */
	function post_meta() {
	
		// Register the 'Description' meta for posts 
		register_meta( 'post' , 'Description' 		, array( $this , 'sanitize_meta' ) );
		
		// Register custom templates for single posts
		register_meta( 'post' , "_wp_post_template" , array( $this , 'santize_meta' ) );
	}

	/**
	 * Callback function that sanitizes meta data passed through custom post fields
	 * @since 0.1
	 */
	function sanitize_meta( $meta_value , $meta_key , $meta_type ) {
		return strip_tags( $meta_value );
	}
	
	/**
	 * Add a continue reading link to custom excerpts
	 * First remove the default [...] text, then add a link
	 * Next, make default excerpts a bit shorter for the home page
	 * @since 0.1
	 */
	function remove_excerpt_more( $more ) {
		return '';
	}
	function add_excerpt_more( $excerpt ) {
		$more = '<a class="excerpt-more" href="'. get_permalink() . '" title="Continue Reading">[...]</a>';
		return $excerpt . ' ' . $more;
	}
	function custom_excerpt_length( $length ) {
		$length = is_home() ? 45 : $length;
		return $length;
	}
}


/*---------------------------------------------
2.0 - STANDALONE FUNCTIONS
----------------------------------------------*/

/**
 * Generates a class for the homepage headers.
 * Randomizes between the six artistic header images.
 * Ensures that each of the headers are only displayed once
 *
 * @version 1.0.0
 */
function home_header_class() {
	
	$apoc = apocrypha();
	if( !isset( $apoc->home_headers ) ) {
		$headers = range( 1 , 6 );
		shuffle( $headers );
		$apoc->home_headers = $headers;
	}

	$header = array_shift( $apoc->home_headers );
	echo 'home-header-' . $header;
}

/**
 * Generates a class for a single post
 * @version 1.0.0
 */
function post_header_class() {
	$header = rand( 1 , 6 );
	echo 'post-header-' . $header;
}

/**
 * Generates a class for a single post
 * @version 1.0.0
 */
function page_header_class() {
	$header = rand( 1 , 6 );
	echo 'page-header-' . $header;
}

/**
 * Display the post title, either as a link or plain text
 * @version 1.0.0
 */
function entry_header_title( $link = true ) {

	$title = get_the_title();
	if ( true == $link )
		$title = '<a href="' . get_permalink() . '" title="Read Post, ' . get_the_title() . '">' . get_the_title() . '</a>';
	echo $title;
	}
 
/**
 * Describes a post within the loop
 * @version 1.0.0
 */
function entry_header_description() {
	global $post;
	$post_ID = $post->ID;
	$author_ID = $post->post_author;
	$type = $post->post_type;
	$description = '';
	
	// Posts 
	if ( $type == 'post' ) :
		
		// Get some info 
		$author = '<a class="post-author" href="' . get_author_posts_url( $author_ID ) . '" title="All posts by ' . get_the_author_meta( 'display_name' ) . '">' . get_the_author_meta( 'display_name' ) . '</a>';
		$published = '<time class="post-date" datetime="'. get_the_time( 'Y-m-d' ) . '">' . get_the_time( 'F j, Y' ) . '</time>';
		if ( current_user_can( 'edit_post' , $post_ID ) )
			$edit_link = '<a class="post-edit-link" href="' . get_edit_post_link( $post_ID ) . '" title="Edit this post" target="_blank">Edit</a>';
		
		// Show a bunch of stuff for single views
		if ( is_single() ) :
			$avatar		= new Apoc_Avatar( array( 'user_id' => $author_ID , 'type' => 'thumb' , 'size' => 50 ) );
			$category = get_the_term_list( $post_ID, 'category', ' in ' , ', ', '' );
			$description = $avatar->avatar . 'By ' . $author . ' on ' . $published . $category . $edit_link;			
		
		// Otherwise keep it simple
		else : 
			$description = 'By ' . $author . ' on ' . $published . $edit_link;

		endif;
			
	// Pages 
	elseif ( $type == 'page' ) :
		$description = get_post_meta( $post_ID , 'Description' , true );
		if ( current_user_can( 'edit_post' , $post_ID ) )
			$description = $description . '<a class="post-edit-link" href="' . get_edit_post_link( $post_ID ) . '" title="Edit this post" target="_blank">Edit</a>';
	
	endif;
	
	// Echo the post description 
	echo $description;
}


/**
 * Generate post report buttons
 * @version 1.0.0
 */
function apoc_report_post_button( $type ) {
	
	// Only let members report stuff
	if ( !is_user_logged_in() ) return false;
	
	// Get the data by context
	switch( $type ) {
		case 'reply' :
			$post_id		= bbp_get_reply_id();
			$reported_user	= bbp_get_reply_author();
			$post_number 	= bbp_get_reply_position();
			break;
		
		case 'comment' :
			global $comment, $comment_count;
			$post_id		= $comment->comment_ID;
			$reported_user	= $comment->comment_author;
			$post_number 	= $comment_count['count'];
			break;
	}
	
	// Echo the button
	$button = '<a class="report-post" title="Report This Post" data-id="' . $post_id . '" data-number="' . $post_number . '" data-user="' . $reported_user . '" data-type="' . $type . '"><i class="icon-warning-sign"></i></a>';
	echo $button;
}

?>