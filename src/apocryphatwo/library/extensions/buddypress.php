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
4.0 - Directories
5.0 - Groups
6.0 - Group Creation
______________________________

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

	// Profile Filters
	new Apoc_Profile();
	
	// Group Extensions
	if ( class_exists( 'BP_Group_Extension' ) )
		bp_register_group_extension( 'Apoc_Group_Add_Leader' );
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
		
		// Guild Buttons
		add_action( 'bp_group_header_actions'		,	'bp_group_join_button',           5 	);
		add_action( 'bp_directory_groups_actions'	, 	'bp_group_join_button'					);
		
		// Group Profile Fields
		add_action( 'groups_create_group_step_save_group-details' 	, array( $this , 'save_group_fields' ) );
		add_action( 'groups_details_updated'						, array( $this , 'save_group_fields' ) );
		
		// Private Messaging Hack
		add_action( 'bp_actions' 					, 	array( $this , 'tinymce_messages_hack' ) , 1 );
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
		
		// Guild Buttons
		add_filter( 'bp_get_group_join_button' 				, array( $this, 'join_button' ) );
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
	 * Copies 'message_content' from TinyMCE into the form's 'content' field (required hack)
	 */
	function tinymce_messages_hack() {
		if ( bp_is_messages_component() && isset( $_POST['send'] ) && empty( $_POST['content'] ) && !empty( $_POST['message_content'] ) ) {
			$_POST['content'] = $_POST['message_content'];
		}
	}

	/**
	 * Modify the join guild button
	 */
	function join_button( $button ) {
		global $groups_template;
		$is_member = $groups_template->group->is_member;
		
		// Force private groups to use the request membership form
		if ( 'private' == $groups_template->group->status && 1 != $groups_template->group->is_member )
			$button['link_href'] = bp_get_group_permalink( $group ) . 'request-membership';
		
		// Apply some styling
		$button['wrapper'] 		= false;
		$button['link_class'] 	.= ' button';
		$button['link_text']	= $is_member ? '<i class="icon-remove"></i>' . $button['link_text'] : '<i class="icon-group"></i>' . $button['link_text']; 
		
		// Don't let people try to join Entropy Rising
		if ( 1 == $groups_template->group->id )
			$button = NULL;
			
		// Return the button
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
			
	/* 
	 * Save Custom Group Fields to SQL 
	 */
	function save_group_fields( $group_id ) {
		global $bp;
		if($bp->groups->new_group_id)  $id = $bp->groups->new_group_id;
		else  $id = $group_id;
		
		$group_is_guild = ( 'group' == $_POST['group-type'] ) ? 0 : 1;
			groups_update_groupmeta( $id, 'is_guild', $group_is_guild );
			
		if ( $_POST['group-website'] )
			groups_update_groupmeta( $id, 'group_website', $_POST['group-website'] );  
	
		if ( $_POST['group-platform']  )
			groups_update_groupmeta( $id, 'group_platform', $_POST['group-platform'] );
			
		if ( $_POST['group-faction']  )
			groups_update_groupmeta( $id, 'group_faction', $_POST['group-faction'] );
			
		if ( $_POST['group-region']  )
			groups_update_groupmeta( $id, 'group_region', $_POST['group-region'] );
			
		if ( $_POST['group-style']  )
			groups_update_groupmeta( $id, 'group_style', $_POST['group-style'] );
			
		if ( $_POST['group-interests']  )
			groups_update_groupmeta( $id, 'group_interests', $_POST['group-interests'] );
	}
}

add_filter( 'bp_get_activity_delete_link' , 'apoc_activity_delete_icon' );
function apoc_activity_delete_icon( $link ) {
	$link = str_replace( 'Delete' , '<i class="icon-remove"></i>Delete' , $link );
	return $link;
}

/*--------------------------------------------------------------
4.0 - DIRECTORIES
--------------------------------------------------------------*/
/** 
 * Customize search forms a bit for context
 * @since 0.1
 */
function apoc_members_search_form() {
	$default_search_value = 'Search for members...';
	$search_value         = !empty( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : $default_search_value; ?>
	<input type="text" name="s" id="members_search" placeholder="<?php echo esc_attr( $search_value ) ?>" /><?php
}
function apoc_groups_search_form() {
	$default_search_value = 'Search for guilds...';
	$search_value         = !empty( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : $default_search_value; ?>
	<input type="text" name="s" id="groups_search" placeholder="<?php echo esc_attr( $search_value ) ?>" /><?php
}
function apoc_messages_search_form() {
	$default_search_value = 'Search messages...';
	$search_value         = !empty( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : $default_search_value; ?>
	<form action="" method="get" id="search-message-form">
		<input type="text" name="s" id="messages_search" placeholder="<?php echo esc_attr( $search_value ) ?>">
	</form><?php
}
	
	
/*--------------------------------------------------------------
5.0 - GROUPS
--------------------------------------------------------------*/

/**
 * Apocrypha Group Class
 * For use in directories and guild profiles
 */
class Apoc_Group {

	// The context in which this user is being displayed
	public $context;
	
	// The HTML member block
	public $block;
	
	/**
	 * Constructs relevant information regarding a TF user 
	 * The scope of information that is added depends on the context supplied
	 */	
	function __construct( $group_id = 0 , $context = 'profile' ) {
	
		// Set the context
		$this->context = $context;
		
		// Get data for the user
		$this->get_data( $group_id );
		
		// Format data depending on the context
		$this->format_data( $context );
	}
	
	/**
	 * Gets user data for a forum reply or article comment
	 */	
	function get_data( $group_id ) {
		
		// Get the meta data
		$allmeta = wp_cache_get( 'bp_groups_allmeta_' . $group_id, 'bp' );
		if ( false === $allmeta ) {
			global $bp, $wpdb;
			$allmeta = array();
			$rawmeta = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM " . $bp->groups->table_name_groupmeta . " WHERE group_id = %d", $group_id ) );
			foreach( $rawmeta as $meta ) {
				$allmeta[$meta->meta_key] = $meta->meta_value;			
			}
			wp_cache_set( 'bp_groups_allmeta_' . $group_id, $allmeta, 'bp' );
		}
		
		// Add data to the class object
		$this->id			= $group_id;
		$this->fullname		= bp_get_group_name();
		$this->domain		= bp_get_group_permalink();
		$this->slug			= bp_get_group_slug();
		$this->guild		= ( $allmeta['is_guild'] == 1 ) ? 1 : 0;
		$this->type			= $this->type();
		$this->members		= bp_get_group_member_count();
		$this->alliance		= $allmeta['group_faction'];
		$this->faction		= $this->allegiance();
		$this->platform		= $allmeta['group_platform'];
		$this->region		= $allmeta['group_region'];
		$this->style		= $allmeta['group_style'];
		$this->interests	= unserialize( $allmeta['group_interests'] );
		$this->website		= $allmeta['group_website'];
		
		// Get some extra stuff on user profiles
		if ( $this->context == 'profile' ) {
			$this->byline	= $this->byline();	
			$this->admins 	= $this->admins();
			$this->mods		= $this->mods();
		}
	}
	
	/* 
	 * Get a group's filtered type
	 * @since 0.4
	 */
	function type() {
		$type = bp_get_group_type();
		if ( $this->guild )
			$type = str_replace( 'Group' , 'Guild' , $type );
		return $type;
	}

	/* 
	 * Get a group's declared allegiance
	 */
	function allegiance() {
	
		switch( $this->alliance ) {
			
			case 'aldmeri' :
				$faction = 'Aldmeri Dominion';
				break;
			case 'daggerfall' :
				$faction = 'Daggerfall Covenant';
				break;
			case 'ebonheart' :
				$faction = 'Ebonheart Pact';
				break;
			case 'neutral' :
				$faction = 'Neutral';
				break;
			default :
				$faction = 'Undeclared';
				break;		
		}
		return $faction;
	}

	/* 
	 * Get a group's platform and region preference
	 */	
	function platform() {
		
		// Format platform
		$platform 	= $this->platform;
		if ( $platform ) {
			$sql	 	= array( 'pcmac' , 'xbox' , 'playstation' , 'blank' );
			$formatted	= array( 'PC' , 'Xbox' , 'PS4' , '' );
			$platform	= str_replace( $sql , $formatted , $platform );
		}
		
		// Format region
		$region		= $this->region;
		if ( $region ) {
			$sql		= array( 'NA' , 'EU' , 'OC' , 'blank' , '' );
			$formatted	= array( 'North America' , 'Europe' , 'Oceania' , 'Global' , 'Global' );
			$region		= str_replace( $sql , $formatted , $region );
		}
		
		// Format the tooltip based on what data is available
		if ( $platform != '' && $region != '' )
			$tooltip = implode( ' - ' , array( $platform , $region ) );
		elseif ( $platform == '' && $region != '' ) 
			$tooltip = $region;
		elseif ( $platform != '' && $region == '' ) 
			$tooltip = $platform;
	
		// Return the tip
		$tooltip 	= ( $tooltip ) ? '<p class="group-member-count">' . $tooltip . '</p>' : '';
		return $tooltip;
	}
	
	/* 
	 * Display the group's interest icons
	 */	
	function interest_icons() {
	
		// Get the data
		$interests 	= $this->interests;
		if ( empty ( $interests ) )
			return false;
			
		$playstyle 	= $this->style;
		if ( $playstyle == 'blank' ) 
			$playstyle = '';

		// Do some grammar
		$lower 	= array( 'pve' , 'pvp' , 'rp' , 'crafting' );
		$upper 	= array( 'PvE' , 'PvP' , 'RP' , 'Crafting' );
		$focus 	= implode( ', ' , $interests );
		$focus 	= str_replace ( $lower , $upper , $focus );
		
		// Generate a tooltip for our icons
		$tooltip = implode( ' - ' , array ( ucfirst( $playstyle ) ,  $focus ) );
			
		// Display them
		$icons 		 = '<div class="guild-style-icons ' . $playstyle . '" title="' . $tooltip . '"><ul>';
		foreach( $interests as $interest_name => $interest_val ) {
			$icons 	.= '<li class="guild-style-icon ' . $interest_val . '"></li>';
		}
		$icons 		.= '</ul></div>';
		return $icons;
	}
	
	/* 
	 * Generate a byline for the user profile with their allegiance information
	 */
	function byline() {
	
		// Get the data
		$faction	= $this->faction;
		$type		= strtolower( $this->type );
		$name		= $this->fullname;
			
		// Generate the byline
		if ( $faction == 'Undeclared' || $faction == 'Neutral' )
			$byline = $name . ' is a ' . $type . ' with no declared political allegiance.';
		else
			$byline = $name . ' is a ' . $type . ' of the ' . $faction;
		
		// Return the byline
		return $byline;
	}
	
	/**
	 * Formats the guild website
	 */	
	function website() {
	
		// Get the url
		$url = $this->website;
		$website = '';
		if ( $url )	$website = '<p class="group-website"><a href="' . $url . '" title="Visit Guild Website" target="_blank">Guild Website</a></p>';
		return $website;
	}
	
	function admins() {
	
		global $groups_template;
		$admins = $groups_template->group->admins;
		$list = '';
		
		if ( !empty( $admins ) ) {
			$list = '<ul id="group-admins">';
			foreach( $admins as $admin ) {
				$avatar = new Apoc_Avatar( array( 'user_id' => $admin->user_id , 'size' => 50 , 'link' => true ) );
				$list .= '<li>' . $avatar->avatar;
				$list .= '<span class="leader-name">' . bp_core_get_user_displayname( $admin->user_id ) . '</span></li>';
			}
			$list .= '</ul>';
		}
		
		return $list;
	}
	
	function mods() {
	
		global $groups_template;
		$mods = $groups_template->group->mods;
		$list = '';
		
		if ( !empty( $mods ) ) {
			$list = '<ul id="group-admins">';
			foreach( $mods as $mod ) {
				$avatar = new Apoc_Avatar( array( 'user_id' => $mod->user_id , 'size' => 50 , 'link' => true ) );
				$list .= '<li>' . $avatar->avatar;
				$list .= '<span class="leader-name">' . bp_core_get_user_displayname( $mod->user_id ) . '</span></li>';
			}
			$list .= '</ul>';
		}
		
		return $list;
	}
	
	/**
	 * Formats the output user block
	 */	
	function format_data( $context ) {
		
		// Setup the basic info block
		$block		= '<a class="member-name" href="' . $this->domain . '" title="View ' . $this->fullname . ' Group Page">' . $this->fullname . '</a>';
		$block		.= $this->title;	
		$block		.= '<p class="group-type">' . $this->type . '</p>';
		$block		.= $allegiance = '<p class="user-allegiance ' . $this->alliance . '">' . $this->faction . '</p>';
		$block		.= $this->platform();
		$block		.= '<p class="group-member-count">' . $this->members . '</p>';

		// Do some things differently depending on context
		$icons			= $this->interest_icons();
		switch( $context ) {
		
			case 'directory' :
				$avatar					= bp_get_group_avatar( $args = array( 'type' => 'thumb' , 'height' => 100, 'width' => 100 ) );
				$avatar					= '<a class="member-avatar" href="' . $this->domain . '" title="View ' . $this->fullname . ' Group Page">' . $avatar . '</a>';
				$avatar					= '<div class="group-avatar-block">' . $avatar . $icons . '</div>';
				$block 					= '<div class="member-meta user-block">' . $block . '</div>';	
				break;
					
			case 'profile' :
				$avatar					= bp_get_group_avatar( $args = array( 'type' => 'full' , 'height' => 200, 'width' => 200 ) );
				$avatar					= '<a class="member-avatar" href="' . $this->domain . '" title="View ' . $this->fullname . ' Group Page">' . $avatar . '</a>';
				$block					.= $this->website();
				$block					= $block . $icons;
				break;
				
			case 'widget' :
				$avatar					= bp_get_group_avatar( $args = array( 'type' => 'thumb' , 'height' => 100, 'width' => 100 ) );
				$avatar					= '<a class="member-avatar" href="' . $this->domain . '" title="View ' . $this->fullname . ' Group Page">' . $avatar . '</a>';
				$avatar					= '<div id="featured-guild-avatar" class="group-avatar-block">' . $avatar . '</div>';
				$block 					= '<div id="featured-guild-meta" class="member-meta user-block">' . $block . '</div>';	
				break;				
		}
		
		// Prepend the avatar
		$this->avatar 	= $avatar;
		$block			= $avatar . $block;
		
		// Add the html to the object
		$this->block 	= $block;
	}
}

/**
 * Count guilds by a specific meta key
 * @since 0.1
 */
function count_groups_by_meta($meta_key, $meta_value) {
	global $wpdb, $bp;
	$user_meta_query = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM " . $bp->groups->table_name_groupmeta . " WHERE meta_key = %d AND meta_value= %s" , $meta_key , $meta_value ) );
	return intval($user_meta_query);
}

/**
 * Helper function to check if a group is a guild
 */
function group_is_guild( $group_id ) {
	$guild = groups_get_groupmeta( $group_id , 'is_guild' );
	$is_guild = ( $guild == 1 ) ? true : false;
	return $is_guild;
}

/*--------------------------------------------------------------
6.0 - GROUP CREATION
--------------------------------------------------------------*/
class Apoc_Group_Add_Leader extends BP_Group_Extension {

	// Define the slug
	public $slug = 'leader';

	function __construct() {
		
		// Provide arguments used by the groups API
		$args = array(
		
			// Where is the component used
			'visibility' => 'private',		
			
			// Set the details of where the component is used
			'screens'	=> array (
				'create' => array (
					'enabled'					=> true,
					'name'						=> 'Guild Leader',
					'slug'						=> 'leader',
					'create_step_position'		=> 99 ),
				'edit'	=> array (
					'enabled'					=> false ),
				'admin'	=> array (
					'enabled'					=> false ),
			),		
		);
		
		// Pass the args back to the groups API
		parent::init( $args );
	}
	
	/*
	 * Generates markup for the create/edit/admin screens
	 */
	function settings_screen() {
	
		// Make sure we are in the right place
		global $bp, $groups_template;
		if ( !bp_is_group_creation_step( $this->slug ) )
		return false; 
		
		
		// Display the form fields ?>
		<div class="instructions">
			<h3 class="double-border bottom">Step 6 - Appoint Guild Leader</h3>
			<ul>
				<li>Enter the exact email address of the user you wish to promote to guild leader.</li>
				<li>Please be careful to precisely enter this field, as errors in the email address may have unintended consequences.</li>
			</ul>
		</div>
		
		<ol id="group-create-list">
			<li class="text">
				<label for="editable-guild-leader">Guild Leader's Email (&#9734;) :</label>
				<input type="text" id="editable-guild-leader" name="editable-guild-leader" title="Leader email address" value="" size="50" />
			</li>
			
			<li class="hidden">
				<?php wp_nonce_field( 'groups_create_save_' . $this->slug ); ?>
			</li><?php			
	}
	
	/*
	 * Save the data and assign the new leader
	 */
	function settings_screen_save() {
		global $bp;
		
		// Make sure we have the group ID
		$group_id = $_POST['group_id'];
		if ( !$group_id )
			$group_id = $bp->groups->current_group->id;	
			
		// Set error redirect based on save method
		$redirect_url = $bp->root_domain . '/' . $bp->groups->slug . '/create/step/' . $this->slug;
		
		// Email cannot be empty
		if ( empty( $_POST['editable-guild-leader'] ) ) {
			bp_core_add_message( 'You must enter a valid email address.' , 'error' );
			bp_core_redirect( $redirect_url );
			exit();
		}
		
		// Make sure the nonce checks
		check_admin_referer( 'groups_create_save_' . $this->slug );
		
		// Get the leader by email
		$leader_email 	= sanitize_email ( $_POST['editable-guild-leader'] );
		$leader			= get_user_by( 'email' , $leader_email );
		
		// If we don't recognize the email, bail out
		if( empty( $leader ) ) {
			bp_core_add_message( 'This email address is not recognized.' , 'error' );
			bp_core_redirect( $redirect_url );
			exit();
		}

		// Otherwise, set the group leader, and remove the creator
		$leader_id	= $leader->ID;
		if ( $leader_id != get_current_user_id() ) { 
			groups_accept_invite( $leader_id , $group_id );	
			$member = new BP_Groups_Member( $leader_id , $group_id );
			$member->promote( 'admin' );
			groups_leave_group( $group_id , $creator_id	);
		}		
	}
}

/*
 * Improve the friend invite interface to use fancy checkboxes!
 */
function apoc_group_invite_friend_list() {
	global $bp;
	if ( empty( $group_id ) )
		$group_id = !empty( $bp->groups->new_group_id ) ? $bp->groups->new_group_id : $bp->groups->current_group->id;

	if ( $friends = friends_get_friends_invite_list( bp_loggedin_user_id(), $group_id ) ) {
		$invites = groups_get_invites_for_group( bp_loggedin_user_id(), $group_id );

		for ( $i = 0, $count = count( $friends ); $i < $count; ++$i ) {
			$checked = '';
			if ( !empty( $invites ) ) {
				if ( in_array( $friends[$i]['id'], $invites ) )
					$checked = ' checked="checked"';
			}
			$items[] = '<li><input' . $checked . ' type="checkbox" name="friends[]" id="f-' . $friends[$i]['id'] . '" value="' . esc_attr( $friends[$i]['id'] ) . '" /><label for="friends">' . $friends[$i]['full_name'] . '</label></li>';
		}
	}

	// Return the list
	if ( !empty( $items ) )
		echo implode( "\n", (array) $items );
	else
	return false;
}


?>
