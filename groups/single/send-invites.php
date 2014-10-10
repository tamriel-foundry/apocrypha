<?php 
/**
 * Apocrypha Theme Group Send Invite Component
 * Andrew Clayton
 * Version 1.0.0
 * 9-28-2013
 */

// Retrieve the group
global $guild;
?>

<?php // Only show the form to people who have friends
if ( bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>	
<form action="<?php bp_group_send_invite_form_action(); ?>" method="post" id="send-invite-form" class="standard-form" role="main">

	<div class="instructions">
		<h3 class="double-border bottom">Invite Friends to Join This <?php echo ucfirst( $guild->type ); ?></h3>
		<ul>
			<li>You may invite friends to participate in this guild.</li>
			<li>To directly invite new members to join they must first be on your friends list.</li>
		</ul>
	</div>
	
	<div id="invite-list" class="group-invites">
		<h3 class="double-border bottom">Your Friends</h3>
		<ul id="group-invite-list">
			<?php apoc_group_invite_friend_list(); ?>
		</ul>
		<?php wp_nonce_field( 'groups_invite_uninvite_user', '_wpnonce_invite_uninvite_user' ); ?>
	</div>
	
	<div id="invited-list" class="group-invites">
		<h3 class="double-border bottom">Selected Friends</h3>
		<?php /* The ID 'friend-list' is important for AJAX support. */ ?>
		<ul id="friend-list" class="directory-list">
		<?php if ( bp_group_has_invites() ) : ?>
			<?php while ( bp_group_invites() ) : bp_group_the_invite(); ?>
				<li id="<?php bp_group_invite_item_id(); ?>" class="member directory-entry">
					<?php global $invites_template;
					$userid = $invites_template->invite->user->id;
					$user = new Apoc_User( $userid , 'directory' );	?>
					<div class="directory-member">
						<?php echo $user->block; ?>
					</div>
					<div class="directory-content">
						<span class="activity"><?php bp_group_invite_user_last_active(); ?></span>
						<div class="actions">
							<a class="button remove" href="<?php bp_group_invite_user_remove_invite_url(); ?>" id="<?php bp_group_invite_item_id(); ?>"><i class="icon-remove"></i>Remove Invite</a>
							<?php do_action( 'bp_group_send_invites_item_action' ); ?>
						</div>
						<?php if ( $user->status['content'] ) : ?>
						<blockquote class="user-status">
							<p><?php echo $user->status['content']; ?></p>
						</blockquote>
						<?php endif; ?>
					</div>
				</li>
			<?php endwhile; ?>
		<?php endif; ?>
		</ul>
	</div>
	
	<div id="group-invite-submit" class="submit">
		<button type="submit" name="submit" id="submit">
			<i class="icon-envelope-alt"></i>Send Invites
		</button>
	</div>
	<?php wp_nonce_field( 'groups_send_invites', '_wpnonce_send_invites'); ?>	
	<input type="hidden" name="group_id" id="group_id" value="<?php bp_group_id(); ?>" />
</form><!-- #send-invite-form -->

<?php // Otherwise, tell them to get friends
else: ?>
<div class="instructions">
	<h3 class="double-border bottom">Invite Friends to Join This <?php echo ucfirst( $guild->type ); ?></h3>
	<p><?php _e( 'Once you have built up friend connections you will be able to invite others to your group. You can send invites any time in the future by selecting the "Send Invites" option when viewing your new group.', 'buddypress' ); ?></p>
</div>
<?php endif; ?>