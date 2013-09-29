<?php 
/**
 * Apocrypha Theme Group Profile Template
 * Andrew Clayton
 * Version 1.0.0
 * 9-28-2013
 */
 
// Load the queried group
if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); 
 
// Store the displayed group in a global
global $guild;
$guild = new Apoc_Group( bp_get_current_group_id() , 'profile' );

// Redirect to specific group pages
if ( 'entropy-rising' == bp_get_current_group_slug() ) {
	if ( !bp_group_is_member() ) {
		wp_redirect( SITEURL . '/entropy-rising' , 301 );
	}
}
?>

<?php get_header(); ?>

	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>

		<?php locate_template( array( 'groups/single/group-header.php' 	), true ); ?>
			
		<div id="profile-body">
			
			<?php // Group homepage
			if ( bp_is_group_home() ) :
				locate_template( array( 'groups/single/front.php' 					), true );
			
			// Group is not private or hidden
			elseif ( bp_group_is_visible() ) : 
				if ( bp_is_group_activity() ) : 
					locate_template( array( 'groups/single/activity.php' 			), true );
				elseif	( bp_is_group_members() ) : 
					locate_template( array( 'groups/single/members.php' 			), true );
				elseif	( bp_is_group_invites() ) : 
					locate_template( array( 'groups/single/send-invites.php' 		), true );
				elseif	( bp_is_group_admin_page() ) : 
					locate_template( array( 'groups/single/admin.php' 				), true );
				else : 
					locate_template( array( 'groups/single/plugins.php' 			), true );
				endif;	
			
			// Private or hidden group, must request membership to view
			else :
				if ( bp_is_group_membership_request() ) :
					locate_template( array( 'groups/single/request-membership.php' 	), true );
				else : ?>
					<div id="message" class="notice">
						<p><?php bp_group_status_message(); ?></p>
					</div><?php
				endif;
			endif; ?>
		</div>		
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>
<?php endwhile; endif; ?>