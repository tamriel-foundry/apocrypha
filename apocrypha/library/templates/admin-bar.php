<?php
/**
 * Apocrypha Theme Top Admin Bar
 * Andrew Clayton
 * Version 1.0.1
 * 2-13-2014
 */
 
// Show the search form to everybody ?>
<div id="header-search" class="notification-type" >
	<div id="search-dropdown" class="admin-bar-dropdown">
		<?php apoc_get_search_form( 'posts' ); ?>
	</div>
</div>

<?php // Get information about the current user
$user 		= apocrypha()->user->data;
$user_id	= isset( $user ) ? $user->ID : 0;

// The user is not currently logged in, show the login form
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

<?php // Otherwise, it is a known user, so let's get more information
else : 
	$name 			= $user->display_name;
	$avatar			= new Apoc_Avatar( array( 'user_id' => $user_id , 'type' => 'thumb' , 'size' => 25 ) );
	$link			= trailingslashit( SITEURL ) . trailingslashit( BP_MEMBERS_SLUG ) . $user->user_nicename;
	$redirect 		= wp_logout_url( get_current_url() );
	$notifications 	= apoc_user_notifications( $user_id );
?>

<div id="admin-bar-login" class="logged-in">	
	<a href="<?php echo $link; ?>" title="Visit your user profile"><?php echo $avatar->avatar; ?></a>
	<span id="logged-in-welcome">Welcome back, <?php echo $name; ?></span>
	<a id="top-login-logout" class="admin-bar-login-link button logout" href="<?php echo $redirect; ?>" title="Log out of this account."><i class="icon-lock"></i>Logout</a>
</div><!-- #admin-bar-login -->

<div id="notifications-panel">
	<ul id="notifications-menu">
		<li id="notifications-activity" class="notification-type">
		<?php if ( !empty( $notifications['activity'] ) ) :?>
			<span class="notifications-number"><?php echo count( $notifications['activity'] ); ?></span>
		<?php endif; ?>		
			<div class="admin-bar-dropdown">
				<ul class="notification-list icons-ul">
				<?php if ( !empty( $notifications['activity'] ) ) : for ( $i=0 ; $i<count( $notifications['activity'] ) ; $i++ ) : ?>
					<li id="notification-<?php echo $notifications['activity'][$i]['id']; ?>" class="notification-entry"><i class="icon-li icon-chevron-right"></i>
						<?php echo '<a class="clear-notification" href="' . $link . '?type=activity&amp;notid='.$notifications['activity'][$i]['id'].'&amp;_wpnonce=' . wp_create_nonce( 'clear-single-notification' ) . '"><i class="icon-remove"></i></a>'; ?>
						<?php echo $notifications['activity'][$i]['desc']; ?>
					</li>
				<?php endfor; else: ?>
					<li class="notification-entry"><i class="icon-li icon-chevron-right"></i>You have no new mentions.</li>
				<?php endif; ?>	
				</ul>
				<ul class="notification-links">
					<li><a class="button" href="<?php echo SITEURL . '/activity/'; ?>" title="The sitewide activity feed"><i class="icon-gears"></i>Site Feed</a></li>
					<li><a class="button" href="<?php echo $link . '/activity/mentions/'; ?>" title="Your mentions in the community"><i class="icon-comments-alt"></i>Your Mentions</a></li>
				</ul>					
			</div>
		</li>
		
		<li id="notifications-messages" class="notification-type">
		<?php if ( !empty( $notifications['messages'] ) ) :?>
			<span class="notifications-number"><?php echo count( $notifications['messages'] ); ?></span>
		<?php endif; ?>		
			<div class="admin-bar-dropdown">
				<ul class="notification-list icons-ul">
				<?php if ( !empty( $notifications['messages'] ) ) : for ( $i=0 ; $i<count( $notifications['messages'] ) ; $i++ ) : ?>
					<li id="notification-<?php echo $notifications['messages'][$i]['id']; ?>" class="notification-entry"><i class="icon-li icon-chevron-right"></i>
						<?php echo '<a class="clear-notification" href="' . $link . '?type=messages&amp;notid='.$notifications['messages'][$i]['id'].'&amp;_wpnonce=' . wp_create_nonce( 'clear-single-notification' ) . '"><i class="icon-remove"></i></a>'; ?>
						<?php echo $notifications['messages'][$i]['desc']; ?>
					</li>
				<?php endfor; else: ?>
					<li class="notification-entry"><i class="icon-li icon-chevron-right"></i>You have no new messages.</li>
				<?php endif; ?>	
				</ul>
				<ul class="notification-links">
					<li><a class="button" href="<?php echo $link . '/messages/'; ?>" title="Go to your inbox"><i class="icon-inbox"></i>Inbox</a></li>
					<li><a class="button" href="<?php echo $link . '/messages/sentbox/'; ?>" title="Browse your sent messages"><i class="icon-envelope-alt"></i>Outbox</a></li>
					<li><a class="button" href="<?php echo $link . '/messages/compose/'; ?>" title="Send a new message"><i class="icon-edit"></i>New Message</a></li>
				</ul>					
			</div>
		</li>

		<li id="notifications-friends" class="notification-type">
		<?php if ( !empty( $notifications['friends'] ) ) :?>
			<span class="notifications-number"><?php echo count( $notifications['friends'] ); ?></span>
		<?php endif; ?>		
			<div class="admin-bar-dropdown">
				<ul class="notification-list icons-ul">
				<?php if ( !empty( $notifications['friends'] ) ) : for ( $i=0 ; $i<count( $notifications['friends'] ) ; $i++ ) : ?>
					<li id="notification-<?php echo $notifications['friends'][$i]['id']; ?>" class="notification-entry"><i class="icon-li icon-chevron-right"></i>
						<?php echo '<a class="clear-notification" href="' . $link . '?type=friends&amp;notid='.$notifications['friends'][$i]['id'].'&amp;_wpnonce=' . wp_create_nonce( 'clear-single-notification' ) . '"><i class="icon-remove"></i></a>'; ?>
						<?php echo $notifications['friends'][$i]['desc']; ?>
					</li>
				<?php endfor; else: ?>
					<li class="notification-entry"><i class="icon-li icon-chevron-right"></i>You have no new friend requests.</li>
				<?php endif; ?>	
				</ul>
				<ul class="notification-links">
					<li><a class="button" href="<?php echo $link . '/friends/'; ?>" title="View your friends list"><i class="icon-user"></i>Your Friends</a></li>
					<li><a class="button" href="<?php echo $link . '/activity/friends'; ?>" title="Recent activity by your friends"><i class="icon-gears"></i>Friend Activity</a></li>
				</ul>					
			</div>
		</li>
		
		<li id="notifications-groups" class="notification-type">
		<?php if ( !empty( $notifications['groups'] ) ) :?>
			<span class="notifications-number"><?php echo count( $notifications['groups'] ); ?></span>
		<?php endif; ?>		
			<div class="admin-bar-dropdown">
				<ul class="notification-list icons-ul">
				<?php if ( !empty( $notifications['groups'] ) ) : for ( $i=0 ; $i<count( $notifications['groups'] ) ; $i++ ) : ?>
					<li id="notification-<?php echo $notifications['groups'][$i]['id']; ?>" class="notification-entry"><i class="icon-li icon-chevron-right"></i>
						<?php echo '<a class="clear-notification" href="' . $link . '?type=groups&amp;notid='.$notifications['groups'][$i]['id'].'&amp;_wpnonce=' . wp_create_nonce( 'clear-single-notification' ) . '"><i class="icon-remove"></i></a>'; ?>
						<?php echo $notifications['groups'][$i]['desc']; ?>
					</li>
				<?php endfor; else: ?>
					<li class="notification-entry"><i class="icon-li icon-chevron-right"></i>You have no new group notifications.</li>
				<?php endif; ?>	
				</ul>
				<ul class="notification-links">
					<li><a class="button" href="<?php echo SITEURL . '/groups/'; ?>" title="View the sitewide guild listing"><i class="icon-globe"></i>Guilds</a></li>
					<li><a class="button" href="<?php echo $link . '/groups/'; ?>" title="View your groups listing"><i class="icon-group"></i>Your Guilds</a></li>
					<li><a class="button" href="<?php echo $link . '/activity/groups/'; ?>" title="View recent activity within your groups"><i class="icon-gears"></i>Guild Feed</a></li>
				</ul>					
			</div>
		</li>
	</ul>
</div><!-- #notifications-panel -->
<?php endif; ?>