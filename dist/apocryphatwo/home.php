<?php 
/**
 * Apocrypha Homepage Template
 * Andrew Clayton
 * Version 1.0
 * 8-1-2013
 */
?>

<?php get_header(); ?>

	<div id="showcase-container">
		<div id="showcase">
			<?php get_slideshow( $slideshow = 'showcase' , $number = 5 ); ?>
		</div><!-- #showcase --> 
		
		<div id="showcase-sidebar">
			<?php recent_comments_widget(); ?>
			<?php recent_forums_widget(); ?>
		</div><!-- #showcase-sidebar --> 
	</div><!-- #showcase-container -->
	
	<div id="content" role="main">
		<div id="home-posts-block">
			<header id="home-posts-header">
				<h1 id="home-posts-title">Featured Articles</h1>
			</header>
		</div>
		
	<?php homepage_have_posts(); ?>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" class="<?php display_entry_class(); ?>">
			
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
	</div><!-- #content -->
	
	<?php apoc_primary_sidebar(); ?>
	
<?php get_footer(); ?>