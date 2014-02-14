<?php 
/**
 * Apocrypha Theme Group Forum Reply Move Component
 * Andrew Clayton
 * Version 1.0.0
 * 9-28-2013
 */
?>

<header id="forum-header" class="entry-header <?php page_header_class(); ?>">
	<h1 class="entry-title">Move: <?php bbp_reply_title(); ?></h1>
	<?php apoc_topic_description(); ?>
</header>

<div id="respond" class="edit-reply">
	<?php bbp_get_template_part( 'form', 'reply-move' ); ?>
</div><!-- #respond -->	