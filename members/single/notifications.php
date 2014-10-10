<?php 
/**
 * Apocrypha Theme Profile Notifications Template
 * Andrew Clayton
 * Version 1.0.0
 * 1-4-2014
 */
?>

<nav class="directory-subheader no-ajax" id="subnav" >
	<ul id="profile-tabs" class="tabs" role="navigation">
		<?php bp_get_options_nav(); ?>
	</ul>
</nav><!-- #subnav -->

<div id="notifications-dir-list" class="notifications dir-list">
<?php if ( bp_is_current_action( 'unread' ) ) : ?>
	<?php bp_get_template_part( 'members/single/notifications/unread' ); ?>
<?php else : ?>
	<?php bp_get_template_part( 'members/single/notifications/read' ); ?>
<?php endif; ?>
</div><!-- #notifications-dir-list -->