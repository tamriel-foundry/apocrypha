<?php
/**
 * Apocrypha Theme Custom Widgets
 * Andrew Clayton
 * Version 1.0
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
			
			// If it's a registered user, link to BuddyPress 
			if ( $comment->user_id > 0 ) : 
				$avatar	= apoc_fetch_avatar( $comment->user_id , 'thumb' , $size );
				$author = bp_core_get_userlink( $comment->user_id ); ?>
                <a class="discussion-avatar" href="<?php echo bp_core_get_user_domain( $comment->user_id ); ?>" title="View <?php echo $comment->comment_author; ?>'s Profile"><?php echo $avatar; ?></a><?php 
				
			// Otherwise, get their guest info 
			else : 
				$avatar = apoc_guest_avatar( 'thumb' , $size );
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
		'number'		=> '3',
		'post_types'	=> array( 'topic' , 'reply' ), 
		'size'		=> 50,
		);			
	$args = wp_parse_args( $args , $defaults );
	extract( $args, EXTR_SKIP );

	// Submit the query 
	$widget_query = new WP_Query( array(
		'post_type'      => $args['post_types'],
		'post_status'    => join( ',', array( bbp_get_public_status_id(), bbp_get_closed_status_id() ) ),
		'posts_per_page' => $args['number'],
		'meta_query'     => array( bbp_exclude_forum_ids( 'meta_query' ) )
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
			$avatar			= apoc_fetch_avatar( $author_id , 'thumb' , $size );
			
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
				<a class="discussion-avatar" href="<?php echo bp_core_get_user_domain( $author_id ); ?>" title="View <?php echo $author_name; ?>'s Profile"><?php echo $avatar; ?></a>
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
 * @since 1.0
 */
function community_online_widget() { 

	// Set up some parameters 
	$online_number = 10;
	$newest_number = 3; 
	
	// Display the widget  ?>
	<div class="community-online-widget widget">
		<header class="widget-header"><h3 class="widget-title">Online Members</h3></header>
		
		<?php // Loop online members 
		bp_has_members( 'type=online&per_page='.$online_number.'&user_id=0' );
		global $members_template;
		$online_total = $members_template->total_member_count; 
		
		// Display online members  ?>
		<div id="whos-online-block">
		<?php if ( $online_total == 0 ) : ?>
			<p class="whos-online-total">There are no members currently online:</p>
		<?php elseif ( $online_total == 1 ) : ?>
			<p class="whos-online-total">There is currently <span class="activity-count"><?php echo $online_total; ?></span> member online:</p>
		<?php else : ?>
			<p class="whos-online-total">There are currently <span class="activity-count"><?php echo $online_total; ?></span> members online:</p>
		<?php endif; ?>
		
			<ul class="whos-online-list">
			<?php $count = 1;
			while ( bp_members() ) : bp_the_member(); 
			
				// Count evens and odds 
				$class = ( $count % 2 ) ? 'odd' : 'even';?>
				<li class="whos-online-member <?php echo $class; ?>">
					<a href="<?php echo bp_get_member_permalink(); ?>" title="<?php echo bp_get_member_name(); ?>"><?php echo bp_get_member_name(); ?></a>
				</li>
				<?php $count++;
			endwhile; ?>
			</ul>
		</div>
		
		<?php // Loop new members 
		bp_has_members( 'type=newest&max='.$newest_number.'&per_page='.$newest_number.'&user_id=0' );
		global $members_template;
		
		
		// Display newest members  ?>
		<div id="newest-members-block">
			<p class="whos-online-total newest">Please help welcome our newest members:</p>		
			<ul class="whos-online-list">
			<?php $count = 1;
			while ( bp_members() ) : bp_the_member(); 
			
				// Count evens and odds 
				$class = ( $count % 2 ) ? 'even' : 'odd';?>
				<li class="whos-online-member <?php echo $class; ?>">
					<a href="<?php echo bp_get_member_permalink(); ?>" title="<?php echo bp_get_member_name(); ?>"><?php echo bp_get_member_name(); ?></a>
				</li>
				<?php $count++;
			endwhile; ?>
			</ul>
		</div>
		
	</div><!-- .community-online-widget --><?php
}

/** 
 * Display current Tamriel Foundry faction statistics
 * @since 1.0
 */
function community_stat_counter() {
	
	// Get Faction Counts 
	$o = bp_core_get_total_member_count();
	$a = max( count_users_by_meta( 'faction' , 'aldmeri' ) 		, 1 );
	$d = max( count_users_by_meta( 'faction' , 'daggerfall' ) 	, 1 );
	$e = max( count_users_by_meta( 'faction' , 'ebonheart' ) 	, 1 );
	$t = $a + $d + $e;
	
	// Compute Banner Heights - normalize max to 250px 
	$largest = max( $a , $d , $e );
	$aheight = round( ( $a / $largest ) * 200 ) + 50;
	$dheight = round( ( $d / $largest ) * 200 ) + 50;
	$eheight = round( ( $e / $largest ) * 200 ) + 50;
	
	$groups = SITEURL . '/groups/';
	?>
	
	<div id="stat-counter" class="widget stat-counter">
		<header class="widget-header"><h3 class="widget-title">Foundry Stats</h3></header>
		<p class="stat-counter-total">Total Champions: <?php echo number_format( $o , 0 , '' , ',' ); ?></p>
		<div class="banner-top aldmeri" style="height:<?php echo $aheight; ?>px">
			<div class="banner-bottom aldmeri">
				<a class="banner-count" href="<?php echo $groups; ?>aldmeri-dominion" title="Aldmeri Dominion - <?php echo round( $a * 100 / $t ); ?>%"><?php echo number_format( $a , 0 , '' , ',' ); ?></a>
			</div>
		</div>
		<div class="banner-top daggerfall" style="height:<?php echo $dheight; ?>px">
			<div class="banner-bottom daggerfall">
				<a class="banner-count" href="<?php echo $groups; ?>daggerfall-covenant" title="Daggerfall Covenant - <?php echo round( $d * 100 / $t ); ?>%"><?php echo number_format( $d , 0 , '' , ',' ); ?></a>
			</div>
		</div>
		<div class="banner-top ebonheart" style="height:<?php echo $eheight; ?>px">
			<div class="banner-bottom ebonheart">
				<a class="banner-count" href="<?php echo $groups; ?>ebonheart-pact" title="Ebonheart Pact - <?php echo round( $e * 100 / $t ); ?>%"><?php echo number_format( $e , 0 , '' , ',' ); ?></a>
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
	while ( bp_groups() ) : bp_the_group(); ?>
	
	<div class="widget featured-guild-widget">
		<header class="widget-header"><h3 class="widget-title">Featured Guild</h3></header>
		<div id="featured-guild">
			<a id="featured-guild-avatar" href="<?php bp_group_permalink(); ?>">
				<?php bp_group_avatar( $args = array(
					'type' 	=> 'thumb',
					'height'	=> 100,
					'width'		=> 100 )); ?>
			</a>
			<div id="featured-guild-meta">
				<h4 id="featured-guild-name"><a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>"><?php bp_group_name(); ?></a></h4>
				<p id="featured-guild-type"><?php bp_group_type(); ?></p>
				<?php echo get_guild_allegiance( bp_get_group_id() ); ?>
				<p id="featured-guild-count"><?php bp_group_member_count(); ?></p>
			</div>
		</div>	
	</div>
	
	<?php endwhile;
}
	