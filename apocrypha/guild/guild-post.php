<?php 
/**
 * Apocrypha Theme Entropy Rising Guild Posts and Pages Template
 * Template Name: Guild Page
 * Post Template: Guild Post
 * Andrew Clayton
 * Version 1.0
 * 2-10-2013
 */
?>

<?php entropy_rising_header(); ?>
	
	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" class="<?php apoc_entry_class(); ?>">
		
			<header class="entry-header">
				<h1 class="entry-title"><?php entry_header_title( false ); ?></h1>
				<p class="entry-byline"><?php entry_header_description(); ?></p>
			</header>
			
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
			
		</div><!-- #post-<?php the_ID(); ?> -->
		<?php endwhile; endif; ?>
		
	</div><!-- #content -->
	<?php entropy_rising_sidebar(); // Load the guild sidebar ?>
	<?php if ( 'post' == get_post_type() ) comments_template( '/library/templates/comments.php', true ); ?>		
<?php get_footer(); // Load the footer ?>