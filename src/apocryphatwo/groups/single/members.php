<?php 
/**
 * Apocrypha Theme Guild Members Component
 * Andrew Clayton
 * Version 1.0.0
 * 9-28-2013
 */
?>

<nav class="directory-subheader no-ajax" id="subnav" >
	<ul id="profile-tabs" class="tabs" role="navigation">
		<li class="current"><span>Guild Members</span></li>
		<?php if ( bp_group_is_admin() || bp_group_is_mod() ) : ?>
			<li><a href="<?php bp_group_permalink(); ?>admin/manage-members/" title="Manage group members">Roster Management</a></li>
		<?php endif; ?>
	</ul>
</nav><!-- #subnav -->

<div id="members-dir-list" class="members dir-list">
	<?php locate_template( array( 'members/members-loop.php' ), true ); ?>
</div><!-- #members-dir-list -->		