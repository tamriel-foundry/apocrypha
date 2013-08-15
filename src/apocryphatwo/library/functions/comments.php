<?php
/**
 * Apocrypha Comments Functions
 * Andrew Clayton
 * Version 1.0.0
 * 8-3-2013

----------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
1.0 - Comments Template
2.0 - Comment Editing
3.0 - Comment Submission AJAX
--------------------------------------------------------------*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
/*---------------------------------------------
1.0 - COMMENTS TEMPLATE
----------------------------------------------*/
/**
 * Generate a number sensitive link to article comments
 * @version 1.0.0
 */
function apoc_comments_link() {
	$comments_link = '';
	$number = doubleval( get_comments_number() );
	$comments_link = '<a class="comments-link button" href="' . get_comments_link() . '" title="Article Comments"><i class="icon-comments"></i>';
	if( $number == 0 ) :
		$comments_link .= 'Leave a Comment';
	elseif ( $number > 0 ) :
		$comments_link .= 'Comments <span class="comments-link-count activity-count">' . $number . '</span>';
	endif;
	$comments_link .= '</a>';
	if( $comments_link ) echo $comments_link;
}

/**
 * Set up arguments for wp_list_comments() used in the comments template
 * @version 1.0.0
 */
function apoc_comments_args() {

	$args = array(
		'style'        		=> 'ol',
		'type'        		=> 'all',
		'per_page'			=> 10,
		'reverse_top_level'	=> false,
		'avatar_size'  		=> 100,
		'callback'     		=> 'apoc_comments_template',
		'end-callback' 		=> 'apoc_comments_end_callback'
	);

	return $args;
}

/**
 * Callback function for choosing the comment template
 * @version 1.0.0
 */
function apoc_comments_template( $comment , $args , $depth ) {
	
	// Determine the post type for this comment
	$apoc = apocrypha();
	$post_type 		= isset( $apoc->post_type ) ? $apoc->post_type : 'post';
	$comment_type 	= get_comment_type( $comment->comment_ID );
	
	// Count comments
	if ( isset ( $apoc->comment_count ) )
		$count = ++$apoc->comment_count;
		
	else {
		if ( '' === $args['per_page'] )
			$args['per_page'] = get_option('comments_per_page');
		if ( '' == $args['page'] )
			$args['page'] = get_query_var('cpage');
		
		$adj = ( $args['page'] - 1 ) * $args['per_page'];
		$count = $adj + 1;
		
		// Update the object
		$apoc->comment_count = $count;
	}
	
	// Load the comment template
	include( THEME_DIR . '/library/templates/comment.php' );
}

/**
 * Close the callback loop
 * @version 1.0.0
 */
function apoc_comments_end_callback() {
	return;
}

/**
 * Output the comment admin links
 * @version 1.0.0
 */
function apoc_comment_admin_links() {
	
	// Make sure it's a logged-in user
	if ( !is_user_logged_in() ) return false;
	
	// If so, go ahead
	global $comment;
	$links = apoc_quote_button( 'comment' );
	$links .= '<a class="reply-link button button-dark" href="#respond" title="Quick Reply"><i class="icon-reply"></i>Reply</a>';
	$links 	.= apoc_comment_edit_button();
	$links	.= apoc_comment_delete_button();
	echo $links;
}

/**
 * Quote button for comments
 * @version 1.0.0
 */
function apoc_quote_button( $context = 'comment' ) {

	// Get information by context
	switch( $context ) {
		
		// Article comments
		case 'comment' :
			global $comment;
			$id  			= $comment->comment_ID;
			$author_name 	= $comment->comment_author;
			$post_date 		= get_comment_date( 'F j, Y' , $comment_id );
			break;
		
		// Forum replies
		case 'reply' :
			$id  			= bbp_get_reply_id( $reply_id );
			$author_name 	= bbp_get_reply_author( $reply_id );
			$post_date 		= get_the_date();	
			break;
	}

    /* Create quote link using data attributes to pass parameters */
	$quoteButton = '<a class="quote-link button button-dark" href="#respond" title="Click here to quote selected text" ';
	$quoteButton .= 'data-context="' . $context . '" data-id="'.$id.'" data-author="'.$author_name.'" data-date="'.$post_date.'">';
	$quoteButton .= '<i class="icon-comment"></i>Quote</a>';
    
	return $quoteButton;
}

/**
 * Edit button for comments
 * @since 0.3
 */
function apoc_comment_edit_button() {
	
	if ( user_can_edit_comment() ) {
	
		global $comment;

		/* Build the link */
		$parent_url = get_permalink( $comment->comment_post_ID );
		$edit_url 	= $parent_url . 'comment-' . $comment->comment_ID . '/edit/';

		/* Create quote link using data attributes to pass parameters */
		$edit_button = '<a class="edit-comment-link button button-dark" href="' . $edit_url . '" title="Edit this comment" ><i class="icon-edit"></i>Edit</a>';
		
		return $edit_button;
	}
}

/**
 * Delete button for comments
 * @since 0.3
 */
function apoc_comment_delete_button() {

	if ( current_user_can( 'moderate' ) || current_user_can( 'moderate_comments' ) ) {
	
		global $comment;

		/* Build the link */
		$delete_button = '<a class="delete-comment-link button button-dark" title="Delete this comment"  data-id="' . $comment->comment_ID . '" data-nonce="' . wp_create_nonce( 'delete-comment-nonce' ) . '"><i class="icon-trash"></i>Trash</a>';
		
		return $delete_button;
	}
}

/*---------------------------------------------
	2.0 - COMMENT EDITING
----------------------------------------------*/

/**
 * Frontend Article Comment Editing Class
 * @version 1.0.0
 */
class Frontend_Comment_Edit {

	// Construct the class
	function __construct() {
		add_action( 'init', array( &$this, 'generate_rewrite_rules' ) ); 
		add_action( 'init', array( &$this, 'add_rewrite_tags' ) ); 
		add_action( 'template_redirect', array( &$this , 'comment_edit_template' ) );
	}

	// Define the rule for parsing new query variables
	function add_rewrite_tags() {
		add_rewrite_tag( '%comment%' , '([0-9]{1,})' ); // Comment Number
	}
	
	// Define the rule for identifying comment edits
	function generate_rewrite_rules() {
	
		$rule	= '[0-9]{4}/[0-9]{2}/([^/]+)/comment-([0-9]{1,})/edit/?$';
		$query	= 'index.php?name=$matches[1]&comment=$matches[2]&edit=1';
		add_rewrite_rule( $rule , $query , 'top' );
	}
	
	// Redirect the template to use comment edit
	function comment_edit_template() {
		
		global $wp_query;
		
		if ( isset( $wp_query->query_vars['comment'] ) && $wp_query->query_vars['edit'] == 1 ) {
		
			// Get the comment
			$comment_id = $wp_query->query_vars['comment'];
			global $comment;
			$comment = get_comment( $comment_id  );
			
			if ( user_can_edit_comment() ) 
				include ( THEME_DIR . '/library/templates/comment-edit.php' );
			else
				include ( THEME_DIR . '/404.php' );
			exit();
		}
	}
}
$edit = new Frontend_Comment_Edit();

/**
 * Determines if the current user can edit a comment;
 * @version 1.0.0
 */
function user_can_edit_comment() {

	/* Check to see who can edit */
	global $comment;
	$user_id = get_current_user_id();
	$author_id = $comment->user_id;

	/* Comment authors and moderators are allowed */
	if ( $user_id == $author_id || current_user_can( 'moderate_comments' ) || current_user_can( 'moderate' ) ) 
		return true;
}

/**
 * Context function for detecting whether we are editing an article comment
 * @version 1.0.0
 */
function is_comment_edit() {
	global $wp_query;
	if ( isset( $wp_query->query_vars['comment'] ) && isset( $wp_query->query_vars['edit'] ) )
		return true;
	else return false;
}

/**
 * Header description for the comment edit page
 * @version 1.0.0
 */
function comment_edit_header_description() {
	global $comment;
	$author_id = $comment->user_id;
	
	$description = 'By ' . bp_core_get_userlink( $author_id );
	$description .= ' on <time datetime="' . date( 'Y-m-d\TH:i' , strtotime( $comment->comment_date ) ) . '">' . date( 'l, F j' , strtotime( $comment->comment_date ) ) . '</time>';
	
	echo $description;	
}


/*---------------------------------------------
	3.0 - COMMENT SUBMISSION AJAX
----------------------------------------------*/
/**
 * Handles comment submission with AJAX
 * @version 1.0.0
 */
add_action(	'comment_post' , 'apoc_ajax_comment' , 20 , 2 );
function apoc_ajax_comment( $comment_ID , $comment_status ) {
	
	// Make sure the comment was submitted via AJAX before proceeding
	if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
	
		// Format the comment and return it's HTML
		$content  	= apoc_display_comment( $comment_ID , $count );
	
		// Kill the script, returning the comment HTML
		die( $content );	
	}
}

/**
 * Gets comment HTML from the comment template to insert with AJAX
 * @version 1.0.0
 */
function apoc_display_comment( $comment_ID , $count ) {

	// Get the current comment
	global $comment , $post , $apoc;
	
	// If the ID which was passed belongs to a different comment, get that one instead
	$comment = get_comment( $comment_ID );
		
	// Tell it which comment number to use
	$apoc->comment_count = $post->comment_count + 1;
		
	// Get the comment HTML
	include( THEME_DIR . '/library/templates/comment.php' );
}


?>