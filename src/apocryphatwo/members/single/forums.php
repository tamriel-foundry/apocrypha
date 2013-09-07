<?php 
/**
 * Apocrypha Theme Profile Forum Activity Template
 * Andrew Clayton
 * Version 1.0.0
 * 9-7-2013
 */
 
// Get the profile user
global $user;
$user 	= new Apoc_User( bp_displayed_user_id() , 'profile' );
?>

<?php get_header(); ?>

	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>
		
		<div id="profile-body">
			<?php do_action( 'template_notices' ); ?>
			<nav class="directory-subheader no-ajax" id="subnav" >
				<ul id="profile-tabs" class="tabs" role="navigation">
					<?php bp_get_options_nav(); ?>
				</ul>
			</nav><!-- #subnav -->
			
			<div id="forums" class="profile-forums" role="main">
			<?php if ( 'topics' == bp_current_action() ) :
				bbp_get_template_part( 'user', 'topics-created' );
			elseif ( 'replies' == bp_current_action() ) :
				bbp_get_template_part( 'user', 'replies-created' );			
			elseif ( 'favorites' == bp_current_action() ) :
				bbp_get_template_part( 'user', 'favorites' );				
			elseif ( 'subscriptions' == bp_current_action() ) :
				bbp_get_template_part( 'user', 'subscriptions' );				
			endif; ?>
			</div>			
			
		</div>
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>