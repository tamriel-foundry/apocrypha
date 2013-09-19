<?php 
/**
 * Apocrypha Theme Activity Feed Template
 * Andrew Clayton
 * Version 1.0.0
 * 9-8-2013
 */
 
// Get the current user info
$user 		= apocrypha()->user->data;
$user_id	= $user->ID;
$avatar		= new Apoc_Avatar( array ( 'user_id' => $user_id , 'size' => 50 , 'link' => true ) );
?>

<?php get_header(); ?>

	<div id="content" class="no-sidebar">
		<?php apoc_breadcrumbs(); ?>
		
		<header id="directory-header" class="entry-header <?php page_header_class(); ?>">
			<h1 class="entry-title"><?php entry_header_title( false ); ?></h1>
			<p class="entry-byline">A feed of all recent activity happening within the Tamriel Foundry community.</p>
		</header>		
		
		<nav id="directory-nav" role="navigation">
			<ul id="directory-actions" class="directory-tabs">
				<li class="selected" id="members-all"><a href="<?php bp_activity_directory_permalink(); ?>">All Members<span><?php echo bp_get_total_member_count(); ?></span></a></li>
				
				<?php if ( $user_id > 0 ) : ?>
					<li id="activity-friends"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_friends_slug() . '/'; ?>">My Friends<span><?php echo bp_get_total_friend_count( $user_id ); ?></span></a></li>
												
					<?php if ( bp_get_total_group_count_for_user( $user_id ) ) : ?>
					<li id="activity-groups"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_groups_slug() . '/'; ?>" title="Recent activity in my guilds.">My Guilds<span><?php echo bp_get_total_group_count_for_user( $user_id ); ?></span></a></li>
					<?php endif; ?>
					
					<li id="activity-mentions">
						<a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/'; ?>" title="Activity where I'm mentioned">My Mentions
						<?php if ( bp_get_total_mention_count_for_user( $user_id ) ) : ?> 
							<span><?php echo bp_get_total_mention_count_for_user( $user_id ); ?></span>
						<?php endif; ?>
						</a>
					</li>		
				<?php endif; ?>
			</ul>
		</nav><!-- #directory-header -->
		
		<?php if ( $user_id > 0 ) : ?>		
			<div id="activity-status">
				<?php echo $avatar->avatar; ?>		
				<blockquote id="profile-status" class="user-status">
					<p><?php echo '@' . $user->user_nicename . ' &rarr; ' . bp_get_activity_latest_update( $user_id ); ?></p>
					<a class="update-status-button button"><i class="icon-pencil"></i>What's New?</a>
				</blockquote>
			</div>
			<?php locate_template( array( 'activity/post-form.php'), true ); ?>
		<?php endif; ?>
		<?php do_action( 'template_notices' ); ?>	
		
		<header class="discussion-header" id="subnav" role="navigation">
			<div class="directory-member">Member</div>
			<div class="directory-content">Current Status
				<div id="activity-filter-select" class="filter">
					<select id="activity-filter-by">
					<option value="-1">All Activity</option>
					<option value="activity_update">Status Updates</option>
					<option value="new_blog_post">Front-Page Articles</option>
					<option value="new_blog_comment">Article Comments</option>						
					<?php do_action( 'bp_activity_filter_options' ); // Topics & Replies ?>
					<option value="new_member">New Members</option>
					<option value="friendship_accepted,friendship_created">Friendships</option>
					<option value="created_group">New Guilds</option>
					<option value="joined_group">Guild Memberships</option>
					</select>
				</div>
			</div>
		</header><!-- #subnav -->			
			
		<div id="activity-directory" class="activity" role="main">
			<?php locate_template( array( 'activity/activity-loop.php' ), true ); ?>
		</div><!-- #activity-directory -->
				
		</form>
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>