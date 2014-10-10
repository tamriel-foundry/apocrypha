<?php 
/**
 * Apocrypha Theme Group Forum Component
 * Andrew Clayton
 * Version 1.0.0
 * 9-28-2013
 */
?>

<header id="forum-header" class="entry-header <?php page_header_class(); ?>">
	<h1 class="entry-title"><?php bbp_forum_title(); ?></h1>
	<p class="entry-byline"><?php bbp_forum_content(); ?></p>
</header>	

<div id="forums">
	<?php bbp_get_template_part( 'content', 'single-forum' ); ?>
</div><!-- #forums -->

<div id="respond" class="create-topic">
	<?php bbp_get_template_part( 'form', 'topic' ); ?>
</div><!-- #respond -->