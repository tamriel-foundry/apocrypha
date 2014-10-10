<?php
/**
 * Apocrypha Theme Custom Widgets
 * Andrew Clayton
 * Version 1.0.0
 * 8-3-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
/** 
 * Display recent comments on front page articles
 * @since 0.1
 */
function recent_comments_widget( $number = 3 , $size = 50 ) {

	// This widget depends on BuddyPress, so bail if it's not active 
	if ( !function_exists( 'bp_version' ) )
		return false;

	// Exclude posts in the Guild News category 
	global $wpdb;
	
	// Build the SQL 
	$request =
		"SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID,
		comment_author, user_id, comment_date, comment_date_gmt, comment_approved, comment_type
		FROM $wpdb->comments, $wpdb->posts
		WHERE comment_approved = '1'
		AND comment_type = ''
		AND ID = comment_post_ID
		AND post_status = 'publish'
		AND post_password = ''
		ORDER BY comment_date DESC LIMIT $number";
	$comments = $wpdb->get_results( $request );

	// If comments are found, loop them 
	if ($comments) : ?>
	<div class="recent-comments-widget widget">
		<header class="widget-header"><h3 class="widget-title">Article Discussion</h3></header>
		<ul class="recent-discussion-list">

		<?php $comment_alt = 1; ?>
		<?php foreach ($comments as $comment) :  
		
			// Get the comment author
			$user_id 	= $comment->user_id;
			
			// Get the comment time, and make it relative 
			$comment_time = $comment->comment_date_gmt;
			$comment_time =  bp_core_time_since( $comment_time , current_time( 'timestamp' , true ) );
			$comment_time = '<time class="recent-discussion-time" datetime="' . date( 'Y-m-d\TH:i' , strtotime($comment->comment_date) ) . '">' . $comment_time . '</time>';			
			ob_start(); 
			
			// Count evens and odds 
			$class = ( $comment_alt % 2 ) ? 'odd' : 'even';
			$comment_alt++;
		
			// Display the comment  
            echo '<li class="recent-discussion '.$class.'">';
			
			// Get the avatar
			$avatar	= new Apoc_Avatar( array( 'user_id' => $user_id , 'type' => 'thumb' , 'size' => $size ) );
			
			// If it's a registered user, link to BuddyPress 
			if ( $comment->user_id > 0 ) : 
				$author = bp_core_get_userlink( $user_id ); ?>
                <a class="discussion-avatar" href="<?php echo bp_core_get_user_domain( $user_id ); ?>" title="View <?php echo $comment->comment_author; ?>'s Profile"><?php echo $avatar->avatar; ?></a><?php 
				
			// Otherwise, get their guest info 
			else : 
				$author = $comment->comment_author; ?>
                <a class="discussion-avatar"><?php echo $avatar; ?></a><?php
				
			// Build the recent comment excerpt  
			endif; ?>
				<div class="recent-discussion-content">
					<span class="recent-discussion-title">
						<?php echo $author; ?> commented on <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" title="View Comment"><?php echo get_the_title($comment->comment_post_ID); ?></a>
					</span>
					<?php echo $comment_time; ?>
				</div> 
            </li>
        <?php ob_end_flush(); endforeach; ?>
		</ul>
	</div><!-- .recent-comments-widget -->
	<?php endif; 
}


/** 
 * Display recent forum posts
 * @since 0.1
 */
function recent_forums_widget( $args = '' ) {

	// This widget depends on BuddyPress, so bail if it's not active 
	if ( !function_exists( 'bp_version' ) )
		return false;

	// Defaults and arguments 
	$defaults = array (
		'number'		=> 3,
		'post_types'	=> array( 'topic' , 'reply' ), 
		'size'			=> 50,
		);			
	$args = wp_parse_args( $args , $defaults );
	extract( $args, EXTR_SKIP );

	// Submit the query 
	$widget_query = new WP_Query( array(
		'post_type'      => $args['post_types'],
		'post_status'    => join( ',', array( bbp_get_public_status_id(), bbp_get_closed_status_id() ) ),
		'posts_per_page' => $args['number']
	) );

	
	// Get replies and display them 
	if ( $widget_query->have_posts() ) : ?>
	<div class="forum-replies-widget widget">
		<header class="widget-header"><h3 class="widget-title">Forum Activity</h3></header>
		<ul class="recent-discussion-list">
		
		<?php $post_alt = 1;
		while ( $widget_query->have_posts() ) : $widget_query->the_post(); 

			// Get some info about the post 
			$post_id		= $widget_query->post->ID;
			$author_id 		= $widget_query->post->post_author;
			$post_type		= $widget_query->post->post_type;	
			$post_title 	= ucfirst( $widget_query->post->post_title );
			$author_name	= bp_core_get_user_displayname( $author_id );
			$author_link 	= bp_core_get_userlink( $author_id );
			$avatar			= new Apoc_Avatar( array( 'user_id' => $author_id , 'type' => 'thumb' , 'size' => $size ) );
			
			// Get the post time, and make it relative 
			$post_time 		= get_the_time( 'U' , false );
			$current_time	= strtotime( current_time( 'mysql', false ) );
			$post_time		=  bp_core_time_since( $post_time , $current_time );	
			$post_time 		= '<time class="recent-discussion-time" datetime="' . get_the_time( 'Y-m-d\TH:i' ) . '">' . $post_time . '</time>';	

			// Handle topics and replies differently 
			if ( $post_type == 'reply' ) {
				$post_link  = '<a href="' . esc_url( bbp_get_reply_url( $post_id ) ) . '">' . bbp_get_reply_topic_title( $post_id ) . '</a>';
				$author = $author_link . ' replied to the topic:';
			} else {
				$post_link  = '<a class="bbp-widget-replies-title" href="' . esc_url( $widget_query->post->guid ) . '">' . $post_title . '</a>';
				$author = $author_link . ' created a new topic:';
			}			
		
			// Count evens and odds 
			$class = ( $post_alt % 2 ) ? 'odd' : 'even' ;
			$post_alt++;

			// Display the topics  
            echo '<li class="recent-discussion '.$class.'">'; ?>
				<a class="discussion-avatar" href="<?php echo bp_core_get_user_domain( $author_id ); ?>" title="View <?php echo $author_name; ?>'s Profile"><?php echo $avatar->avatar; ?></a>
				<div class="recent-discussion-content">
					<span class="recent-discussion-title">
						<?php echo $author . ' ' . $post_link; ?>
					</span>
					<?php echo $post_time; ?>
				</div> 
            </li>		
			<?php endwhile; ?>
		</ul>
	</div>
<?php endif;			
}


/** 
 * Display current online members in the community sidebar
 * @version 1.0.0
 */
function community_online_widget() { 

	// Set up some parameters 
	$online_number = 10;
	
	// Display the widget  ?>
	<div class="community-online-widget widget">
		<header class="widget-header"><h3 class="widget-title">Online Members</h3></header>
		
		<?php // Loop online members 
		bp_has_members( 'type=online&per_page='.$online_number.'&user_id=0' );
		global $members_template;
		$online_total = $members_template->total_member_count; 
	
		// Display online members
		if ( $online_total > 0 ) :
			if ( $online_total == 1 ) : ?>
				<p class="whos-online-total">There is currently <span class="activity-count"><?php echo $online_total; ?></span> member online:</p>
			<?php else : ?>
				<p class="whos-online-total">There are currently <span class="activity-count"><?php echo $online_total; ?></span> members online:</p>
			<?php endif; ?>
			
			<ul class="whos-online-list">
			<?php $count = 1;
			while ( bp_members() ) : bp_the_member(); 
				$class = ( $count % 2 ) ? 'odd' : 'even';?>
				<li class="whos-online-member <?php echo $class; ?>">
					<a href="<?php echo bp_get_member_permalink(); ?>" title="<?php echo bp_get_member_name(); ?>"><?php echo bp_get_member_name(); ?></a>
				</li>
				<?php $count++;
			endwhile; ?>
			</ul>	
		<?php else : ?>
			<p class="whos-online-total">There are no members currently online.</p>
		<?php endif; ?>
	</div><!-- .community-online-widget --><?php
}

/** 
 * Display current Tamriel Foundry faction statistics
 * @version 1.0.0
 */
function community_stat_counter() {
	
	// Try to retrieve counts from cache
	$counts = wp_cache_get( 'stat_counts' , 'apoc' );
	if ( false === $counts ) {
		
		// Get Faction Counts 
		$counts = new stdClass();
		$counts->total		= bp_core_get_total_member_count();
		$counts->aldmeri	= max( count_users_by_meta( 'faction' , 'aldmeri' ) 	, 1 );
		$counts->daggerfall = max( count_users_by_meta( 'faction' , 'daggerfall' ) 	, 1 );
		$counts->ebonheart	= max( count_users_by_meta( 'faction' , 'ebonheart' ) 	, 1 );
		$counts->declared	= $counts->aldmeri + $counts->daggerfall + $counts->ebonheart;
		// Cache them
		wp_cache_add( 'stat_counts' , $counts , 'apoc' , 600 );
	}
	
	// Compute Banner Heights - normalize max to 250px 
	$largest = max( $counts->aldmeri , $counts->daggerfall , $counts->ebonheart );
	$aheight = round( ( $counts->aldmeri / $largest ) * 200 ) + 50;
	$dheight = round( ( $counts->daggerfall / $largest ) * 200 ) + 50;
	$eheight = round( ( $counts->ebonheart / $largest ) * 200 ) + 50;
	
	// Get the group URL stub
	$groups = SITEURL . '/groups/';
	?>
	
	<div id="stat-counter" class="widget stat-counter">
		<header class="widget-header"><h3 class="widget-title">Foundry Stats</h3></header>
		<p class="stat-counter-total">Total Champions: <?php echo number_format( $counts->total , 0 , '' , ',' ); ?></p>
		<div class="banner-top aldmeri" style="height:<?php echo $aheight; ?>px">
			<div class="banner-bottom aldmeri">
				<a class="banner-count" href="<?php echo $groups; ?>aldmeri-dominion" title="Aldmeri Dominion - <?php echo round( $counts->aldmeri * 100 / $counts->declared ); ?>%"><?php echo number_format( $counts->aldmeri , 0 , '' , ',' ); ?></a>
			</div>
		</div>
		<div class="banner-top daggerfall" style="height:<?php echo $dheight; ?>px">
			<div class="banner-bottom daggerfall">
				<a class="banner-count" href="<?php echo $groups; ?>daggerfall-covenant" title="Daggerfall Covenant - <?php echo round( $counts->daggerfall * 100 / $counts->declared ); ?>%"><?php echo number_format( $counts->daggerfall , 0 , '' , ',' ); ?></a>
			</div>
		</div>
		<div class="banner-top ebonheart" style="height:<?php echo $eheight; ?>px">
			<div class="banner-bottom ebonheart">
				<a class="banner-count" href="<?php echo $groups; ?>ebonheart-pact" title="Ebonheart Pact - <?php echo round( $counts->ebonheart * 100 / $counts->declared ); ?>%"><?php echo number_format( $counts->ebonheart , 0 , '' , ',' ); ?></a>
			</div>
		</div>
	</div><?php
}

/** 
 * Display featured guild box
 * @since 0.1
 */
function featured_guild_box() {

	// Pick a random guild 
	bp_has_groups( 'type=random&max=1&populate_extras=0' );
	while ( bp_groups() ) : bp_the_group(); 
	
	// Get the apoc group object
	$group = new Apoc_Group( bp_get_group_id() , 'widget' ); ?>
	<div class="widget featured-guild-widget">
		<header class="widget-header"><h3 class="widget-title">Featured Guild</h3></header>
		<div id="featured-guild">
			<?php echo $group->block; ?>
		</div>
	</div>
	<?php endwhile;
}


/* 
 * Show the PayPal donate button
 */
function paypal_donate_box() {

	// Get the user's name
	$user		= apocrypha()->user;
	$user_id	= $user->ID;
	$name		= ( $user_id == 0 ) ? 'Anonymous' : $user->data->display_name;

	// Echo the HTML ?>
	<div class="widget paypal-donate-widget">
		<header class="widget-header"><h3 class="widget-title">Support Us!</h3></header>
		<p>Donate to help fund Tamriel Foundry and support further community improvements.</p>
		<form id="donation-form" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_donations">
			<input type="hidden" name="business" value="admin@tamrielfoundry.com">
			<input type="hidden" name="lc" value="US">
			<input type="hidden" name="item_name" value="Tamriel Foundry">
			<input type="hidden" name="item_number" value="Donation From <?php echo $name; ?> (<?php echo $user_id; ?>)">
			<input type="hidden" name="currency_code" value="USD">
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
			<input type="image" id="donate-image" src="<?php echo THEME_URI . '/images/icons/donate.png'; ?>" border="0" name="submit" width="200" height="50" alt="Donate to support Tamriel Foundry!">
		</form>
	</div>
	<?php
}

/* 
 * Load Twitch Streams
 * Use AJAX after page-load to reduce delay
 */
function twitch_streams_widget() {

	// Try to retrieve a stream from cache to avoid external calls
	$stream = wp_cache_get( 'featured_stream' , 'apoc' );
	if ( false === $stream ) {
	
		// The list of valid streamers
		$streamers = array( 
			'Phazius' 			=> 'phazius', 
			'Atropos' 			=> 'atropos_nyx', 
			'Nybling' 			=> 'nybling',
			'Erlexx' 			=> 'erlexx', 
			'Deagen'			=> 'deagen',
			'typeRkrim'			=> 'typerkrim',
			'Rudrias'			=> 'rudrias',
			'Moowi'				=> 'moomoney',
		);
		
		// Shuffle the array
		$keys 	= array_keys( $streamers );
		shuffle( $keys );
		$new	= array();
		foreach($keys as $key) {
			$new[$key] = $streamers[$key];
		}
		$streamers = $new;
		
		// Loop through streamers in a random order, and pull their info
		foreach ( $streamers as $name => $user ) {
		
			// Get the Twitch data
			$twitch_response 	= json_decode( @file_get_contents( "https://api.twitch.tv/kraken/streams/" . $user ) );
			$is_online			= isset( $twitch_response->stream->game );
			$game				= $is_online ? $twitch_response->stream->game : false;
			
			// Are they online and playing TESO?
			if ( $is_online && $game == "The Elder Scrolls Online" ) break;
		}
		
		// If we have an online user, report it
		$url				= 'http://twitch.tv/'.$user;
		$viewers			= $is_online ? intval( $twitch_response->stream->viewers ) . " Viewers" : "Offline";
		$icon				= $is_online ? '<i class="icon-ok"></i>' : '<i class="icon-remove"></i>';
		$status				= $is_online ? 'online' : 'offline';
		$stream				= '<a class="twitch-stream-name '.$status.'" href="'.$url.'" target="_blank">'.$name.' '.$icon.'</a><span class="activity-count twitch-stream-count">'.$viewers.'</span>';
		
		// Set it to the cache
		wp_cache_set( 'featured_stream' , $stream , 'apoc' , 180 ); 
	} 
	
	// Display the widget ?>
	<div class="widget featured-stream-widget">
		<header class="widget-header"><h3 class="widget-title">Featured Stream</h3></header>
		<div id="featured-stream">
			<?php echo $stream; ?>
		</div>
	</div>
	<?php
}


/* 
 * Show all Entropy Rising member's TwitchTV status
 */
function guild_twitch_streams() {
	$streamers = array( 
		'phazius' 			=> 'phazius', 
		'atropos' 			=> 'atropos_nyx', 
		'nybling' 			=> 'nybling',
		'erlexx' 			=> 'erlexx', 
	);
	$streams = array();
	
	// Check the status of each user's channel
	foreach ( $streamers as $user_name => $streamer ) {
		$twitch_response = @file_get_contents("http://api.justin.tv/api/stream/list.json?channel=" . $streamer , 0 , null , null );
		$stream_array = json_decode( $twitch_response , true );
		$streams[$user_name] = array_pop( $stream_array );
	}
	
	echo '<ul id="er-twitch-streams">';
	// Loop through each channel and display some info
	foreach ( $streams as $user_name => $stream ) {
		if ( empty( $streams[$user_name] ) ) {
			echo '<li class="er-streamer"><a href="http://twitch.tv/'. $streamers[$user_name] . '" title="Visit Channel" target="_blank">' . $user_name . '</a><span class="stream-status offline">OFFLINE</span></li>';
		} else {
			$title = $streams[$user_name]['title'];
			$url = $streams[$user_name]['channel']['channel_url'];
			$count = $streams[$user_name]['stream_count'];
			echo '<li class="er-streamer"><a href="' . $url . '" title="Now Playing: ' . $title . '" target="_blank">' . $user_name . '</a><span class="stream-viewers">' . $count . '</span><span class="stream-status online">ONLINE</span></li>';
		}
	}
	echo '</ul>';
}
	