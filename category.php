<?php 
/**
 * Apocrypha Theme Category Archive Template
 * Andrew Clayton
 * Version 1.0.0
 * 8-8-2013
 */
 
// Get the queried category
$category_id = get_query_var( 'cat' );
?>

<?php get_header(); ?>

	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
			
		<header id="archive-header">
			<h1 id="archive-title" class="double-border bottom"><?php printf( 'Category Archives: %s' , single_cat_title( '', false ) ); ?></h1>
			<?php if ( category_description() ) : ?>
				<div id="archive-description"><?php echo category_description(); ?></div>
			<?php else : ?>
				<div id="archive-description">Browse archived posts in this category.</div>
			<?php endif; ?>		
		</header>
		
		<div id="posts">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php apoc_display_post(); ?>
			<?php endwhile; else : ?>
			<div class="warning">Sorry, no posts were found for this category.</div>
			<?php endif; ?>
		
			<nav class="pagination ajaxed" data-type="category" data-id="<?php echo $category_id; ?>">
				<?php apoc_pagination(); ?>
			</nav>
		</div>
		
	</div><!-- #content -->
	
	<?php apoc_primary_sidebar(); ?>
<?php get_footer(); ?>

