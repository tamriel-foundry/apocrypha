<?php 
/**
 * Apocrypha Theme Profile Forum Favorites
 * Andrew Clayton
 * Version 1.0.0
 * 9-7-2013
 */
?>

<?php if ( bbp_get_user_favorites() ) : ?>
	
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
	<p class="no-results"><?php bbp_is_user_home() ? _e( 'You currently have no favorite topics.', 'bbpress' ) : _e( 'This user has no favorite topics.', 'bbpress' ); ?></p>
<?php endif; ?>