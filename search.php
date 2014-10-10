<?php 
/**
 * Apocrypha Theme Search Template
 * Andrew Clayton
 * Version 1.0
 * 2-6-2013
 */
 
// Get the theme global
$apoc = apocrypha();
?>

<?php get_header(); // Load the header ?>
	
	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
			search results - this *should* go unused by the theme
		
	</div><!-- #content -->
	
	<?php apoc_primary_sidebar(); // Load the community sidebar ?>
<?php get_footer(); // Load the footer ?>