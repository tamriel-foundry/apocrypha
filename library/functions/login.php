<?php
/**
 * Apocrypha Theme Login Functions
 * Andrew Clayton
 * Version 1.0.1
 * 2-13-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*---------------------------------------------
1.0 - APOC_SECURITY CLASS
----------------------------------------------*/

/**
 * Configures login, authentication, and security features
 *
 * @author Andrew Clayton
 * @version 1.0.1
 */
class Apoc_Security {
	
 	/**
	 * Construct the class
	 * @version 1.0.0
	 */	
	function __construct() {
	
		// WP-Login Modifications
		if( isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] == 'wp-login.php' ) :
			add_action( 'login_enqueue_scripts'	, array( $this , 'login_styles' ) );
			add_filter( 'login_headerurl'		, array( $this , 'login_redirect' ) );
			add_filter( 'login_headertitle'		, array( $this , 'login_title' ) );
		endif;
		
		// Actions
		add_action( 'auth_cookie_valid' 		, array( $this , 'screen_cookie' ) , 10 , 2 );
		
		// Filters
		add_filter('login_url'					, array( $this , 'login_url') );
		add_filter( 'lostpassword_url'			, array( $this , 'lost_password_url' ) );
		add_filter( 'sanitize_user' 			, array( $this , 'sanitize_user' ) );
		add_filter( 'wp_authenticate_user' 		, array( $this , 'authenticate' ) , 10 );	
	}
	
	/**
	 * Prevent usernames from containing spaces
	 * since 1.0.1
	 */
	function sanitize_user( $username ) {
		return str_replace( " " , "-" , $username );
	}
	
	/** 
	 * Prevent "Banned" users from logging in
	 * @since 1.0
	 */
	function authenticate( $object ) {

		// If there hasn't already been an error, check to make sure the user is not banned
		if ( !is_wp_error( $object ) && $object->has_cap( 'banned' ) )
			return new WP_Error( 'forbidden' , "Your user account has been banned from Tamriel Foundry." );
		else
			return $object;
	}
	
	/** 
	 * Modifies various aspects of the wp-login.php page
	 * @since 1.0
	 */
	function login_styles() {
		echo '<link rel="stylesheet" href="' . THEME_URI . '/library/css/login-style.css?ver=1.1.0" type="text/css" media="all" />';
	}
	function login_redirect() {
		return SITEURL;
	}
	function login_title() {
		return 'Tamriel Foundry &bull; An ESO fansite and forum dedicated to discussing mechanics, theorycrafting, and guides for The Elder Scrolls Online.';
	}
	
	/** 
	 * Sets custom login urls when needed
	 * @since 1.0
	 */
	function login_url($login_url) {
		return SITEURL . '/wp-login.php';
	}
	function lost_password_url( $lostpassword_url ) {
		return SITEURL . '/wp-login.php?action=lostpassword';
	}
	
	
	/** 
	 * Prevent "Banned" users automatically authenticating with a cookie
	 * @since 1.0
	 */
	function screen_cookie( $cookie_elements , $user ) {
		
		// If the user is "Banned" delete their authentication cookie
		if( $user->has_cap( 'banned' ) )
			wp_clear_auth_cookie();
	}
}
$security = new Apoc_Security();
?>