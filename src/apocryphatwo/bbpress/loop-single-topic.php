<?php 
/**
 * Apocrypha Theme Single Topic Loop
 * Andrew Clayton
 * Version 1.0.0
 * 8-10-2013
 */
?>

<li id="topic-<?php bbp_topic_id(); ?>" <?php bbp_topic_class(); ?>>
	<div class="forum-content">
		<h3 class="forum-title">
			<a class="topic-title-link" href="<?php bbp_topic_permalink(); ?>" title="Read this topic"><?php bbp_topic_title(); ?></a>
			<?php if ( bbp_get_topic_post_count() > 1 ) : ?>
			<a class="last-reply-link" href="<?php bbp_topic_last_reply_url(); ?>" title="Jump to the last reply">&rarr;</a>
			<?php endif; ?>
		</h3>
		
		<p class="forum-description">Started by <?php bbp_topic_author_link( array( 'type' => 'name' ) ); ?>
		<?php if ( !bbp_is_single_forum() || ( bbp_get_topic_forum_id() != bbp_get_forum_id() ) ) : ?>in <a class="topic-location" href="<?php bbp_forum_permalink( bbp_get_topic_forum_id() ); ?>" title="Browse this forum"><?php bbp_forum_title( bbp_get_topic_forum_id() ); ?></a><?php endif; ?>
		<?php bbp_topic_pagination(	$args = array (
			'before' => '<nav class="pagination topic-pagination">',
			'after'  => '</nav>' 
			) ); ?>
		</p>
	</div>
	
	<div class="forum-count">
		<?php bbp_topic_post_count(); ?>
	</div>
	
	<div class="forum-freshness">
		<?php bbp_author_link( array( 'post_id' => bbp_get_topic_last_active_id(), 'type' => 'avatar' , 'size' => 50 ) ); ?>
		<div class="freshest-meta">
			<span class="freshest-author">By <?php bbp_author_link( array( 'post_id' => bbp_get_topic_last_active_id(), 'type' => 'name' ) ); ?></span><br/>
			<span class="freshest-time"><?php bbp_topic_last_active_time(); ?></span>
		</div>
	</div>	
</li>