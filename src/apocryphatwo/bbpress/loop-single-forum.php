<?php 
/**
 * Apocrypha Theme Single Forum Loop
 * Andrew Clayton
 * Version 1.0.0
 * 8-10-2013
 */
?>
 
<header class="forum-header">
	<div class="forum-content"><h2><?php bbp_forum_title(); ?></h2></div>
	<div class="forum-count">Topics</div>
	<div class="forum-freshness">Latest Post</div>
</header>
 
<?php // Single parent forum with no children
if ( !bbp_get_forum_subforum_count() ) : ?>
<ol id="forum-<?php bbp_forum_id(); ?>" class="forum single <?php bbp_forum_status(); ?>">
	<li class="single-forum">
		<div class="forum-content">
			<h3 class="forum-title"><a href="<?php bbp_forum_permalink(); ?>" title="Browse <?php bbp_forum_title(); ?>"><?php bbp_forum_title(); ?></a></h3>
			<p class="forum-description"><?php the_content(); ?></p>
		</div>

		<div class="forum-count">
			<span class="topic-count">COUNT</span></p>
		</div>

		<div class="forum-freshness">
			<a class="freshest-topic-title" href="<?php bbp_forum_last_reply_url(); ?>" title="<?php bbp_forum_last_topic_title(); ?>"><?php bbp_forum_last_topic_title(); ?></a>
			<div class="freshest-topic-meta">
				<span class="freshest-author">By: 
				<?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id(), 'type' => 'name' ) ); ?>
				<?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id(), 'type' => 'avatar' , 'size' => 20 ) ); ?>
				</span>
				<span class="freshness-time"><?php bbp_forum_last_active_time(); ?></span>
			</div>
		</div>
	</li>
</ol><!-- #forum-<?php bbp_forum_id(); ?> -->
	
<?php // A categorical parent with child forums
else : ?>
<ol id="forum-<?php bbp_forum_id(); ?>" class="forum category <?php bbp_forum_status(); ?>">
	<?php apoc_list_subforums(); ?>
</ol>
<?php endif; ?>