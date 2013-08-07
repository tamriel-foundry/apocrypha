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
function get_user_rank( $user_id , $totalposts=0 ) {
	if ( $user_id == '0' ) return;
	
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
	$user_rank = array(
		'current_rank' 	=> $rank['min_posts'],
		'next_rank' 	=> $rank['next_rank'],
		'rank_title'	=> $rank['title']
	);

	// Return the rank information
	return $user_rank;
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
 * Display member info block
 * @since 1.0
 */
function apoc_member_block( $user_id , $context = 'reply' , $avatar = 'thumb' ) {

	// Determine avatar size
	$size = ( 'full' == $avatar ) ? 200 : 100;

	// Get some basic information
	$username 		= bp_core_get_user_displayname( $user_id );
	$link			= bp_core_get_user_domain( $user_id );
	$avatar			= apoc_fetch_avatar( $user_id , $avatar , $size );	
	$total_posts 	= get_user_post_count( $user_id );
	$user_rank		= get_user_rank( $user_id , $total_posts['total'] );
	$title			= get_user_title( $user_id , $user_rank['rank_title'] );
	$allegiance 	= get_user_raceclass( $user_id );
	
	// Get more information for non-minimal blocks
	if ( 'reply' == $context || 'profile' == $context )
		$expbar		= get_user_expbar( $total_posts['total'] , $user_rank['current_rank'] , $user_rank['next_rank'] );
	
	// Get even more stuff for the full profile block
	if ( 'profile' == $context )
		$regdate	= get_user_registration_date( $user_id );
	
	// Display the avatar
	$block		 = '<a class="member-avatar" href="' . $link . '" title="View ' . $username . '&apos;s Profile">' . $avatar . '</a>';
	
	// Member meta block
	$block		.= ( 'directory' == $context ) ? '<div class="member-meta">' : '' ;
	$block		.= '<a class="member-name" href="' . $link . '" title="View ' . $username . '&apos;s Profile">' . $username . '</a>';
	$block		.= $title;	
	$block		.= $allegiance;	
	$block		.= '<p class="user-post-count">Total Posts: ' . $total_posts['total'] . '</p>';
	$block		.= ( 'directory' == $context ) ? '</div>' : '' ;
	
	// Add extra elements
	if ( 'reply' == $context || 'profile' == $context )
		$block	.= $expbar;
	if ( 'profile' == $context )
		$block	.= '<p class="user-join-date">Joined ' . $regdate . '</p>';
	
	// Display it
	echo $block;	
}

/** 
 * Retrieve a user's total post count
 * @since 0.1
 */
function get_user_post_count( $user_id ) {

	// Get the stored count from usermeta
	$posts 	= get_user_meta( $user_id , 'post_count' , true );
	
	// If it's not in the database, build it
	if ( empty( $count ) )
		update_user_post_count( $user_id );
	
	// Return it
	return $posts;
}

/** 
 * Update a user's total post count
 * @since 1.0
 */
function update_user_post_count( $user_id ) {

	// Only do this for registered users
	if ( 0 >= $user_id ) return;

	// Get the counts
	$topics = bbp_get_user_topic_count_raw( $user_id ) ;
	$replies = bbp_get_user_reply_count_raw( $user_id ) ;
	$comments = get_user_comment_count( $user_id );

	$posts = array(
		'topics' 	=> $topics,
		'replies' 	=> $replies,
		'comments' 	=> $comments,
		'total'	 	=> $comments + $topics + $replies,
	);
	
	// Save it
	update_user_meta( $user_id , 'post_count' , $posts );
}

/** 
 * Update the user's post count after they submit a new topic or reply
 * @since 1.0
 */
add_action( 'bbp_new_topic' 	, 'new_bbpress_post_count' );
add_action( 'bbp_new_reply' 	, 'new_bbpress_post_count' );
function new_bbpress_post_count( $reply_author , $topic_author ) {
	$user_id 	= ( isset( $reply_author ) ) ? $reply_author : $topic_author;
	update_user_post_count( $user_id );
}

/** 
 * Update the user's post count after a topic or reply is trashed or untrashed
 * @since 1.0
 */
add_action( 'bbp_trash_reply' 	, 'trash_bbpress_post_count' );
add_action( 'bbp_trash_topic' 	, 'trash_bbpress_post_count' );
add_action( 'bbp_untrash_reply' , 'trash_bbpress_post_count' );
add_action( 'bbp_untrash_topic' , 'trash_bbpress_post_count' );
function trash_bbpress_post_count( $reply_id , $topic_id ) {
	$post_id 	= ( isset( $reply_id ) ) ? $reply_id : $topic_id;
	$post 		= get_post( $post_id );
	$user_id 	= $post->post_author;
	update_user_post_count( $user_id );
}

/** 
 * Update the user's post count after they submit a new comment
 * @since 1.0
 */
add_action( 'comment_post' 		, 'new_comment_post_count' );
function new_comment_post_count( $comment_ID ) {
	$comment	= get_comment( $comment_ID );
	$user_id 	= $comment->user_id;
	update_user_post_count( $user_id );
}

/** 
 * Update the user's post count after a comment is trashed or untrashed
 * @since 1.0
 */
add_action( 'trashed_comment' 		, 'trash_comment_post_count' );
add_action( 'untrashed_comment' 	, 'trash_comment_post_count' );
function trash_comment_post_count( $comment_id ) {
	$comment	= get_comment( $comment_id );
	$user_id 	= $comment->user_id;
	update_user_post_count( $user_id );
}

/** 
 * Count a user's total comments
 * @since 0.1
 */
function get_user_comment_count( $user_id ) {
	global $wpdb;
    $count = $wpdb->get_var('SELECT COUNT(comment_ID) FROM ' . $wpdb->comments. ' WHERE user_id = ' . $user_id . ' AND comment_approved = 1' );
    return $count;
}

/** 
 * Display user site title
 * @since 0.5
 */
function get_user_title( $user_id , $role_name ) {
	
	// If it's a guest, say so
	if ( $user_id == '0' ) { 
		$role_name = 'Guest'; 
		$display_title = $role_name;
	
	// If not a guest, get site title
	} else { 
	
		// Get the user's info
		$user 		= get_userdata( $user_id );
		$roles 		= $user->roles;
		$site_role 	= $roles[0];
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
function get_user_raceclass( $user_id ) {
	
	// Get race and class
	$race 		= get_user_meta( $user_id , 'race' , true );
	if ( 'norace' == $race ) 		$race = '';
	
	$class 		= get_user_meta( $user_id , 'playerclass' , true );
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
		$faction = get_user_meta( $user_id , 'faction' , true );
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

/* 
 * Display the user signature
 * @since 1.0
 */
function user_signature( $user_id ) {
	if ( $user_id == '0' ) return;
	
	// Get the signature
	$signature = get_user_meta( $user_id , 'signature' , true );
	
	if ( $signature != '' ) {
		
		// Evaluate shortcodes
		$signature = do_shortcode( $signature );
	
		// Display it
		echo '<div class="user-signature"><div class="signature-content">' . $signature . '</div></div>' ;
	}
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

