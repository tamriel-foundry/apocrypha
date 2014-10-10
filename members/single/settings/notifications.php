<?php 
/**
 * Apocrypha Theme User Settings Notifications Screen
 * Andrew Clayton
 * Version 1.0.0
 * 10-6-2013
 */
 
// Get the currently displayed user
global $user;
$user 	= new Apoc_User( bp_displayed_user_id() , 'profile' );

// Grab the user's notification settings
if ( !$mention 			= bp_get_user_meta( bp_displayed_user_id(), 'notification_activity_new_mention'			, true ) )
	$mention 			= 'yes';
if ( !$reply 			= bp_get_user_meta( bp_displayed_user_id(), 'notification_activity_new_reply'			, true ) )
	$reply 				= 'yes';
if ( !$new_messages 	= bp_get_user_meta( bp_displayed_user_id(), 'notification_messages_new_message'			, true ) )
	$new_messages 		= 'yes';
if ( !$new_notices 		= bp_get_user_meta( bp_displayed_user_id(), 'notification_messages_new_notice'			, true ) )
	$new_notices  		= 'yes';
if ( !$send_requests 	= bp_get_user_meta( bp_displayed_user_id(), 'notification_friends_friendship_request'	, true ) )
	$send_requests   	= 'yes';
if ( !$accept_requests 	= bp_get_user_meta( bp_displayed_user_id(), 'notification_friends_friendship_accepted'	, true ) )
	$accept_requests 	= 'yes';
if ( !$group_invite 	= bp_get_user_meta( bp_displayed_user_id(), 'notification_groups_invite'				, true ) )
	$group_invite  		= 'yes';
if ( !$group_update 	= bp_get_user_meta( bp_displayed_user_id(), 'notification_groups_group_updated'			, true ) )
	$group_update  		= 'yes';
if ( !$group_promo 		= bp_get_user_meta( bp_displayed_user_id(), 'notification_groups_admin_promotion'		, true ) )
	$group_promo   		= 'yes';
if ( !$group_request 	= bp_get_user_meta( bp_displayed_user_id(), 'notification_groups_membership_request'	, true ) )
	$group_request 		= 'yes';
?>

<?php get_header(); ?>

	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>		
		
		<div id="profile-body">
			<?php do_action( 'template_notices' ); ?>
			
			<nav class="directory-subheader no-ajax" id="subnav" >
				<ul id="profile-tabs" class="tabs" role="navigation">
					<?php bp_get_options_nav(); ?>
				</ul>
			</nav><!-- #subnav -->
			
			<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/notifications'; ?>" method="post" class="standard-form" id="settings-form">
			
				<div class="instructions">	
					<h3 class="double-border bottom">Configure Email Notification Prerefences</h3>
					<ul>
						<li>Use these settings to customize what types of activity on Tamriel Foundry you wish to trigger an email notification to your associated email account.</li>
						<li>Most of these events will still trigger an on-site notification, even if email notification is disabled.</li>
					</ul>
				</div>
				
				<ol id="notification-preferences-list">
					<li class="checkbox">
						<span>A member mentions you in an update using "@<?php bp_displayed_user_username(); ?>":</span>
						<input type="radio" name="notifications[notification_activity_new_mention]" value="yes" <?php checked( $mention, 'yes', true ) ?>>
						<label for="notifications[notification_activity_new_mention]">Yes</label>
						<input type="radio" name="notifications[notification_activity_new_mention]" value="no" <?php checked( $mention, 'no', true ) ?>>
						<label for="notifications[notification_activity_new_mention]">No</label>
					</li>
					
					<li class="checkbox">
						<span>A member replies to an update or comment you've posted:</span>
						<input type="radio" name="notifications[notification_activity_new_reply]" value="yes" <?php checked( $reply, 'yes', true ) ?>>
						<label for="notifications[notification_activity_new_reply]">Yes</label>
						<input type="radio" name="notifications[notification_activity_new_reply]" value="no" <?php checked( $reply, 'no', true ) ?>>
						<label for="notifications[notification_activity_new_reply]">No</label>
					</li>
					
					<li class="checkbox">
						<span>A member sends you a new private message:</span>
						<input type="radio" name="notifications[notification_messages_new_message]" value="yes" <?php checked( $new_messages, 'yes', true ) ?>>
						<label for="notifications[notification_messages_new_message]">Yes</label>
						<input type="radio" name="notifications[notification_messages_new_message]" value="no" <?php checked( $new_messages, 'no', true ) ?>>
						<label for="notifications[notification_messages_new_message]">No</label>
					</li>					
					
					<li class="checkbox">
						<span>A new sitewide notice is posted:</span>
						<input type="radio" name="notifications[notification_messages_new_notice]" value="yes" <?php checked( $new_notices, 'yes', true ) ?>>
						<label for="notifications[notification_messages_new_notice]">Yes</label>
						<input type="radio" name="notifications[notification_messages_new_notice]" value="no" <?php checked( $new_notices, 'no', true ) ?>>
						<label for="notifications[notification_messages_new_notice]">No</label>
					</li>					
					
					<li class="checkbox">
						<span>A member sends you a new friendship request:</span>
						<input type="radio" name="notifications[notification_friends_friendship_request]" value="yes" <?php checked( $send_requests, 'yes', true ) ?>>
						<label for="notifications[notification_friends_friendship_request]">Yes</label>
						<input type="radio" name="notifications[notification_friends_friendship_request]" value="no" <?php checked( $send_requests, 'no', true ) ?>>
						<label for="notifications[notification_friends_friendship_request]">No</label>
					</li>
					
					<li class="checkbox">
						<span>A member accepts your friendship request:</span>
						<input type="radio" name="notifications[notification_friends_friendship_accepted]" value="yes"<?php checked( $accept_requests, 'yes', true ) ?>>
						<label for="notifications[notification_friends_friendship_accepted]">Yes</label>
						<input type="radio" name="notifications[notification_friends_friendship_accepted]" value="no" <?php checked( $accept_requests, 'no', true ) ?>>
						<label for="notifications[notification_friends_friendship_accepted]">No</label>						
					</li>
					
					<li class="checkbox">
						<span>A member invites you to join a guild:</span>
						<input type="radio" name="notifications[notification_groups_invite]" value="yes" <?php checked( $group_invite, 'yes', true ) ?>>
						<label for="notifications[notification_groups_invite]">Yes</label>
						<input type="radio" name="notifications[notification_groups_invite]" value="no" <?php checked( $group_invite, 'no', true ) ?>>
						<label for="notifications[notification_groups_invite]">No</label>				
					</li>
					
					<li class="checkbox">
						<span>Guild information is updated:</span>
						<input type="radio" name="notifications[notification_groups_group_updated]" value="yes" <?php checked( $group_update, 'yes', true ) ?>>
						<label for="notifications[notification_groups_group_updated]">Yes</label>
						<input type="radio" name="notifications[notification_groups_group_updated]" value="no" <?php checked( $group_update, 'no', true ) ?>>
						<label for="notifications[notification_groups_group_updated]">No</label>
					</li>					
					
					<li class="checkbox">
						<span>You are promoted to guild leader or officer:</span>
						<input type="radio" name="notifications[notification_groups_admin_promotion]" value="yes" <?php checked( $group_promo, 'yes', true ) ?>>
						<label for="notifications[notification_groups_admin_promotion]">Yes</label>
						<input type="radio" name="notifications[notification_groups_admin_promotion]" value="no" <?php checked( $group_promo, 'no', true ) ?>>
						<label for="notifications[notification_groups_admin_promotion]">No</label>
					</li>					
					
					<li class="checkbox">
						<span>A member requests to join a private guild for which you are an officer:</span>
						<input type="radio" name="notifications[notification_groups_membership_request]" value="yes" <?php checked( $group_request, 'yes', true ) ?>>
						<label for="notifications[notification_groups_membership_request]">Yes</label>
						<input type="radio" name="notifications[notification_groups_membership_request]" value="no" <?php checked( $group_request, 'no', true ) ?>>
						<label for="notifications[notification_groups_membership_request]">No</label>
					</li>
					
					<li class="submit">
						<button type="submit" name="submit" id="submit" class="auto"><i class="icon-ok"></i>Save Changes</button>
					</li>
					
					<li class="hidden">
						<?php wp_nonce_field('bp_settings_notifications'); ?>
					</li>					
				</ol>
				
			</form><!-- #settings-form -->	
		</div><!-- #profile-body -->
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>