<?php 
/**
 * Apocrypha Theme Group Forum Topic Edit Component
 * Andrew Clayton
 * Version 1.0.0
 * 9-28-2013
 */
?>

<header id="forum-header" class="entry-header <?php page_header_class(); ?>">
	<h1 class="entry-title">Edit: <?php bbp_topic_title(); ?></h1>
	<?php apoc_topic_description(); ?>
</header>

<div id="respond" class="edit-topic">
	<?php bbp_get_template_part( 'form', 'topic' ); ?>
</div><!-- #respond -->	