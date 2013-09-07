<?php 
/**
 * Apocrypha Theme Profile All Forum Replies
 * Andrew Clayton
 * Version 1.0
 * 2-2-2013
 */
?>

<?php if ( bbp_get_user_replies_created() ) : ?>
	
	<?php bbp_get_template_part( 'loop',       'replies' ); ?>
	
	<nav class="pagination forum-pagination">
		<div class="pagination-count">
			<?php bbp_topic_pagination_count(); ?>
		</div>
		<div class="pagination-links">
			<?php bbp_topic_pagination_links(); ?>
		</div>
	</nav>
	
<?php else : ?>
	<p class="no-results"><?php bbp_is_user_home() ? _e( 'You have not replied to any topics.', 'bbpress' ) : _e( 'This user has not replied to any topics.', 'bbpress' ); ?></p>
<?php endif; ?>