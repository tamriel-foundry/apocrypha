<?php 
/**
 * Apocrypha Theme Single Post Template
 * Andrew Clayton
 * Version 1.0.0
 * 8-6-2013
 */
?>

<?php get_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" class="<?php display_entry_class(); ?>">
		
			<header class="entry-header <?php post_header_class(); ?>">
				<h1 class="entry-title"><?php entry_header_title( false ); ?></h1>
				<p class="entry-byline"><?php entry_header_description(); ?></p>
			</header>
			
			<div class="entry-content">
				<?php the_content(); ?>
			</div>

		</div><!-- #post-<?php the_ID(); ?> -->
		<?php endwhile; endif; ?>
		
	</div><!-- #content -->
	
	<?php apoc_primary_sidebar(); // Load the community sidebar ?>
	
	<?php comments_template( '/library/templates/comments.php', true ); ?>	

<?php get_footer(); // Load the footer ?>
