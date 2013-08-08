<?php
/**
 * Apocrypha Theme AJAX Functions
 * Andrew Clayton
 * Version 1.0
 * 8-6-2013

----------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
1.0 - Login
2.0 - Notifications
3.0 - Comments
--------------------------------------------------------------*/
 
/*---------------------------------------------
1.0 - LOGIN
----------------------------------------------*/
/** 
 * Register and process the login AJAX action.
 * @since 0.1
 */
add_action( 'wp_ajax_nopriv_toplogin', 'top_login_ajax' );
function top_login_ajax() {
	
	// First validate the nonce
	check_ajax_referer( 'ajax-login-nonce' , 'top-login' );
	
	// Next, get user credentials from the login form
	$creds = array();
	$creds['user_login'] 	= $_POST['username'];
	$creds['user_password'] = $_POST['password'];
	$creds['remember']		= isset( $_POST['rememberme'] ) ? $_POST['remember'] : false;
	$redirect_url 			= $_REQUEST['redirect'];
	
	// Before proceeding, trim and replace any spaces in usernames with hyphens for BuddyPress
	$creds['user_login'] 	= str_replace( ' ' , "-" , trim( $creds['user_login'] ) );
	
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
 * @since 1.0
 */
add_action( 'wp_ajax_apoc_clear_notification' , 'apoc_clear_notification' );
function apoc_clear_notification() {
	
	// Get the current user
	global $apocrypha, $bp, $wpdb;
	$user_id = $apocrypha->user->data->ID;
	$notification_id = $_REQUEST['notid'];
	
	// Check the nonce
	check_ajax_referer( 'clear-single-notification' );
	
	// Delete the notification
	$wpdb->query( $wpdb->prepare( "DELETE FROM " . $bp->core->table_name_notifications . " WHERE user_id = %d AND id = %s", $user_id , $notification_id ) );
	echo "1";
	exit(0);
}

 
/*---------------------------------------------
3.0 - COMMENTS
----------------------------------------------*/

/**
 * Delete article comments with AJAX
 * @since 1.0
 */
add_action( 'wp_ajax_apoc_delete_comment' , 'apoc_delete_comment' );
function apoc_delete_comment() {
	
	/* Check the nonce */
	check_ajax_referer( 'delete-comment-nonce' , '_wpnonce' );	

	/* Get the data */
	$comment_id	= $_POST['commentid'];
	
	/* Delete it */
	wp_delete_comment( $comment_id );
	
	echo "1";
	exit(0);
}


?>