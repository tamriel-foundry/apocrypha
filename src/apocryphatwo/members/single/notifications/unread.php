<?php 
/**
 * Apocrypha Theme Profile Unread Notifications
 * Andrew Clayton
 * Version 1.0.0
 * 1-4-2014
 */
 
// Get the currently displayed user
global $user;
?>

<?php if ( bp_has_notifications() ) : ?>

	<?php bp_get_template_part( 'members/single/notifications/notifications-loop' ); ?>
	
	<div id="notifications-actions">
		<a class="button" href="#" id="mark_as_read" data-id="<?php echo $user->id; ?>"><i class="icon-eye-open"></i>Mark All Read</a>
		<a class="button" href="#" id="delete_all_notifications" data-id="<?php echo $user->id; ?>"><i class="icon-trash"></i>Delete All</a>
	</div>

	<nav id="pag-bottom" class="pagination directory-pagination no-ajax">
		<div id="notifications-count-bottom" class="pagination-count">
			<?php bp_notifications_pagination_count(); ?>
		</div>
		<div id="notifications-pag-bottom" class="pagination-links" >
			<?php bp_notifications_pagination_links(); ?>
		</div>
	</nav>

<?php else : ?>
<p class="no-results">
	<?php if ( bp_is_current_action( 'unread' ) ) : ?>
		<?php if ( bp_is_my_profile() ) : ?>
			<?php _e( 'You have no unread notifications.', 'buddypress' ); ?>
		<?php else : ?>
			<?php _e( 'This member has no unread notifications.', 'buddypress' ); ?>
		<?php endif; ?>			
	<?php else : ?>			
		<?php if ( bp_is_my_profile() ) : ?>
			<?php _e( 'You have no notifications.', 'buddypress' ); ?>
		<?php else : ?>
			<?php _e( 'This member has no notifications.', 'buddypress' ); ?>
		<?php endif; ?>
	<?php endif; ?>
</p>
<?php endif; ?>