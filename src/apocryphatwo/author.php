<?php 
/**
 * Apocrypha Theme Author Archive Template
 * Andrew Clayton
 * Version 1.0.0
 * 8-8-2013
 */

// Get the queried author
$user_id	= get_query_var( 'author' );
$author		= new Apoc_User( $user_id );
?>

<?php get_header(); ?>

	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
			
		<header id="archive-header">
			<h1 id="archive-title" class="double-border bottom"><?php printf( 'Articles By %s' , $author->fullname ); ?></h1>
			<div id="archive-author-info">
				<div class="reply-author user-block">
					<?php echo $author->block; ?>
				</div>
				<div id="archive-author-bio" class="reply-content">
					<?php echo $author->bio; ?>
				</div>
			</div>
		</header>
		
		<div id="posts">		
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php apoc_display_post(); ?>
			<?php endwhile; else : ?>
			<div class="warning">Sorry, no posts were found for this author.</div>
			<?php endif; ?>
		
			<nav class="pagination ajaxed" data-type="author" data-id="<?php echo $user_id; ?>">
				<?php apoc_pagination(); ?>
			</nav>
		</div>
		
	</div><!-- #content -->
	
	<?php apoc_primary_sidebar(); ?>
<?php get_footer(); ?>