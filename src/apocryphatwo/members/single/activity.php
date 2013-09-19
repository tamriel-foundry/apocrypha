<?php 
/**
 * Apocrypha Theme User Profile Activity Component
 * Andrew Clayton
 * Version 1.0.0
 * 9-19-2013
 */
?>

<?php do_action( 'template_notices' ); ?>
<header class="discussion-header" id="subnav" role="navigation">
	<div class="directory-member">Member</div>
	<div class="directory-content">Current Status
		<div id="activity-filter-select" class="filter">
			<select id="activity-filter-by">
			<option value="-1">All Activity</option>
			<option value="activity_update">Status Updates</option>
			<option value="new_blog_comment">Article Comments</option>						
			<?php do_action( 'bp_activity_filter_options' ); // Topics & Replies ?>
			<option value="friendship_accepted,friendship_created">Friendships</option>
			<option value="joined_group">Guild Memberships</option>
			</select>
		</div>
	</div>
</header><!-- #subnav -->

<div id="activity-directory" class="activity" role="main">
	<?php locate_template( array( 'activity/activity-loop.php' ), true ); ?>
</div><!-- #activity-directory -->