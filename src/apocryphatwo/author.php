<?php 
/**
 * Apocrypha Theme Author Archive Template
 * Andrew Clayton
 * Version 1.0.0
 * 8-8-2013
 */

// Get the queried author
$user_id	= get_query_var( 'author' );
$username 	= bp_core_get_user_displayname( $user_id );
?>

<?php get_header(); ?>

	<div id="content" role="main">
			
		<header id="archive-header">
			<h1 id="archive-title" class="double-border bottom"><?php printf( 'Articles By %s' , $username ); ?></h1>
			<div id="archive-author-info">
				<div id="archive-author-block" class="reply-author">
					<?php apoc_member_block( $user_id , $context = 'member' , $avatar = 'thumb' ); ?>
				</div>
				<div id="archive-author-bio" class="reply-content">
					<?php the_author_meta( 'description', $userid ); ?>
					test
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
				<?php loop_pagination(); ?>
			</nav>
		</div>
		
	</div><!-- #content -->
	
	<?php apoc_primary_sidebar(); ?>
<?php get_footer(); ?>