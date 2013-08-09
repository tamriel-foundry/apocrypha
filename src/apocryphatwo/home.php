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
		<?php apoc_display_post(); ?>
		<?php endwhile; endif; ?>
	
		<nav class="pagination ajaxed" data-type="home">
			<?php loop_pagination(); ?>
		</nav>
		
	</div><!-- #content -->
	
	<?php apoc_primary_sidebar(); ?>
	
<?php get_footer(); ?>