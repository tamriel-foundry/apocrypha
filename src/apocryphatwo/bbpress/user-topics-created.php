<?php 
/**
 * Apocrypha Theme Profile Forum Topics Created
 * Andrew Clayton
 * Version 1.0.0
 * 9-7-2013
 */
?>

<?php if ( bbp_get_user_topics_started() ) : ?>
	
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
	<p class="no-results"><?php bbp_is_user_home() ? _e( 'You have not created any topics.', 'bbpress' ) : _e( 'This user has not created any topics.', 'bbpress' ); ?></p>
<?php endif; ?>