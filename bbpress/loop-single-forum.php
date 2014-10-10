<?php 
/**
 * Apocrypha Theme Single Forum Loop
 * Andrew Clayton
 * Version 1.0.0
 * 8-10-2013
 */
?>
 
<?php // Single parent forum with no children
if ( !bbp_get_forum_subforum_count() ) :
$forum_id = bbp_get_forum_id(); ?>
<ol id="forum-<?php bbp_forum_id(); ?>" class="forum single <?php bbp_forum_status(); ?>">
	<li class="sub-forum">
		<div class="forum-content">
			<h3 class="forum-title"><a href="<?php bbp_forum_permalink(); ?>" title="Browse <?php bbp_forum_title(); ?>"><?php bbp_forum_title(); ?></a></h3>
			<p class="forum-description"><?php bbp_forum_content(); ?></p>
		</div>

		<div class="forum-count">
			<?php bbp_forum_topic_count( $forum_id , false ); ?>
		</div>

		<div class="forum-freshness">
			<?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id( $forum_id ), 'type' => 'avatar' , 'size' => 50 ) ); ?>
			<div class="freshest-meta">
				<a class="freshest-title" href="<?php echo $link_url; ?>" title="<?php bbp_forum_last_topic_title( $forum_id ); ?>"><?php bbp_forum_last_topic_title( $forum_id ); ?></a>
				<span class="freshest-author">By <?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id( $forum_id ), 'type' => 'name' ) ); ?></span>
				<span class="freshest-time"><?php bbp_forum_last_active_time( $forum_id ); ?></span>
			</div>
		</div>
	</li>
</ol><!-- #forum-<?php bbp_forum_id(); ?> -->
	
<?php // A categorical parent with child forums
else : ?>
<header class="forum-header">
	<div class="forum-content"><h2><?php bbp_forum_title(); ?></h2></div>
	<div class="forum-count">Topics</div>
	<div class="forum-freshness">Latest Post</div>
</header>
<ol id="forum-<?php bbp_forum_id(); ?>" class="forum category <?php bbp_forum_status(); ?>">
	<?php apoc_list_subforums(); ?>
</ol>
<?php endif; ?>