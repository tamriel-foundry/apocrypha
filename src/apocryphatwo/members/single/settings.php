<?php 
/**
 * Apocrypha Theme User Settings Components
 * Andrew Clayton
 * Version 1.0.0
 * 10-6-2013
 * THIS DOESNT SEEM TO BE USED?
 */
?>

<nav class="directory-subheader no-ajax" id="subnav" >
	<ul id="profile-tabs" class="tabs" role="navigation">
		<?php bp_get_options_nav(); ?>
	</ul>
</nav><!-- #subnav -->

<?php if ( bp_is_current_action( 'notifications' ) ) :
	 locate_template( array( 'members/single/settings/notifications.php' ), true );
elseif ( bp_is_current_action( 'delete-account' ) ) :
	 locate_template( array( 'members/single/settings/delete-account.php' ), true );
elseif ( bp_is_current_action( 'general' ) ) :
	locate_template( array( 'members/single/settings/general.php' ), true ); 
else :
	locate_template( array( 'members/single/plugins.php' ), true ); 
endif; ?>