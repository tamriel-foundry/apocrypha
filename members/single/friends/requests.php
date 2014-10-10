<?php 
/**
 * Apocrypha Theme User Profile Friend Requests Template
 * Andrew Clayton
 * Version 1.0.0
 * 9-7-2013
 */
?>

<?php if ( bp_has_members( 'type=alphabetical&include=' . bp_get_friendship_requests() ) ) : ?>
<ul id="friend-request-list" class="directory-list" role="main">

	<?php while ( bp_members() ) : bp_the_member(); 
	$user = new Apoc_User( bp_get_member_user_id() , 'directory' ); ?>
	<li id="friendship-<?php bp_friend_friendship_id(); ?>" class="member directory-entry">

		<div class="directory-member">
			<?php echo $user->block; ?>
		</div>
		
		<div class="directory-content">
			<span class="activity"><?php bp_member_last_active(); ?></span>
			<div class="actions">
				<?php do_action( 'bp_directory_members_actions' ); ?>
				<a class="button accept" href="<?php bp_friend_accept_request_link(); ?>"><i class="icon-ok"></i>Accept Friendship</a>
				<a class="button reject" href="<?php bp_friend_reject_request_link(); ?>"><i class="icon-remove"></i>Decline Friendship</a>
			</div>
			<?php if ( $user->status['content'] ) : ?>
			<blockquote class="user-status">
				<p><?php echo $user->status['content']; ?></p>
			</blockquote>
			<?php endif; ?>
		</div>
	</li>
	<?php endwhile; ?>
</ul>


<?php else: ?>
	<p class="notice no-results"><?php _e( 'You have no pending friendship requests.', 'buddypress' ); ?></p>
<?php endif; ?>