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
3.0 - User Profiles
______________________________

4.0 - Directories
5.0 - Activity
6.0 - Groups
7.0 - Registration
--------------------------------------------------------------*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*--------------------------------------------------------------
1.0 - INITIALIZATION
--------------------------------------------------------------*/

// Include BuddyPress AJAX functions 
require_once( BP_PLUGIN_DIR . '/bp-themes/bp-default/_inc/ajax.php' );
	
// Register buttons for BuddyPress actions 	
if ( !is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

	// User Profile
	new Apoc_Profile();

	// Group Profile
	add_action( 'bp_group_header_actions'		,	'bp_group_join_button',           5 	);
	add_action( 'bp_group_header_actions'		,	'bp_group_new_topic_button',      20 	);
	add_action( 'bp_directory_groups_actions'	, 	'bp_group_join_button'					);
	
	// Directories
	
	// Registration
}
	
/*--------------------------------------------------------------
2.0 - NOTIFICATIONS
--------------------------------------------------------------*/

/** 
 * Generates user notifications for the admin bar in the site header
 * Formats these notifications by grouping them by component
 * Disaggregates multiple notifications of the same type to display notifications individually
 *
 * @version 1.0.0
 */
class Apoc_Notifications extends BP_Core_Notification {

	/* Class Properties */
	public $user_id;
	public $notifications;
	
	/* Constructor */
	function __construct( $user_id ) {
	
		$this->user_id 			= $user_id;
		$this->notifications	= $this->get_notifications();	
	}

	function get_notifications() {
		
		$notifications = $this->get_all_for_user( $this->user_id );
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
		foreach ( $grouped_notifications as $component => $actions ) {
			if ( empty( $actions ) )
				continue;

			// Loop through each actionable item and try to map it to a component
			foreach ( (array) $actions as $action => $items ) {

				// Get the number of actionable items */
				$total = count( $items );
				if ( $total < 1 )
					continue;

				// Loop through the items and format notifications
				for ( $j = 0; $j < $total; $j++ ) {
				
					// Format the content of the notification using the a custom callback function
					$content = $this->format_notifications( $component , $action, $items[$j]->item_id, $items[$j]->secondary_item_id, $total );

					// Add it to the notification output 
					$notification_output[$component][] = array(
						'content'	=> $content['text'],
						'href'		=> $content['link'],
						'id'		=> $items[$j]->id,
						);
				
				} // end foreach notification item
			} // end foreach notification type
		} // end foreach notification component
		return( $notification_output );
	}

	/** 
	 * Format notifications how I want them
	 * @since 0.1
	 */
	function format_notifications( $component , $action , $item_id , $secondary_item_id , $total_items ) {
		
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
}

/**
 * Helper function to output notifications to template
 * @version 1.0.0
 */
function apoc_user_notifications( $user_id ) {
	$notifications = new Apoc_Notifications( $user_id );
	return $notifications->notifications;
}


/*--------------------------------------------------------------
3.0 - USER PROFILES
--------------------------------------------------------------*/

/**
 * BuddyPress user profiles
 * This class contains filters, actions, and methods used in the construction of TF member profiles
 *
 * @version 1.0.0
 */
class Apoc_Profile {


	/**
	 * Initialize BuddyPress user profile methods
	 */
	function __construct() {
	
		// Add profile actions
		$this->actions();		
		
		// Add profile filters
		$this->filters();
	
	}
	
	/**
	 * Add user profile actions
	 */
	private function actions() {
	
		// Profile Buttons
		add_action( 'bp_member_header_actions'		,	'bp_add_friend_button',           5 	);
		add_action( 'bp_member_header_actions'		,	'bp_send_public_message_button',  20 	);
		add_action( 'bp_member_header_actions'		,	'bp_send_private_message_button', 20 	);
	
	}
	

	/**
	 * Add user profile filters
	 */	
	private function filters() {
	
		// Profile Buttons
		add_filter( 'bp_get_add_friend_button' 				, array( $this, 'friend_button' ) );
		add_filter( 'bp_get_send_public_message_button' 	, array( $this, 'mention_button' ) );
		add_filter( 'bp_get_send_message_button_args'		, array( $this, 'message_button' ) );
		
		// Profile Status
		add_filter( 'bp_get_activity_latest_update' 		, array( $this, 'strip_status' ) );
		
		// Override bbPress Forum Tracker Templates 
		add_filter( 'bbp_member_forums_screen_topics' 		 , array( $this, 'forums_template' ) );
		add_filter( 'bbp_member_forums_screen_replies' 		 , array( $this, 'forums_template' ) );
		add_filter( 'bbp_member_forums_screen_favorites' 	 , array( $this, 'forums_template' ) );
		add_filter( 'bbp_member_forums_screen_subscriptions' , array( $this, 'forums_template' ) );
	}
	
	
	/**
	 * Modify user profile buttons
	 */
	function friend_button( $button ) {
	$button['wrapper'] 	= false;
	$button['link_class'] 	.= ' button';
	$button['link_text']	= '<i class="icon-male"></i>' . $button['link_text']; 
	return $button;
	}
	function mention_button( $button ) {
		$button['wrapper']		= false;
		$button['link_class'] 	.= ' button';
		$button['link_text']	= '<i class="icon-comment"></i>' . $button['link_text']; 
		return $button;
	}
	function message_button( $button ) {
		$button['wrapper'] 		= false;
		$button['link_class'] 	.= ' button';
		$button['link_text']	= '<i class="icon-envelope"></i>' . $button['link_text']; 
		return $button;
	}
	
	
	/**
	 * Strip "View" link out of activity updates
	 */
	function strip_status( $update ) {
		$update = preg_replace( '/<a(.*)<\/a>/' , '' , $update );
		return $update;
	}
	
	
	/**
	 * Override the bbPress forum tracker templating
	 */
	function forums_template( $template ) {
		$template = 'members/single/forums';
		return $template;
		}
	
	
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
