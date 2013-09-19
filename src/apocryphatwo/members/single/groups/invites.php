<?php 
/**
 * Apocrypha Theme Profile Guild Invite Template
 * Andrew Clayton
 * Version 1.0.0
 * 9-19-2013
 */
?>

<?php if ( bp_has_groups( 'type=invites&user_id=' . bp_loggedin_user_id() ) ) : ?>
<ul id="group-invite-list" class="directory-list" role="main">
<?php // Loop through all groups
	while ( bp_groups() ) : bp_the_group();
	$group = new Apoc_Group( bp_get_group_id() , 'directory' );	?>
	
	<li class="group directory-entry">
		<div class="directory-member">
			<?php echo $group->block; ?>
		</div>
		
		<div class="directory-content">
			<span class="activity"><?php bp_group_last_active(); ?></span>
			<div class="actions">
				<a class="button accept" href="<?php bp_group_accept_invite_link(); ?>"><i class="icon-ok"></i>Join Guild</a>
				<a class="button reject confirm" href="<?php bp_group_reject_invite_link(); ?>"><i class="icon-remove"></i>Decline Invitation</a>
			</div>
			<div class="guild-description">
				<?php bp_group_description_excerpt(); ?>
			</div>
		</div>
	</li>
	<?php endwhile; ?>
</ul><!-- #groups-list -->

<?php else: ?>
<p class="notice"><?php _e( 'You have no outstanding group invites.', 'buddypress' ); ?></p>
<?php endif;?>