<?php 
/**
 * Apocrypha Theme Single Forum Content
 * Andrew Clayton
 * Version 1.0
 * 8-10-2013
 */
?>

<header class="forum-header topics">
	<div class="forum-content"><h2>Topic</h2></div>
	<div class="forum-count">Posts</div>
	<div class="forum-freshness">Latest Post</div>
</header>

<ol id="forum-<?php bbp_forum_id(); ?>" class="forum single-forum">
	<?php while ( bbp_topics() ) : bbp_the_topic(); ?>
		<?php bbp_get_template_part( 'loop', 'single-topic' ); ?>
	<?php endwhile; ?>
</ol><!--#forum-<?php bbp_forum_id(); ?> -->