<?php 
/**
 * Apocrypha Theme Single Forum Content
 * Andrew Clayton
 * Version 1.0
 * 8-10-2013
 */
?>

<?php // Forum categories
if ( bbp_get_forum_subforum_count() && bbp_has_forums() ) :?>
	<header class="forum-header">
		<div class="forum-content"><h2><?php bbp_forum_title(); ?></h2></div>
		<div class="forum-count">Posts</div>
		<div class="forum-freshness">Latest Post</div>
	</header>
	<ol id="forum-<?php bbp_forum_id(); ?>" class="forum category <?php bbp_forum_status(); ?>">
		<?php apoc_list_subforums(); ?>
	</ol>
<?php endif; ?>

<?php // Topics within forum
if ( !bbp_is_forum_category() && bbp_has_topics() ) : ?>

	<?php bbp_get_template_part( 'loop', 'topics' ); ?>

	<nav class="forum-pagination pagination">
		<div class="pagination-count">
			<?php // bbp_forum_pagination_count(); ?>
		</div>
		<div class="pagination-links">
			<?php bbp_forum_pagination_links(); ?>
		</div>
	</nav>

<?php elseif ( !bbp_is_forum_category() ) : ?>
	<p class="warning">Sorry, no topics were found here.</p>
<?php endif; ?>