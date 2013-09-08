<?php 
/**
 * Apocrypha Theme Profile Friends Template
 * Andrew Clayton
 * Version 1.0.0
 * 9-7-2013
 */
?>

<?php do_action( 'template_notices' ); ?>
<nav class="directory-subheader no-ajax" id="subnav" >
	<ul id="profile-tabs" class="tabs" role="navigation">
		<?php bp_get_options_nav(); ?>
	</ul>
	<div id="members-order-select" class="filter">
		<select id="members-order-by">
			<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
			<option value="newest"><?php _e( 'Newest Registered', 'buddypress' ); ?></option>
			<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>
		</select>
	</div>
</nav><!-- #subnav -->

<div id="members-dir-list" class="members dir-list">
<?php if ( bp_is_current_action( 'requests' ) ) : ?>
	<?php locate_template( array( 'members/single/friends/requests.php' ), true ); ?>
<?php else : ?>
	<?php locate_template( array( 'members/members-loop.php' ), true ); ?>
<?php endif; ?>
</div><!-- #members-dir-list -->