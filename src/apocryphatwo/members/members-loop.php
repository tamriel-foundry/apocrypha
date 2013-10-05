<?php 
/**
 * Apocrypha Theme Members Loop
 * Andrew Clayton
 * Version 1.0.0
 * 9-7-2013
 */
?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>
<ul id="members-list" class="directory-list" role="main">
	
	<?php // Loop through all members
	while ( bp_members() ) : bp_the_member(); 
	$user = new Apoc_User( bp_get_member_user_id() , 'directory' );	?>
	<li class="member directory-entry">

		<div class="directory-member">
			<?php echo $user->block; ?>
		</div>
		
		<div class="directory-content">
			<span class="activity"><?php bp_member_last_active(); ?></span>
			<div class="actions">
				<?php do_action( 'bp_directory_members_actions' ); ?>
			</div>
			<?php if ( $user->status['content'] ) : ?>
			<blockquote class="user-status">
				<p><?php echo $user->status['content']; ?></p>
			</blockquote>
			<?php endif; ?>
		</div>
	</li>
<?php endwhile; ?>
</ul><!-- #members-list -->
<?php bp_member_hidden_fields(); ?>

<nav id="pag-bottom" class="pagination directory-pagination">
	<div id="member-dir-count-bottom" class="pagination-count" >
		<?php bp_members_pagination_count(); ?>
	</div>
	<div id="member-dir-pag-bottom" class="pagination-links" >
		<?php bp_members_pagination_links(); ?>
	</div>
</nav>

<?php else: ?>
	<p class="no-results"><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
<?php endif; ?>
