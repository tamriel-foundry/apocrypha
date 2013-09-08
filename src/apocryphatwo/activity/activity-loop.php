<?php 
/**
 * Apocrypha Theme Activity Loop
 * Andrew Clayton
 * Version 1.0.0
 * 9-8-2013
 */
?>

<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ) : ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>
	<ul id="activity-stream" class="directory-list activity-list" role="main">
	<?php endif; ?>

	<?php while ( bp_activities() ) : bp_the_activity(); ?>
		<?php locate_template( array( 'activity/entry.php' ), true, false ); ?>
	<?php endwhile; ?>
	
	<?php if ( bp_activity_has_more_items() ) : ?>
		<li class="load-more">
			<a class="button" href="#more"><i class="icon-expand-alt"></i><?php _e( 'Load More', 'buddypress' ); ?></a>
		</li>
	<?php endif; ?>
	
	<?php if ( empty( $_POST['page'] ) ) : ?>
	</ul>
	<?php endif; ?>
	
	<?php /* Show pagination if JS is not enabled, since the "Load More" link will do nothing */ ?>
	<noscript>
		<nav class="pagination activity-pagination">
			<div class="pagination-count"><?php bp_activity_pagination_count(); ?></div>
			<div class="pagination-links"><?php bp_activity_pagination_links(); ?></div>
		</nav>
	</noscript>

<?php else : ?>
<div id="message" class="info">
	<p>Sorry, no activity matches your search.</p>
</div>
<?php endif; ?>

<form action="" name="activity-loop-form" id="activity-loop-form" method="post">
	<?php wp_nonce_field( 'activity_filter', '_wpnonce_activity_filter' ); ?>
</form>