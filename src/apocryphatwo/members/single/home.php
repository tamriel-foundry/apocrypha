<?php 
/**
 * Apocrypha Theme User Profile Template
 * Andrew Clayton
 * Version 1.0.1
 * 1-4-2014
 */
 
// Get the currently displayed user
global $user;
$user 	= new Apoc_User( bp_displayed_user_id() , 'profile' );
?>

<?php get_header(); ?>

	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>		
		
		<div id="profile-body">
			<?php do_action( 'template_notices' ); ?>
			<?php if ( bp_is_user_profile() ) :
			locate_template( array( 'members/single/profile.php'		), true );
			elseif ( bp_is_user_activity() ) :
			locate_template( array( 'members/single/activity.php'		), true ); 
			elseif ( bp_is_user_forums() ) :
			locate_template( array( 'members/single/forums.php'			), true );
			elseif ( bp_is_user_friends() ) :
			locate_template( array( 'members/single/friends.php'		), true );
			elseif ( bp_is_user_groups() ) :
			locate_template( array( 'members/single/groups.php'			), true );
			elseif ( bp_is_user_messages() ) :
			locate_template( array( 'members/single/messages.php'		), true );
			elseif ( bp_is_user_settings() ) :
			locate_template( array( 'members/single/settings.php'		), true );
			elseif ( bp_is_user_notifications() ) :
			locate_template( array( 'members/single/notifications.php' 	), true );
			else :
			locate_template( array( 'members/single/plugins.php'		), true );
			endif; ?>
		</div>		
		
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>