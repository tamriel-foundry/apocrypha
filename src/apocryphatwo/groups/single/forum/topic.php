<?php 
/**
 * Apocrypha Theme Group Forum Topic Content
 * Andrew Clayton
 * Version 1.0.0
 * 9-28-2013
 */
?>

<header id="forum-header" class="entry-header <?php apoc_topic_header_class(); ?>">
	<h1 class="entry-title"><?php bbp_topic_title(); ?></h1>
	<?php apoc_topic_description(); ?>
</header>
	
<div id="forums">
	<?php bbp_get_template_part( 'content', 'single-topic' ); ?>
</div><!-- #forums -->

<div id="respond" class="create-reply">
	<?php bbp_get_template_part( 'form', 'reply' ); ?>
</div><!-- #respond -->	