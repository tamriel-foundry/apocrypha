<?php 
/**
 * Apocrypha Theme Single Reply Edit
 * Andrew Clayton
 * Version 1.0.0
 * 8-17-2013
 */
?>

<?php get_header(); ?>

	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<?php while ( have_posts() ) : the_post(); ?>
		<header id="forum-header" class="entry-header <?php page_header_class(); ?>">
			<h1 class="entry-title">Edit Reply - <?php bbp_topic_title(); ?></h1>
			<?php apoc_topic_description(); ?>
			<div class="forum-actions">
				<?php apoc_get_search_form( 'topic' ); ?>
			</div>
		</header>
		
		<div id="respond" class="edit-reply">
			<?php bbp_get_template_part( 'form', 'reply' ); ?>
		</div><!-- #respond -->		
		<?php endwhile; ?>
		
	</div><!-- #content -->
<?php get_footer(); ?>