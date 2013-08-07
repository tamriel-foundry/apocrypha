<?php
/**
 * Apocrypha Theme User Functions
 * Andrew Clayton
 * Version 1.0
 * 8-1-2013
 */
 
/*--------------------------------------------------------------
1.0 - PERMISSIONS AND RANKS
--------------------------------------------------------------*/
/** 
 * Assign default ranks based on total post count
 * @since 1.0
 */
function get_user_rank( $userid , $totalposts=0 ) {
	if ( $userid == '0' ) return;
	
	// Check if the user's site rank is already in the theme global
	global $apocrypha;
	if ( !isset ( $apocrypha->user->rank ) ) {
		$ranks = array(
			0 => array(
				'min_posts' => 0,
				'next_rank' => 10,
				'title' 	=> 'Scamp' ),
			1 => array(
				'min_posts' => 10,
				'next_rank' => 25,
				'title' 	=> 'Novice' ),
			2 => array(
				'min_posts' => 25,
				'next_rank' => 50,
				'title' 	=> 'Apprentice' ),
			3 => array(
				'min_posts' => 50,
				'next_rank' => 100,
				'title' 	=> 'Journeyman' ),	
			4 => array(
				'min_posts' => 100,
				'next_rank' => 250,
				'title' 	=> 'Adept' ),
			5 => array(
				'min_posts' => 250,
				'next_rank' => 500,
				'title' 	=> 'Expert' ),
			6 => array(
				'min_posts' => 500,
				'next_rank' => 1000,
				'title' 	=> 'Master' ),			
			7 => array(
				'min_posts' => 1000,
				'next_rank' => 10000,
				'title' 	=> 'Grandmaster' ),
			);
		$i = 0;
		$rank = $ranks[$i];
		while ( $totalposts >= $rank['next_rank'] ) {
			$i++;
			$rank = $ranks[$i];
		}
		$apocrypha->user->rank = array(
			'current_rank' 	=> $rank['min_posts'],
			'next_rank' 	=> $rank['next_rank'],
			'rank_title'	=> $rank['title']
		);
	}

	// Return the rank information
	return $apocrypha->user->rank;
}


/*--------------------------------------------------------------
2.0 - PROFILE FUNCTIONS
--------------------------------------------------------------*/

/** 
 * Get a user's avatar without using gravatar, uses custom defaults
 * @since 1.0
 */
function apoc_fetch_avatar( $user_id , $type='thumb' , $size=100 ) {
	
	if ( $user_id > 0 ) {
		$avatar	= bp_core_fetch_avatar( $args = array (
			'item_id' 		=> $user_id,
			'type'			=> $type,
			'height'		=> $size,
			'width'			=> $size,
			'no_grav'		=> true,
			));
		if ( strrpos( $avatar , 'mystery-man.jpg' ) ) 
			$avatar = apoc_guest_avatar( $type , $size ); 
	}
	else $avatar = apoc_guest_avatar( $type , $size ); 
	return $avatar;
}

/**
 * Randomly selects and returns a guest avatar from the available choices
 * @since 1.0
 */
function apoc_guest_avatar( $type ='thumb' , $size = 100 ) {
	$avsize = ( 'full' == $type ) ? '-large' : '' ;
	$avatars = array( 'aldmeri' , 'daggerfall' , 'ebonheart' , 'undecided' );
	$avatar = $avatars[array_rand($avatars)];
    $src = trailingslashit( THEME_URI ) . "images/avatars/{$avatar}{$avsize}.jpg";
	$guest_avatar = '<img src="' . $src . '" alt="Avatar Image" class="avatar" width="' . $size . '" height="' . $size . '">';
    return $guest_avatar;
}

/** 
 * Count a user's total comments
 * @since 0.1
 */
function get_user_comment_count( $userid ) {
    global $wpdb;
	$author_email = get_userdata( $userid )->data->user_email;
    $count = $wpdb->get_var('SELECT COUNT(comment_ID) FROM ' . $wpdb->comments. ' WHERE comment_author_email = "' . $author_email . '"');
    return $count;
}

/** 
 * Compute a user's total post count
 * @since 0.1
 */
function get_user_post_count( $userid ) {

	$topics = bbp_get_user_topic_count_raw( $userid ) ;
	$replies = bbp_get_user_reply_count_raw( $userid ) ;
	$comments = get_user_comment_count( $userid );
	
	$posts = array(
		'topics' 	=> $topics,
		'replies' 	=> $replies,
		'comments' 	=> $comments,
		'total'	 	=> $comments + $topics + $replies,
	);
	return $posts;
}

/** 
 * Display user site title
 * @since 0.5
 */
function get_user_title( $userid , $role_name ) {
	
	// If it's a guest, say so
	if ( $userid == '0' ) { 
		$role_name = 'Guest'; 
		$display_title = $role_name;
	
	// If not a guest, get site title
	} else { 
	
		// Get the user's info
		global $apocrypha;
		$user = $apocrypha->user;
		
		// Get user roles
		$roles = $apocrypha->user->roles;
		$site_role = $roles[0];
		$forum_role = $roles[1];
		
		// Assign titles appropriately
		if ( 'administrator' == $site_role ) :
			$role_name = 'Daedric Prince';
		elseif ( 'bbp_moderator' == $forum_role || 'bbp_keymaster' == $forum_role ) :
			$role_name = 'Moderator'; 
		elseif ( 'guildmember' == $site_role ) :
			$role_name = 'Entropy Rising';
		elseif ( 'banned' == $site_role ) :
			$role_name = 'Banned';
		else :
			// Handle [refixes
			$regdate = $user->data->user_registered;
			$regdate = strtotime( $regdate );
			if ( $regdate < 1352592000 ) $prefix = 'Founder';
		endif;
		
		// Construct the full title
		if ( isset( $prefix ) ) $display_title = $prefix . ', ' . $role_name;
		else $display_title = $role_name;
	}
	
	// Display the title
	$role_class = strtolower( str_replace( " ", "-", $role_name) );
	return '<p class="user-title ' . $role_class . '">' . $display_title . '</p>';
}

/**
 * Display user post experience bar
 * @since 1.0
 */
function get_user_expbar( $totalposts , $current_rank , $next_rank ) {
	$percentexp = ( $totalposts - $current_rank ) / ( $next_rank - $current_rank );
	$percentexp = round( $percentexp, 2) * 100;
	$posts_to_ding = $next_rank - $totalposts;
	
	// Check for proper grammar!
		if( $posts_to_ding == 1 )
			$exptip = $posts_to_ding . ' more post to next rank!';
		else
			$exptip = $posts_to_ding . ' more posts to next rank!';
			
	// Display the bar
	$bar = '<div class="user-exp-container" title="' . $exptip . '"><div class="user-exp-bar" style="width:' . $percentexp . '%;"></div></div>';
	return $bar;
}

/* 
 * Get a user's declared race and class
 * @since 0.4
 */
function get_user_raceclass( $userid ) {
	
	// Get race and class
	$race 		= get_user_meta( $userid , 'race' , true );
	if ( 'norace' == $race ) 		$race = '';
	
	$class 		= get_user_meta( $userid , 'playerclass' , true );
	if ( 'undecided' == $class ) 	$class = '';
	
	// If race is set, we can figure out faction from that
	if ( '' != $race ) :
		$alliances = array(
			'aldmeri' 		=> array( 'altmer' , 'bosmer' , 'khajiit' ),
			'daggerfall' 	=> array( 'breton' , 'orc' , 'redguard' ),
			'ebonheart'		=> array( 'argonian' , 'nord' , 'dunmer' ),
		);
		foreach ( $alliances as $alliance => $races ) {
			if ( in_array( $race , $races ) )
				$faction = $alliance;			
		}

	// Otherwise, grab the faction from database
	else :
		$faction = get_user_meta( $userid , 'faction' , true );
		if ( 'undecided' == $faction ) 	$faction = '';
	endif;	
	
	// Nothing set
	$separator	= '';
	if ( '' == $race && '' == $class && '' == $faction )
		return false;
	
	// Otherwise, display what we have		
	if ( '' == $race ) $race = $faction;
	if ( $race != '' ) $separator = ' ';
	$raceclass = '<p class="user-allegiance ' . $faction . '">' . ucfirst( $race ) . $separator . ucfirst( $class ) . '</p>';
	return $raceclass;
}






/*--------------------------------------------------------------
3.0 - MISCELLANEOUS
--------------------------------------------------------------*/

/**
 * Count users by a specific meta key
 * @since 0.1
 */
function count_users_by_meta( $meta_key , $meta_value ) {
	global $wpdb;
	$user_meta_query = $wpdb->get_var( 
		$wpdb->prepare(
			"SELECT COUNT(*) 
			FROM $wpdb->usermeta 
			WHERE meta_key = %d 
			AND meta_value = %s" , 
			$meta_key , $meta_value 
			) 
		);
	return intval($user_meta_query);
}

