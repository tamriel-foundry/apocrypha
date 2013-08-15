<?php 
/**
 * Apocrypha Theme Post Archive Template
 * Andrew Clayton
 * Version 1.0.0
 * 8-8-2013
 */
?>

<?php get_header(); ?>

	<div id="content" role="main">
			
		<header id="archive-header">
			<h1 id="archive-title">Archives</h1>
			<div id="archive-description">Browse our collection of articles.</p>
		</header>
		
		<div id="posts">		
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" class="<?php apoc_entry_class(); ?>">
				
				<header class="entry-header <?php home_header_class(); ?>">
					<h2 class="entry-title"><?php entry_header_title(); ?></h2>
					<p class="entry-byline"><?php entry_header_description(); ?></p>
				</header>
				
				<div class="entry-content">
					<?php get_the_image( array( 'meta_key' => 'Thumbnail', 'size' => 'thumbnail' ) );?>
					<div class="entry-excerpt">
						<?php the_excerpt(); ?>
					</div>
				</div>
				
				<footer class="entry-footer">
					<div class="entry-meta">
						<?php echo get_the_term_list( $post->ID, 'category', 'Posted In: ', ', ', '' ); ?> 
					</div>
					<?php apoc_comments_link(); ?>
				</footer>
				
			</div><!-- #post-<?php the_ID(); ?> -->
			<?php endwhile; endif; ?>
		
			<?php loop_pagination(); ?>
		</div>
		
	</div><!-- #content -->
	<?php apoc_primary_sidebar(); ?>
<?php get_footer(); ?>
