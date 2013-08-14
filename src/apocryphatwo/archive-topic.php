<?php 
/**
 * Apocrypha Theme Topics Archive
 * Andrew Clayton
 * Version 1.0
 * 8-12-2013
 */
?>

<?php get_header(); ?>
	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<header id="forum-header" class="entry-header <?php page_header_class(); ?>">
			<h1 class="entry-title">Recent Topics</h1>
			<p class="entry-byline">Browse a chronological list of the most recently updated forum topics on Tamriel Foundry.</p>
		</header>	
	
		<div id="forums">

			<?php do_action( 'bbp_template_notices' ); ?>
			
			<?php if ( bbp_has_topics( ) ) : ?>
			
				<?php bbp_get_template_part( 'loop',       'topics'    ); ?>
				
				<nav class="pagination forum-pagination ajaxed" data-type="topics" data-id="0">
					<div class="pagination-count">
						<?php bbp_forum_pagination_count(); ?>
					</div>
					<div class="pagination-links">
						<?php bbp_forum_pagination_links(); ?>
					</div>
				</nav>	
				
			<?php else : ?>
				<p class="notice warning">Sorry, no topics were found here.</p>
			<?php endif; ?>
					
		</div><!-- #forums -->	
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>