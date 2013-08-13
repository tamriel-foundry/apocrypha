<?php 
/**
 * Apocrypha Theme Single Topic Content
 * Andrew Clayton
 * Version 1.0
 * 8-11-2013
 */
?>


<?php // Private Topics
if ( post_password_required() ) : ?>
	<?php bbp_get_template_part( 'form', 'protected' ); ?>
	
	
<?php // Visible Topics
elseif ( bbp_has_replies() ) : ?>

	<?php // bbp_topic_tag_list(); ?>
		
	<?php bbp_get_template_part( 'loop', 'replies' ); ?>
	
	<?php if ( bbp_get_topic_pagination_links() ) : ?>
	<nav class="pagination forum-pagination">
		<div class="pagination-count">
			<?php bbp_topic_pagination_count(); ?>
		</div>
		<div class="pagination-links">
			<?php bbp_topic_pagination_links(); ?>
		</div>
	</nav>
	<?php endif; ?>
<?php endif; ?>