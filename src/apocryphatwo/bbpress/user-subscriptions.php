<?php 
/**
 * Apocrypha Theme Profile Forum Subscriptions
 * Andrew Clayton
 * Version 1.0.0
 * 9-7-2013
 */
?>

<?php // Forum subscriptions
if ( bbp_get_user_forum_subscriptions() ) : ?>
	<header class="forum-header">
		<div class="forum-content"><h2>Forum</h2></div>
		<div class="forum-count">Topics</div>
		<div class="forum-freshness">Latest Post</div>
	</header>
	<?php while ( bbp_forums() ) : bbp_the_forum(); ?>
		<?php bbp_get_template_part( 'loop', 'single-forum' ); ?>
	<?php endwhile;	?>
<?php else : ?>
	<p class="no-results"><?php bbp_is_user_home() ? _e( 'You are not currently subscribed to any forums.', 'bbpress' ) : _e( 'This user is not currently subscribed to any forums.', 'bbpress' ); ?></p>
<?php endif; ?>


<?php // Topic subscriptions
if ( bbp_get_user_subscriptions() ) : ?>
	<?php bbp_get_template_part( 'loop', 'topics' ); ?>
	
	<nav class="pagination forum-pagination">
		<div class="pagination-count">
			<?php bbp_forum_pagination_count(); ?>
		</div>
		<div class="pagination-links">
			<?php bbp_forum_pagination_links(); ?>
		</div>
	</nav>
	
<?php else : ?>
	<p class="no-results"><?php bbp_is_user_home() ? _e( 'You are not currently subscribed to any topics.', 'bbpress' ) : _e( 'This user is not currently subscribed to any topics.', 'bbpress' ); ?></p>
<?php endif; ?>