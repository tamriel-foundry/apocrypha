<?php
/**
 * Apocrypha AJAX Functions
 * Andrew Clayton
 * Version 1.0.0
 * 2-13-2014
 */
 
// Imitate AJAX Functionality
define('DOING_AJAX', true);

// Reject non POST actions
if( !isset( $_POST['action'] ) )
	exit;

// Load WordPress
require_once( '../../../../wp-load.php' ); 

//Typical headers
header('Content-Type: text/html');
send_nosniff_header();

//Disable caching
header('Cache-Control: no-cache');
header('Pragma: no-cache');

// Determine the requested action
$action = esc_attr( trim( $_POST['action'] ) );

// Make sure the action is allowed
if ( is_user_logged_in() && has_action( 'apoc_ajax_'.$action ) )
	do_action( 'apoc_ajax_'.$action );
elseif ( has_action( 'apoc_ajax_nopriv_'.$action ) )
	do_action( 'apoc_ajax_nopriv_'.$action );
else
	die( 'AJAX action ' . $action . ' does not exist or is missing in action!' );
exit;