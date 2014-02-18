<?php
/**
 * Apocrypha Theme Top Admin Bar
 * Andrew Clayton
 * Version 1.0.1
 * 2-13-2014
 */

// Get information about the current user
$user 		= apocrypha()->user;
$user_id	= $user->ID;
if ( $user_id > 0 ) {
	$name 			= $user->display_name;
	$avatar			= new Apoc_Avatar( array( 'user_id' => $user_id , 'type' => 'thumb' , 'size' => 25 ) );
	$link			= trailingslashit( SITEURL ) . trailingslashit( BP_MEMBERS_SLUG ) . $user->data->user_nicename;
	$redirect 		= wp_logout_url( get_current_url() );
	$notifications 	= apoc_user_notifications( $user_id );
}
?>

<?php // The user is not currently logged in, show the login form
if ( 0 == $user_id ) :  ?>
<div id="admin-bar-login" class="logged-out">
	<form name="top-login-form" id="top-login-form" action="<?php echo SITEURL . '/wp-login.php'; ?>" method="post">
			
			<input type="text" name="log" id="username" class="input" value="" placeholder="Username" size="20" tabindex="1">
			<input type="password" name="pwd" id="password" class="input" value="" placeholder="Password" size="20" tabindex="1">
			
			<div id="login-remember" class="checkbox">
				<input type="checkbox" name="rememberme" id="rememberme" value="forever" tabindex="1">
				<label id="remember-label" for="rememberme">Save</label>
			</div>		
			
			<input type="hidden" name="redirect_to" value="<?php echo get_current_url(); ?>">
			<button type="submit" name="wp-submit" id="login-submit" class="admin-bar-login-link" tabindex="1"><i class="icon-lock"></i>Log In</button>
			
			<a class="admin-bar-login-link button" href="<?php echo trailingslashit(SITEURL) . BP_REGISTER_SLUG; ?>" title="Register a new user account!"><i class="icon-user"></i>Register</a>
			<a class="admin-bar-login-link button" href="<?php echo wp_lostpassword_url(); ?>" title="Lost your password?"><i class="icon-question"></i>Lost Password</a>		
	</form>
</div><!-- #admin-bar-login -->
<div id="top-login-error" class="error"></div>

<?php // Otherwise, it is a logged-in user
else : ?>
<div id="admin-bar-login" class="logged-in">	
	<a href="<?php echo $link; ?>" title="Visit your user profile"><?php echo $avatar->avatar; ?></a>
	<span id="logged-in-welcome">Welcome back, <?php echo $name; ?></span>
	<a id="top-login-logout" class="admin-bar-login-link button logout" href="<?php echo $redirect; ?>" title="Log out of this account."><i class="icon-lock"></i>Logout</a>
</div><!-- #admin-bar-login -->
<?php endif; ?>


<?php // Everyone gets a notifications panel ?>
<div id="notifications-panel">
	<div id="header-search" class="notification-type" >
		<div id="search-dropdown" class="admin-bar-dropdown">
			<?php apoc_get_search_form( 'posts' ); ?>
		</div>
	</div>		
	
	<?php // Display notifications for logged in users
	if ( $user_id > 0 ) locate_template( array( 'library/templates/admin-notifications.php' ), true ); ?>

</div><!-- #notifications-panel -->