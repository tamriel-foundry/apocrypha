<?php 
/**
 * Apocrypha Theme Profile Forum Subscriptions
 * Andrew Clayton
 * Version 1.0.0
 * 9-7-2013
 */
?>

<?php if ( bbp_get_user_subscriptions() ) : ?>
	
	<?php bbp_get_template_part( 'loop',       'topics' ); ?>
	
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