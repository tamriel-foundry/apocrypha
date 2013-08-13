<?php 
/**
 * Apocrypha Single Topic Template
 * Andrew Clayton
 * Version 1.0
 * 8-11-2013
 */
?>

<?php get_header(); // Load the header ?>

	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<?php if ( bbp_user_can_view_forum( array( 'forum_id' => bbp_get_topic_forum_id() ) ) ) : ?>
		
			<?php while ( have_posts() ) : the_post(); ?>
			<header id="forum-header" class="entry-header <?php page_header_class(); ?>">
				<h1 class="entry-title"><?php bbp_topic_title(); ?></h1>
				<?php apoc_topic_description(); ?>
			</header>		

			<div id="forums">
				<?php bbp_get_template_part( 'content', 'single-topic' ); ?>
			</div><!-- #forums -->
			<?php endwhile; ?>
			
			<div id="respond" class="create-reply">
				<?php bbp_get_template_part( 'form', 'reply' ); ?>
			</div><!-- #respond -->

		<?php elseif ( bbp_is_forum_private( bbp_get_topic_forum_id(), false ) ) : ?>
			<p class="error">You do not have permission to view topics in this forum.</p>
		<?php endif; ?>
		
	</div><!-- #content -->
<?php get_footer(); ?>