<?php 
/**
 * Apocrypha Theme Group Forum Template
 * Andrew Clayton
 * Version 1.0.0
 * 9-28-2013
 */
 
// Load the queried group
if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); 

// Maybe use a special guild header
if ( 1 == bp_get_group_id() ) :
	entropy_rising_header();
else  :
	get_header(); 
endif; ?>

	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>

		<div id="group-forums">
			<?php do_action( 'template_notices' ); ?>
			<?php // this hook grabs the bbpress group forum extension.
			do_action( 'bp_template_content' ); ?>
		</div><!-- #group-forums -->
					
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>
<?php endwhile; endif; ?>