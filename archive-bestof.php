<?php
/**
 * Apocrypha Theme Best-Of Template
 * Andrew Clayton
 * Version 1.0.0
 * 8-8-2013
 * Template Name: Best Topics
 */
?>

<?php get_header(); ?>
	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<header id="forum-header" class="entry-header <?php page_header_class(); ?>">
			<h1 class="entry-title">Best Weekly Topics</h1>
			<p class="entry-byline">Browse the top rated forum topics on Tamriel Foundry created in the past week.</p>
			<div class="forum-actions">
				<?php apoc_get_search_form( 'topic' ); ?>
			</div>
		</header>	
	
		<div id="forums">

			<?php do_action( 'bbp_template_notices' ); ?>
			
			<?php if ( bestof_has_topics() ) : ?>
			
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
				<p class="notice warning">Sorry, no topics have been favorited for the week.</p>
			<?php endif; ?>
					
		</div><!-- #forums -->	
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>