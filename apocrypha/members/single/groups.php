<?php 
/**
 * Apocrypha Theme Profile Guilds Template
 * Andrew Clayton
 * Version 1.0.0
 * 9-19-2013
 */
?>

<nav class="directory-subheader no-ajax" id="subnav" >
	<ul id="profile-tabs" class="tabs" role="navigation">
		<?php bp_get_options_nav(); ?>
	</ul>
	<?php if ( !bp_is_current_action( 'invites' ) ) : ?>
	<div id="groups-order-select" class="filter">
		<select id="groups-order-by">
			<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
			<option value="popular"><?php _e( 'Most Members', 'buddypress' ); ?></option>
			<option value="newest"><?php _e( 'Newly Created', 'buddypress' ); ?></option>
			<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>
		</select>
	</div>
	<?php endif; ?>
</nav><!-- #subnav -->

<div id="groups-dir-list" class="groups dir-list">
<?php if ( bp_is_current_action( 'invites' ) ) : ?>
	<?php locate_template( array( 'members/single/groups/invites.php' ), true ); ?>
<?php else : ?>
	<?php locate_template( array( 'groups/groups-loop.php' ), true ); ?>
<?php endif; ?>
</div><!-- #groups-dir-list -->