<?php 
/**
 * Apocrypha Theme Replies Loop
 * Andrew Clayton
 * Version 1.0.0
 * 8-11-2013
 */
?>

<header class="discussion-header">
	<div class="reply-author">Author</div>
	<div class="reply-content">Post</div>
	<div id="subscription-controls">
		<?php bbp_user_subscribe_link( array(
			'subscribe'		=> '<i class="icon-bookmark"></i>Subscribe',
			'unsubscribe'	=> '<i class="icon-remove"></i>Unsubscribe',
			'before'    	=> '',
			'after'     	=> '',
		)); ?>
		<?php bbp_user_favorites_link( array(
			'favorite'		=> '<i class="icon-thumbs-up"></i>This Thread Rocks',
			'favorited'		=> '<i class="icon-thumbs-down"></i>This Got Ugly',
			'before'    	=> '',
			'after'     	=> '',
		)); ?>
	</div>
</header>

<ol id="topic-<?php bbp_topic_id();?>" class="topic single-topic">
	<?php while ( bbp_replies() ) : bbp_the_reply(); ?>
		<?php bbp_get_template_part( 'loop', 'single-reply' ); ?>
	<?php endwhile; ?>
</ol><!-- #topic-<?php bbp_topic_id(); ?> -->