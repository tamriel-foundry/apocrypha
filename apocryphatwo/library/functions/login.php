<?php
/**
 * Apocrypha Theme Login Functions
 * Andrew Clayton
 * Version 1.0
 * 8-2-2013
 */
 
/**
 * Overrides elements of wp-login.php
 * @since 1.0
 */
add_action( 'login_enqueue_scripts', 'apoc_login_styles' );
function apoc_login_styles() {
	echo '<link rel="stylesheet" href="' . APOC_CSS . '/login-style.css" type="text/css" media="all" />';
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
	if ( !function_exists( 'bp_version' ) )
		return false;
	
	echo '<div id="admin-bar-login">';
	
	global $apocrypha;
	$user 		= $apocrypha->user->data;
	$user_id	= $user->ID;
	
	// If it's a recognized user
	if ( 0 < $user_id ) :

		// Grab some information
		$name 		= $user->display_name;
		$avatar		= apoc_fetch_avatar( $user_id , 'thumb' , 25 );
		$link		= bp_core_get_user_domain( $user_id );

		// Construct a logout link
		$redirect 	= wp_logout_url( get_current_url() ); ?>	
		
		<a href="<?php echo $link; ?>" title="Visit your user profile"><?php echo $avatar; ?></a>
		<span class="logged-in-welcome">Welcome back, <?php echo $name; ?></span>
		<a id="top-login-logout" class="admin-bar-login-link button" href="<?php echo $redirect; ?>" title="Log out of this account.">Logout</a>
		
	<?php // Otherwise we need to display the login form
	else :?>
		
	<form name="top-login-form" id="top-login-form" action="<?php echo SITEURL . '/wp-login.php'; ?>" method="post">
		<fieldset class="login-form">
	
			<input type="text" name="username" id="username" class="input" value="" placeholder="Username" size="20" tabindex="1">
			
			<input type="password" name="password" id="password" class="input" value="" placeholder="Password" size="20" tabindex="1">
			
			<input type="checkbox" name="remember" id="remember" value="forever" tabindex="1">
			<label id="remember-label" for="remember">Save</label>
			
			<input type="hidden" name="redirect" value="<?php echo get_current_url(); ?>">
			<input type="hidden" name="action" value="toplogin">
			
			<input type="submit" name="login-submit" id="login-submit" class="admin-bar-login-link" value="Log In" tabindex="1">
			
			<?php if ( get_option( 'users_can_register' ) ) : ?>
				<a class="admin-bar-login-link button" href="<?php echo trailingslashit(SITEURL) . BP_REGISTER_SLUG; ?>" title="Register a new user account!">Register</a>
			<?php endif; ?>
			
			<a class="admin-bar-login-link button" href="<?php echo wp_lostpassword_url(); ?>" title="Lost your password?">Lost Password</a>
			
			<?php wp_nonce_field( 'ajax-login-nonce', 'top-login' ); ?>
		
		</fieldset>
	</form>		
		
	<?php endif;
	echo '</div>';
	
	if ($user_id == '') echo '<div id="top-login-error" class="error"></div>';
}

/** 
 * Get the current page URL for redirection.
 * @since 0.1
 */
function get_current_url() {
	$current_url .= esc_attr( $_SERVER['HTTP_HOST'] );
	$current_url .= esc_attr( $_SERVER['REQUEST_URI'] );
	return esc_url( $current_url );
}
	
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