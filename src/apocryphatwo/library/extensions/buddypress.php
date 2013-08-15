<?php
/**
 * Apocrypha Theme BuddyPress Functions
 * Andrew Clayton
 * Version 1.0.0
 * 8-2-2013
 
----------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
1.0 - Initialization
2.0 - Notifications

______________________________

3.0 - Directories
4.0 - Activity
5.0 - User Profiles
	5.1 - Private Messages
6.0 - Groups
	6.1 - Group Profile
	6.2 - Group Creation
7.0 - User Registration
--------------------------------------------------------------*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*--------------------------------------------------------------
1.0 - INITIALIZATION
--------------------------------------------------------------*/

// Include BuddyPress AJAX functions 
require_once( BP_PLUGIN_DIR . '/bp-themes/bp-default/_inc/ajax.php' );
	
// Register buttons for BuddyPress actions 	
if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

	// Friends button
	add_action( 'bp_member_header_actions'		,	'bp_add_friend_button',           5 	);

	// Activity button
	add_action( 'bp_member_header_actions'		,	'bp_send_public_message_button',  20 	);

	// Messages button
	add_action( 'bp_member_header_actions'		,	'bp_send_private_message_button', 20 	);

	// Group buttons
	add_action( 'bp_group_header_actions'		,	'bp_group_join_button',           5 	);
	add_action( 'bp_group_header_actions'		,	'bp_group_new_topic_button',      20 	);
	add_action( 'bp_directory_groups_actions'	, 	'bp_group_join_button'					);
}

// Override bbPress Forum Tracker Templates 
add_filter( 'bbp_member_forums_screen_topics' 		 , 'apoc_profile_forums_screen' );
add_filter( 'bbp_member_forums_screen_replies' 		 , 'apoc_profile_forums_screen' );
add_filter( 'bbp_member_forums_screen_favorites' 	 , 'apoc_profile_forums_screen' );
add_filter( 'bbp_member_forums_screen_subscriptions' , 'apoc_profile_forums_screen' );
function apoc_profile_forums_screen( $template ) {
	$template = 'members/single/forums';
	return $template;
	}
	
/*--------------------------------------------------------------
2.0 - NOTIFICATIONS
--------------------------------------------------------------*/

/** 
 * Display the frontend notifications menu
 * @version 1.0.0
 */
function apoc_notifications_menu() {
	
	// This function depends on BuddyPress
	if ( !function_exists ( 'bp_version' ) )
		return false;
	
	// Grab the current user
	global $apoc;
	$user 		= $apoc->user->data;
	$user_id	= $user->ID;
	$name		= $user->user_nicename;
	
	// Bail if it's a guest
	if ( $user_id == '' )
		return false;
		
	// Get the notifications!
	$notifications = apoc_get_notifications( $user_id );
		
	// Display the widget ?>
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
							<?php echo '<a class="clear-notification" href="' . bp_core_get_user_domain( $user_id ) . '?type=activity&amp;notid='.$notifications['activity'][$i]['id'].'&amp;_wpnonce=' . wp_create_nonce( 'clear-single-notification' ) . '"><i class="icon-remove"></i></a>'; ?>
							<?php echo '<a href="'.$notifications['activity'][$i]['href'] .'">'.$notifications['activity'][$i]['content'] .'</a>'; ?>
						</li>
					<?php endfor; else: ?>
						<li class="notification-entry"><i class="icon-li icon-chevron-right"></i>You have no new mentions.</li>
					<?php endif; ?>	
					</ul>
					<ul class="notification-links">
						<li><a class="button" href="<?php echo SITEURL . '/activity/'; ?>" title="The sitewide activity feed"><i class="icon-gears"></i>Site Feed</a></li>
						<li><a class="button" href="<?php echo SITEURL . '/members/' . $name . '/activity/mentions/'; ?>" title="Your mentions in the community"><i class="icon-comments-alt"></i>Your Mentions</a></li>
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
							<?php echo '<a class="clear-notification" href="' . bp_core_get_user_domain( $user_id ) . '?type=messages&amp;notid='.$notifications['messages'][$i]['id'].'&amp;_wpnonce=' . wp_create_nonce( 'clear-single-notification' ) . '"><i class="icon-remove"></i></a>'; ?>
							<?php echo '<a href="'.$notifications['messages'][$i]['href'] .'">'.$notifications['messages'][$i]['content'] .'</a>'; ?>
						</li>
					<?php endfor; else: ?>
						<li class="notification-entry"><i class="icon-li icon-chevron-right"></i>You have no new messages.</li>
					<?php endif; ?>	
					</ul>
					<ul class="notification-links">
						<li><a class="button" href="<?php echo SITEURL . '/members/' . $name . '/messages/'; ?>" title="Go to your inbox"><i class="icon-inbox"></i>Inbox</a></li>
						<li><a class="button" href="<?php echo SITEURL . '/members/' . $name . '/messages/sentbox/'; ?>" title="Browse your sent messages"><i class="icon-envelope-alt"></i>Outbox</a></li>
						<li><a class="button" href="<?php echo SITEURL . '/members/' . $name . '/messages/compose/'; ?>" title="Send a new message"><i class="icon-edit"></i>New Message</a></li>
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
							<?php echo '<a class="clear-notification" href="' . bp_core_get_user_domain( $user_id ) . '?type=friends&amp;notid='.$notifications['friends'][$i]['id'].'&amp;_wpnonce=' . wp_create_nonce( 'clear-single-notification' ) . '"><i class="icon-remove"></i></a>'; ?>
							<?php echo '<a href="'.$notifications['friends'][$i]['href'] .'">'.$notifications['friends'][$i]['content'] .'</a>'; ?>
						</li>
					<?php endfor; else: ?>
						<li class="notification-entry"><i class="icon-li icon-chevron-right"></i>You have no new friend requests.</li>
					<?php endif; ?>	
					</ul>
					<ul class="notification-links">
						<li><a class="button" href="<?php echo SITEURL . '/members/' . $name . '/friends/'; ?>" title="View your friends list"><i class="icon-user"></i>Your Friends</a></li>
						<li><a class="button" href="<?php echo SITEURL . '/members/' . $name . '/activity/friends'; ?>" title="Recent activity by your friends"><i class="icon-gears"></i>Friend Activity</a></li>
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
							<?php echo '<a class="clear-notification" href="' . bp_core_get_user_domain( $user_id ) . '?type=groups&amp;notid='.$notifications['groups'][$i]['id'].'&amp;_wpnonce=' . wp_create_nonce( 'clear-single-notification' ) . '"><i class="icon-remove"></i></a>'; ?>
							<?php echo '<a href="'.$notifications['groups'][$i]['href'] .'">'.$notifications['groups'][$i]['content'] .'</a>'; ?>
						</li>
					<?php endfor; else: ?>
						<li class="notification-entry"><i class="icon-li icon-chevron-right"></i>You have no new group notifications.</li>
					<?php endif; ?>	
					</ul>
					<ul class="notification-links">
						<li><a class="button" href="<?php echo SITEURL . '/groups/'; ?>" title="View the sitewide guild listing"><i class="icon-globe"></i>Guilds</a></li>
						<li><a class="button" href="<?php echo SITEURL . '/members/' . $name . '/groups/'; ?>" title="View your groups listing"><i class="icon-group"></i>Your Guilds</a></li>
						<li><a class="button" href="<?php echo SITEURL . '/members/' . $name . '/activity/groups/'; ?>" title="View recent activity within your groups"><i class="icon-gears"></i>Guild Feed</a></li>
					</ul>					
				</div>
			</li>
		</ul>
	</div><!-- #notifications-panel --><?php	
}

/** 
 * Get user notifications without default formatting
 * @version 1.0.0
 */
function apoc_get_notifications( $user_id ) {
	
	global $bp;
	$notifications = BP_Core_Notification::get_all_for_user( $user_id );
	$count = count( $notifications );
	$grouped_notifications = $notification_output = array();

	// Group notifications by type
	for ( $i = 0; $i < $count; $i++ ) {
		$notification = $notifications[$i];
		$grouped_notifications[$notification->component_name][$notification->component_action][] = $notification;
	}
	
	// If we can't identify any of the notification groups, let's bail
	if ( empty( $grouped_notifications ) )
		return false;
	
	// Calculate a renderable output for each notification type
	foreach ( $grouped_notifications as $component_name => $action_arrays ) {
		if ( empty( $action_arrays ) )
			continue;
		if ( !bp_is_active( $component_name ) )
			continue;

		// Loop through each actionable item and try to map it to a component
		foreach ( (array) $action_arrays as $component_action_name => $component_action_items ) {

			// Get the number of actionable items */
			$action_item_count = count( $component_action_items );
			if ( $action_item_count < 1 )
				continue;

			// Loop through the items and format notifications
			for ( $j = 0; $j < $action_item_count; $j++ ) {
			
				// Format the content of the notification using the a custom callback function
				$content = call_user_func( 'apoc_format_notification', $component_name , $component_action_name, $component_action_items[$j]->item_id, $component_action_items[$j]->secondary_item_id, $action_item_count );

				// Create the object to be returned 
				$notification_object = array();
				$notification_object['content'] 	= $content['text'];
				$notification_object['href']   		= $content['link'];
				$notification_object['id']			= $component_action_items[$j]->id;
				
				// Add it to the notification output 
				$notification_output[$component_name][] = $notification_object;
			
			} // end foreach notification item
		} // end foreach notification type
	} // end foreach notification component
	return( $notification_output );
}

/** 
 * Format notifications how I want them
 * @since 0.1
 */
function apoc_format_notification( $component , $action , $item_id , $secondary_item_id , $total_items ) {
	
	// Mentions 
	if ( $component == 'activity' && $action == 'new_at_mention' ) :

		// Construct each mention 
		$activity_id 		= $item_id;
		$poster_user_id		= $secondary_item_id;
		$link				= bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/';
		$user_fullname		= bp_core_get_user_displayname( $poster_user_id );
		$text 				= sprintf( '%1$s mentioned you' , $user_fullname );
		
	// Messages 
	elseif ( $component == 'messages' && $action == 'new_message' ) :
		$link  	= trailingslashit( bp_loggedin_user_domain() . bp_get_messages_slug() . '/inbox' );
		$text = 'You have a new private message';
		
	// Friends 
	elseif ( $component == 'friends' ) :
		switch ( $action ) {
			
			case 'friendship_accepted' :
				$text = sprintf( '%s accepted your friendship request' , bp_core_get_user_displayname( $item_id ) );  
				$link = trailingslashit( bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends' );
				break;
			
			case 'friendship_request' :
				$text = sprintf( 'New friendship request from %s',  bp_core_get_user_displayname( $item_id ) );
				$link = bp_loggedin_user_domain() . bp_get_friends_slug() . '/requests/?new';
				break;
		}
		
	// Groups 
	elseif ( $component == 'groups' ) :	
		
		// Grab some group info 
		if ( $action == 'new_membership_request' ) 
			$group_id = $secondary_item_id;
		else $group_id = $item_id;
		$group = groups_get_group( array( 'group_id' => $group_id ) );
		$group_link = bp_get_group_permalink( $group );	
		
		
		switch ( $action ) {
			
			case 'new_membership_request' :
				$requesting_user_id = $item_id;
				$user_fullname = bp_core_get_user_displayname( $requesting_user_id );
				$text = sprintf( '%s requests group membership' , $user_fullname );
				$link = $group_link . 'admin/membership-requests';
				break;

			case 'membership_request_accepted' :
				$text = sprintf( 'Membership for group "%s" accepted' , $group->name );
				$link = $group_link;
				break;
			
			case 'membership_request_rejected' :
				$text = sprintf( 'Membership for group "%s" rejected' , $group->name );
				$link = $group_link;
				break;
			
			case 'member_promoted_to_admin':
				$text = sprintf( 'You were promoted to administrator in the group "%s"' , $group->name );
					$link = $group_link;
				break;
			
			case 'member_promoted_to_mod':
				$text = sprintf( 'You were promoted to moderator in the group "%s"' , $group->name );
				$link = $group_link;
				break;
			
			case 'group_invite':
				$text = sprintf( 'You have an invitation to join the group: %s' , $group->name );
				$link = bp_loggedin_user_domain() . bp_get_groups_slug() . '/invites';
				break;
			
			case 'new_calendar_event' :
				$text = sprintf( 'New event "%1$s" added to %2$s group calendar.' , get_the_title($secondary_item_id) , $group->name );
				$link = SITEURL . '/calendar/' . $group->slug;
				break;
		}
	endif;
	
	// Return the formatted mention 
	$content = array(
		'text' 	=> $text,
		'link'	=> $link,
	);
	return $content;		
}




/*--------------------------------------------------------------
X.0 - GROUPS
--------------------------------------------------------------*/

/**
 * Get the allegiance of a guild from the database.
 * Display an allegiance block with the faction listed.
 * @Since 2.0
 */
function get_guild_allegiance( $group_id ) {
	$faction = groups_get_groupmeta( $group_id, 'group_faction' );
	$name = 'Neutral';
	switch ( $faction ) {
		case 'aldmeri' :
			$name = 'Aldmeri Dominion';
		break;
		
		case 'daggerfall' :
			$name = 'Daggerfall Covenant';
		break;
		
		case 'ebonheart' :
			$name = 'Ebonheart Pact';
		break;
	}
	$allegiance = '<p class="guild-allegiance ' . $faction . '">' . $name . '</p>';	
	return $allegiance;
}


?>
