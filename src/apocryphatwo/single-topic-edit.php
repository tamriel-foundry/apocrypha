<?php 
/**
 * Apocrypha Theme Single Topic Edit
 * Andrew Clayton
 * Version 1.0
 * 8-13-2013
 */
?>

<?php get_header(); ?>

	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<?php while ( have_posts() ) : the_post(); ?>
		<header id="forum-header" class="entry-header <?php page_header_class(); ?>">
			<h1 class="entry-title">Edit Topic: <?php bbp_topic_title(); ?></h1>
			<?php apoc_topic_description(); ?>
		</header>
		
		<div id="respond" class="edit-topic">
			<?php bbp_get_template_part( 'form', 'topic' ); ?>
		</div><!-- #respond -->		
		<?php endwhile; ?>
		
	</div><!-- #content -->
<?php get_footer(); ?>