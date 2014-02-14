<?php
/**
 * Apocrypha Theme AJAX Functions
 * Andrew Clayton
 * Version 1.0.2
 * 1-22-2013

----------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
1.0 - Login
2.0 - Notifications
3.0 - Posts
4.0 - Comments

X.X - Infractions
X.X - Contact Form

X.X - Private Messages
--------------------------------------------------------------*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Debugging
add_action( 'apoc_ajax_apoc_debug' , 'apoc_debug' );
add_action( 'wp_ajax_apoc_debug' , 'apoc_debug' );
function apoc_debug() {
	$memory =  round ( memory_get_peak_usage() / 1048576 , 2 ) . 'megabytes used.';
	die( $memory );
}
 
/*---------------------------------------------
1.0 - LOGIN
----------------------------------------------*/
/** 
 * Register and process the login AJAX action.
 * @since 1.0.1
 */
add_action( 'wp_ajax_nopriv_toplogin', 'top_login_ajax' );
function top_login_ajax() {
	
	// First validate the nonce
	check_ajax_referer( 'ajax-login-nonce' , 'top-login' );

	// Next, get user credentials from the login form
	$creds = array();
	$creds['user_login'] 	= $_POST['username'];
	$creds['user_password'] = $_POST['password'];
	$creds['remember']		= isset( $_POST['rememberme'] ) ? $_POST['rememberme'] : false;
	$redirect_url 			= $_REQUEST['redirect'];
	
	// Process the signon!
	$login = wp_signon( $creds , false );	

	// Check results
	$result = array();
	if ( !is_wp_error($login) ) {
		$result['success'] 	= 1;
		$result['redirect'] = $redirect_url;
	} else {
		$result['success'] = 0;
		if ( $login->errors )	
			$result['error'] = $login->get_error_message();
		else 
			$result['error'] = "<strong>ERROR</strong>: Please enter a valid username and password to login.";
	}
	
	// Send the JSON
	echo json_encode( $result );
	die();	
}

/*---------------------------------------------
2.0 - NOTIFICATIONS
----------------------------------------------*/
/**
 * Remove frontend BuddyPress notifications with AJAX
 * @version 1.0.0
 */
add_action( 'wp_ajax_apoc_clear_notification' , 'apoc_clear_notification' );
function apoc_clear_notification() {

	// Check the nonce
	check_ajax_referer( 'clear-single-notification' , '_wpnonce' );
	
	// Get some data
	global $bp, $wpdb;
	$user_id = get_current_user_id();
	$notification_id = $_POST['notid'];
	
	// Delete the notification
	$wpdb->query( $wpdb->prepare( "DELETE FROM " . $bp->core->table_name_notifications . " WHERE user_id = %d AND id = %s", $user_id , $notification_id ) );
	
	// Send a response
	echo "1";
	die();
}

/**
 * Mark all notifications as read
 * @version 1.0.2
 */
add_action( 'wp_ajax_apoc_mark_notifications_read' , 'apoc_mark_notifications_read' );
function apoc_mark_notifications_read() {

	// Get the user data
	$user_id = $_POST['id'];	
	
	// Mark all notifications as read
	BP_Notifications_Notification::mark_all_for_user( $user_id );

	echo "1";
	die();
}

/**
 * Delete all notifications for user
 * @version 1.0.2
 */
add_action( 'wp_ajax_apoc_delete_all_notifications' , 'apoc_delete_all_notifications' );
function apoc_delete_all_notifications() {

	// Get the user data
	$user_id = $_POST['id'];	
	
	// Delete all notifications
	BP_Notifications_Notification::delete( array( 'user_id' => $user_id ) );

	echo "1";
	die();
}

/*---------------------------------------------
3.0 - POSTS
----------------------------------------------*/
add_action( 'apoc_ajax_nopriv_apoc_load_posts' 	, 'apoc_load_posts' );
add_action( 'apoc_ajax_nopriv_apoc_load_posts' 	, 'apoc_load_posts' );
add_action( 'wp_ajax_apoc_load_posts' 			, 'apoc_load_posts' );
add_action( 'wp_ajax_apoc_load_posts' 			, 'apoc_load_posts' );
function apoc_load_posts() {

	// Get the post data
	$type 	= $_POST['type'];
	$id		= isset( $_POST['id'] ) ? $_POST['id'] : NULL;
	$paged	= $_POST['paged'];
	$url	= $_POST['baseurl'];
	
	// Setup post query variables
	$args = array();
	$args['paged'] 	= $paged;
	
	// Don't include Entropy Rising
	$args['cat'] 	= '-'.get_cat_ID( 'entropy rising' ) . ',-' . get_cat_ID( 'guild news' );
	
	// Add additional query variables depending on context
	switch ( $type ) {
		case 'author' :
			$args['author'] = $id;
			break;
		
		case 'category' :
			$args['cat']	= $id;
			break;

		case 'erhome' :
			$public 		= get_cat_ID( 'entropy rising' );
			$private 		= get_cat_ID( 'guild news' );
			$args['cat']	= is_user_guild_member() ? $public . ',' . $private : $public;
			break;
	}
		
	// Issue the posts query
	global $ajax_query;
	$ajax_query = new WP_Query( $args );
	ob_start();
	
	// Check if we found anything
	if ( $ajax_query->have_posts() ) :

		// If we have posts, build the HTML for the set
		while ( $ajax_query->have_posts() ) :
			$ajax_query->the_post();
			apoc_display_post();
		endwhile;
		
		// Next we need to build some new pagination
		echo '<nav class="pagination ajaxed" data-type="' . $type . '" data-id="' . $id .'">';
			apoc_pagination( $args = array() , $url , 'ajax_query' );
		echo '</nav>';
				
	endif;
	
	// Get everything from the output buffer
	$content = ob_get_contents();
	ob_end_clean();
	
	// Send a response and return the HTML
	die( $content );
}
 
/*---------------------------------------------
4.0 - COMMENTS
----------------------------------------------*/
add_action( 'wp_ajax_nopriv_apoc_load_comments' , 'apoc_load_comments' );
add_action( 'wp_ajax_apoc_load_comments' 		, 'apoc_load_comments' );
function apoc_load_comments() {

	// Get the post data
	$postid	= $_POST['postid'];
	$paged	= $_POST['paged'];
	$url	= $_POST['baseurl'];
	

	// Setup post query variables
	$args = apoc_comments_args();
	$args['page'] 	= $paged;
	
	// Get the comments for the relevant post
	$comments = get_comments(array(
		'post_id' 	=> $postid,
		'status' 	=> 'approve',
		'order'		=> 'ASC',
	));
	
	// Get the comment count and max pages
	$count 		= get_comments_number( $postid );
	$max_pages	= ceil( $count / 10 );
	
	// Display the comments into the buffer
	ob_start();
	wp_list_comments( $args , $comments );

	// Next we need to build some new pagination
	echo '<nav class="pagination ajaxed" data-postid="' . $postid . '">';
		apoc_pagination( $args = array( 'context' => 'comment' , 'current' => $paged , 'total' => $max_pages ) , $url );
	echo '</nav>';
	
	// Get everything from the output buffer
	$content = ob_get_contents();
	ob_end_clean();
	
	// Send a response and return the HTML
	die( $content );
}

/**
 * Delete article comments with AJAX
 * @version 1.0.0
 */
add_action( 'wp_ajax_apoc_delete_comment' , 'apoc_delete_comment' );
function apoc_delete_comment() {
	
	// Check the nonce
	check_ajax_referer( 'delete-comment-nonce' , '_wpnonce' );	

	// Get the data
	$comment_id	= $_POST['commentid'];
	
	// Delete it
	wp_delete_comment( $comment_id );
	
	echo "1";
	die();
}

/*---------------------------------------------
5.0 - BBPRESS
----------------------------------------------*/

/**
 * Get new replies using AJAX
 * @version 1.0.0
 */
add_action( 'wp_ajax_nopriv_apoc_load_replies' 	, 'apoc_load_replies' );
add_action( 'wp_ajax_apoc_load_replies' 		, 'apoc_load_replies' );
function apoc_load_replies() {

	// Get the post data
	$type 		= $_POST['type'];
	$topic_id	= $_POST['id'];
	$paged		= $_POST['paged'];
	$url		= $_POST['baseurl'];
	
	// Setup post query variables
	$reply_args = array(
		'post_type'			=> array( 'topic' , 'reply' ),
		'post_parent'    	=> $topic_id,
		'posts_per_page' 	=> bbp_get_replies_per_page(),
		'paged' 			=> $paged,
		'order'				=> 'ASC',
		's'					=> false,
	);
		
	// Allow bbPress to detect if this request is a ?view=all
	add_filter( 'bbp_get_view_all' , 'apoc_spoof_view_get' );
	function apoc_spoof_view_get( $retval ) {
		$view_all 	= ( strpos( $_POST['baseurl'] , 'view=all' ) > 0 ) 	? true : false;
		$retval 	= ( $view_all && current_user_can( 'moderate' ) ) 	? true : false;
		return $retval;
	}
	
	// Customize the pagination arguments depending on our AJAX parameters
	add_filter( 'bbp_replies_pagination' , 'apoc_ajax_reply_pagination' );
	function apoc_ajax_reply_pagination( $args ) {
	
		// Clean the URL
		global $wp_rewrite;
		$base 		= $wp_rewrite->pagination_base;
		$remove 	= '/\/' . $base . '\/(.*)/';
		$baseurl 	= preg_replace( $remove , "" , $_POST['baseurl'] );
		$format		= $base . '/%#%/';

		// Define the args
		$pagination_args = array(
			'base'      => trailingslashit( $baseurl ) . $format,
			'format'    => '',
			'total'     => bbpress()->reply_query->max_num_pages,
			'current'   => $_POST['paged'],
			'prev_text' => '&larr;',
			'next_text' => '&rarr;',
			'mid_size'  => 1
		);
		return $pagination_args;
	}

	// Get the replies
	bbp_has_replies( $reply_args );
	
	// Bluff AJAX into recognizing this as a single topic
	bbpress()->reply_query->is_single = true;
	set_query_var( '_bbp_query_name' , 'bbp_single_topic' );					
	
	// Start an output buffer and capture the formatted replies
	ob_start();
	while ( bbp_replies() ) : bbp_the_reply();
		include( THEME_DIR . '/bbpress/loop-single-reply.php');
	endwhile;
	
	// Refresh the pagination ?>
	<nav class="pagination forum-pagination ajaxed" data-type="<?php echo $type; ?>" data-id="<?php echo $topic_id; ?>">
		<div class="pagination-count">
			<?php bbp_topic_pagination_count(); ?>
		</div>
		<div class="pagination-links">
			<?php bbp_topic_pagination_links(); ?>
		</div>
	</nav>		

	<?php // Get everything from the output buffer
	$content = ob_get_contents();
	ob_end_clean();
	
	// Send a response and return the HTML
	die( $content );
}

/**
 * Submit bbPress Replies with AJAX
 * @version 1.0.0
 */
add_action( 'wp_ajax_apoc_bbp_reply' , 'apoc_bbp_reply' );
function apoc_bbp_reply() {

	// Intercept the bbp_new_reply_handler just before redirection
	add_action( 'bbp_new_reply_post_extras' , 'apoc_bbp_reply_content' , 1 , 1 );
	
	// Return the formatted reply
	function apoc_bbp_reply_content( $reply_id ) {
	
		// Get the reply, which is now in the database
		$bbp = bbpress();
		$bbp->reply_query = new WP_Query( array(
			'p'			=> $reply_id, 
			'post_type' => 'reply',
			));
		
		// Start an output buffer to capture the formatted reply
		ob_start();
		while(  $bbp->reply_query->have_posts() ) : $bbp->reply_query->the_post();
			include( THEME_DIR . '/bbpress/loop-single-reply.php');
		endwhile;
		
		// Retrieve everything from the output buffer
		$content = ob_get_contents();
		ob_end_clean();	
		
		// Send the response back to jQuery
		die( $content );
	}

	// Process the new reply
	bbp_new_reply_handler( 'bbp-new-reply' );
}


/**
 * Get new topics from AJAX
 * @version 1.0.0
 */
add_action( 'wp_ajax_nopriv_apoc_load_topics' 	, 'apoc_load_topics' );
add_action( 'wp_ajax_apoc_load_topics' 			, 'apoc_load_topics' );
function apoc_load_topics() {

	// Get the post data
	$type 		= $_POST['type'];
	$forum_id	= $_POST['id'];
	$paged		= $_POST['paged'];
	$url		= $_POST['baseurl'];
	
	// Setup post query variables
	$topic_args = array(
		'post_type'			=> bbp_get_topic_post_type(),
		'post_parent'		=> ( 0 < $forum_id ) ? $forum_id : 'any',
		'meta_key'       	=> '_bbp_last_active_time', 
		'orderby'       	=> 'meta_value',
		'order'				=> 'DESC',
		'posts_per_page'	=> bbp_get_topics_per_page(),
		'paged' 			=> $paged,
		's'					=> false,
		'show_stickies'		=> ( 1 == $paged ) ? true : false,
		'max_num_pages'		=> false,
	);
	
	// If it's the recent topics page, stick to just topics within the past month
	if ( $forum_id == 0 ) {
		$topic_args['meta_value'] 	= date( 'Y-m-d' , strtotime( '-30 days' ));
		$topic_args['meta_compare']	= '>=';
	}
	
	// If it's a specific forum, let bbPress know
	if ( $forum_id > 0 ) {
		add_filter( 'bbp_is_single_forum', 'apoc_spoof_single_forum' );
		function apoc_spoof_single_forum( $retval ) {
			$retval = true;
			return $retval;
			}
	}
	
	// Allow bbPress to detect if this request is a ?view=all
	add_filter( 'bbp_get_view_all' , 'apoc_spoof_view_get' );
	function apoc_spoof_view_get( $retval ) {
		$view_all 	= ( strpos( $_POST['baseurl'] , 'view=all' ) > 0 ) 	? true : false;
		$retval 	= ( $view_all && current_user_can( 'moderate' ) ) 	? true : false;
		return $retval;
	}
	
	// Make sure what is returned has the 'topic' class (for some reason it is omitted)
	add_filter( 'bbp_get_topic_class' , 'apoc_force_topic_class' );
	function apoc_force_topic_class( $classes ) {
		array_unshift( $classes, "topic" );
		return array_unique( $classes );
	}
	
	// Customize the pagination arguments depending on our AJAX parameters
	add_filter( 'bbp_topic_pagination' , 'apoc_ajax_topic_pagination' );
	function apoc_ajax_topic_pagination( $args ) {
	
		// Clean the URL
		global $wp_rewrite;
		$base 		= $wp_rewrite->pagination_base;
		$remove 	= '/\/' . $base . '\/(.*)/';
		$baseurl 	= preg_replace( $remove , "" , $_POST['baseurl'] );
		$format		= $base . '/%#%/';
		
		// Get the query
		$bbp = bbpress();

		// Define the args
		$pagination_args = array(
			'base'      => trailingslashit( $baseurl ) . $format,
			'format'    => '',
			'total'     => $bbp->topic_query->max_num_pages,
			'current'   => $_POST['paged'],
			'prev_text' => '&larr;',
			'next_text' => '&rarr;',
			'mid_size'  => 1
		);
		return $pagination_args;
	}
		
	// Get the topics
	bbp_has_topics( $topic_args );
	
	// Start an output buffer and capture the formatted topics
	ob_start();	
	while ( bbp_topics() ) : bbp_the_topic();
		include( THEME_DIR . '/bbpress/loop-single-topic.php');
	endwhile;
	
	// Refresh the pagination ?>
	<nav class="pagination forum-pagination ajaxed" data-type="<?php echo $type; ?>" data-id="<?php echo $forum_id; ?>">
		<div class="pagination-count">
			<?php bbp_forum_pagination_count(); ?>
		</div>
		<div class="pagination-links">
			<?php bbp_forum_pagination_links(); ?>
		</div>
	</nav>	
	
	<?php // Retrieve everything from the output buffer
	$content = ob_get_contents();
	ob_end_clean();	
	
	// Send the response back to jQuery
	die( $content );
}



/**
 * Delete replies using AJAX!
 * @version 1.0.0
 */
add_action( 'wp_ajax_apoc_delete_reply' , 'apoc_delete_reply' );
function apoc_delete_reply() {

	// Get the data
	$reply_id 	= $_POST['reply_id'];
	$context	= $_POST['context'];
	
	// Make sure it's a trash action
	if ( $context == "bbp_toggle_reply_trash" ) {
	
		// Trash it
		wp_trash_post($reply_id);
		echo "1";
	}

	// Exit
	exit(0);
}



/**
 * Process post reports using AJAX
 * @version 1.0.0
 */
add_action( 'wp_ajax_apoc_report_post' , 'apoc_report_post' );
function apoc_report_post() {
	
	/* Get the needed data */
	$userid = get_current_user_id();
	$from	= bp_core_get_user_displayname( $userid );
	
	/* Get AJAX data */
	$type	= $_POST['type'];
	$postid = $_POST['id'];
	$number	= $_POST['num'];
	$user 	= $_POST['user'];
	$reason	= $_POST['reason'];
	
	/* Get the post URL */
	if( 'reply' == $type ) :
		$link = bbp_get_reply_url( $postid );
	elseif ( 'comment' == $type ) :
		$link = get_comment_link( $postid );
	elseif( 'message' == $type ) :
		$link 	= bp_core_get_user_domain( $user ) . 'messages/view/' . trailingslashit( $postid );
		$user	= bp_core_get_user_displayname( $user );
	endif;
	
	/* Set the email headers */
	$subject 	= "Reported Post From $from";
	$headers 	= "From: Post Report Bot <noreply@tamrielfoundry.com>\r\n";
	$headers	.= "Content-Type: text/html; charset=UTF-8";
	
	/* Construct the message */
	$body = '<h3>' . $from . ' has reported a post violating the Code of Conduct.</h3>';
	$body .= '<ul><li>Report URL: <a href="' . $link . '" title="View Post" target="_blank">' . $link . '</a></li>';
	$body .= '<li>Post Number: ' . $number . '</li>';
	$body .= '<li>User Reported: ' . $user . '</li>';
	$body .= '<li>Reason: ' . $reason . '</li></ul>';
	
	/* Send the email */
	$emailto = get_moderator_emails();
	wp_mail( $emailto , $subject , $body , $headers );
	
	echo "1";
	exit(0);
}

/*--------------------------------------------------------------
X.X - INFRACTIONS
--------------------------------------------------------------*/

/** 
 * Clear an Infraction using AJAX
 * @since 0.5
 */
add_action( 'wp_ajax_apoc_clear_infraction' , 'apoc_clear_infraction' );
function apoc_clear_infraction() {

	// Get the data
	$userid = bp_displayed_user_id();
	$id 	= $_REQUEST['id'];
	
	// Check the nonce
	check_ajax_referer( 'clear-single-infraction' );
	
	// Flush any cached stuff	
	if ( function_exists( 'w3tc_pgcache_flush' ) ) w3tc_pgcache_flush();
	if ( function_exists( 'w3tc_objectcache_flush' ) ) w3tc_objectcache_flush();
	if ( function_exists( 'apc_clear_cache' ) ) apc_clear_cache('user');
	
	// Delete the infraction
	$warnings = maybe_unserialize( get_user_meta( $userid , 'infraction_history' , true ) );
	unset( $warnings[$id] );
	$warnings = array_values( $warnings );
	
	// Resave the stuff
	if ( count( $warnings ) > 0 )
		update_user_meta( $userid , 'infraction_history' , $warnings );
	else 
		delete_user_meta( $userid , 'infraction_history' );
	
	// Return success
	echo "1";
	exit(0);
}

/** 
 * Clear a moderator note using AJAX handler
 * @since 0.5
 */
add_action( 'wp_ajax_apoc_clear_mod_note' , 'apoc_clear_mod_note' );
function apoc_clear_mod_note() {
	
	// Get the data
	$userid = bp_displayed_user_id();
	$id 	= $_REQUEST['id'];
	
	// Check the nonce
	check_ajax_referer( 'clear-moderator-note' );
	
	// Delete the note
	$notes = maybe_unserialize( get_user_meta( $userid , 'moderator_notes' , true ) );
	unset( $notes[$id] );
	$notes = array_values( $notes );
	
	// Resave the stuff
	if ( count( $notes ) > 0 )
		update_user_meta( $userid , 'moderator_notes' , $notes );
	else 
		delete_user_meta( $userid , 'moderator_notes' );
	
	// Return success
	echo "1";
	exit(0);
}



/*---------------------------------------------
X.X - CONTACT FORM
----------------------------------------------*/
add_action( 'wp_ajax_nopriv_apoc_contact_form' 	, 'apoc_contact_form' );
add_action( 'wp_ajax_apoc_contact_form' 		, 'apoc_contact_form' );
function apoc_contact_form () {

	// Get the data
	$name 		= trim( $_POST['name'] );
	$email 		= trim( $_POST['email'] );
	$comments 	= stripslashes( trim( $_POST['comments'] ) );
	$copy		= $_POST['copy'];
	
	// Configure headers
	$emailto	= 'admin@tamrielfoundry.com';
	$subject 	= 'Contact Form Submission from ' . $name;
	$body 		= "Name: $name \n\nEmail: $email \n\nComments: $comments";
	$headers[] 	= "From: $name <$email>\r\n";
	$headers[] 	= "Content-Type: text/html; charset=UTF-8";	
	
	// Send mail
	wp_mail($emailto, $subject, $body, $headers);
	if( true == $copy ) {
		$subject 	= 'Tamriel Foundry Contact Form Submission';
		$headers[0] = 'From: Tamriel Foundry <admin@tamrielfoundry.com>';
		wp_mail($email, $subject, $body, $headers);
	}
	
	exit(1);
}


/*---------------------------------------------
X.X - PRIVATE MESSAGES
----------------------------------------------*/

/**
 * Send a private message reply to a thread via a POST request.
 * Overrides the default AJAX function provided by BuddyPress
 */
add_action( 'wp_ajax_apoc_private_message_reply' , 'apoc_private_message_reply' );
function apoc_private_message_reply() {
	
	// Bail if not a POST action
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;

	// Check the nonce and register the new message
	check_ajax_referer( 'messages_send_message' );
	$result = messages_new_message( array( 'thread_id' => (int) $_REQUEST['thread_id'], 'content' => $_REQUEST['content'] ) );

	// If the new message was registered successfully
	if ( $result ) :
	$user = new Apoc_User( get_current_user_id() , 'reply' ); ?>
	<li class="reply new-message">
		<header class="reply-header">
			<time class="reply-time">Right Now</time>
			<?php apoc_report_post_button( 'message' ); ?>
		</header>		
				
		<div class="reply-body">	
			<div class="reply-author user-block">
				<?php echo $user->block; ?>
			</div>
			<div class="reply-content">
				<?php echo wpautop( stripslashes( $_REQUEST['content'] ) ); ?>
			</div>
			<?php $user->signature(); ?>
		</div>	
	</li>
	
	<?php // Otherwise, process errors
	else :
		echo "<div id='message' class='error'><p>" . __( 'There was a problem sending that reply. Please try again.', 'buddypress' ) . '</p></div>';
	endif;
	exit;
}
?>