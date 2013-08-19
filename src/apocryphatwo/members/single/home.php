<?php 
/**
 * Apocrypha Theme User Profile Template
 * Andrew Clayton
 * Version 1.0.0
 * 8-18-2013
 */
?>

<?php get_header(); ?>

	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>		
		
		<div id="profile-body">
		This is profile content		
		
		<?php /* This is user profile content
		<?php if ( bp_is_user_profile() ) :
			locate_template( array( 'members/single/profile.php'	), true );
		elseif ( bp_is_user_activity() || !bp_current_component() ) :
			locate_template( array( 'members/single/activity.php'	), true ); 
		elseif ( bp_is_user_forums() ) :
			locate_template( array( 'members/single/forums.php'		), true );
		elseif ( bp_is_user_friends() ) :
			locate_template( array( 'members/single/friends.php'	), true );
		elseif ( bp_is_user_groups() ) :
			locate_template( array( 'members/single/groups.php'		), true );
		elseif ( bp_is_user_messages() ) :
			locate_template( array( 'members/single/messages.php'	), true );
		elseif ( bp_is_user_settings() ) :
			locate_template( array( 'members/single/settings.php'	), true );
		else :
			locate_template( array( 'members/single/plugins.php'	), true );
		endif;  */ ?>
		</div>		
		
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>

<pre style="clear:both; font-size: 16px">
<?php print_r( apocrypha() );?>
</pre>