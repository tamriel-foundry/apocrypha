<?php
/**
 * Apocrypha Theme Login Functions
 * Andrew Clayton
 * Version 1.0.0
 * 8-2-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
/**
 * Overrides the display of wp-login.php
 * @version 1.0.0
 */
add_action( 'login_enqueue_scripts', 'apoc_login_styles' );
function apoc_login_styles() {
	echo '<link rel="stylesheet" href="' . THEME_URI . '/library/css/login-style.css" type="text/css" media="all" />';
}
add_filter( 'login_headerurl', 'apoc_login_url' );
function apoc_login_url() {
    return SITEURL;
}
add_filter( 'login_headertitle', 'apoc_login_title' );
function apoc_login_title() {
    return 'Tamriel Foundry &bull; An ESO fansite and forum dedicated to discussing mechanics, theorycrafting, and guides for The Elder Scrolls Online.';
}

/** 
 * Display the AJAX login form in the header.
 * @since 0.1
 */
function apoc_header_login() {
	
	// Requires BuddyPress, bail if it's missing
	if ( !class_exists( 'BuddyPress' ) )
		return false;
	
	echo 'true';
}

/** 
 * Get the current page URL for redirection.
 * @since 0.1
 */
function get_current_url() {
	$current_url = esc_attr( $_SERVER['HTTP_HOST'] );
	$current_url .= esc_attr( $_SERVER['REQUEST_URI'] );
	return esc_url( $current_url );
}

/** 
 * Filter the lostpassword URL and send it to my own password recovery page
 * @since 0.1
 */
add_filter( 'lostpassword_url' , 'filter_lostpassword' );
function filter_lostpassword( $lostpassword_url ) {
	$lostpassword_url = trailingslashit( SITEURL ) . '/login?action=password';
	return $lostpassword_url;
	}
	
/** 
 * Sets custom login urls when needed
 * @since 0.1
 */
add_filter('login_url' , 'custom_login_url');
function custom_login_url($login_url) {
    return SITEURL . '/wp-login.php';
}
add_filter( 'lostpassword_url', 'custom_lostpass_url' );
function custom_lostpass_url( $lostpassword_url ) {
    return SITEURL . '/wp-login.php?action=lostpassword';
}

?>