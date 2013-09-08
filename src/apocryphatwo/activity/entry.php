<?php 
/**
 * Apocrypha Theme Single Activity Entry
 * Andrew Clayton
 * Version 1.0.0
 * 9-8-2013
 */
 
// Get the user info
$user = new Apoc_User( bp_get_activity_user_id() , 'directory' );	?>

<li id="activity-<?php bp_activity_id(); ?>" class="<?php bp_activity_css_class(); ?> directory-entry">
	<div class="directory-member">
		<?php echo $user->block; ?>	
	</div>
	
	<div class="directory-content">
		
		<header class="activity-header">			
			<?php if ( is_user_logged_in() ) : ?>
			<div class="actions">
				<?php if ( bp_activity_can_favorite() ) : ?>
					<?php if ( !bp_get_activity_is_favorite() ) : ?>
					<a href="<?php bp_activity_favorite_link(); ?>" class="button fav bp-secondary-action" title="<?php esc_attr_e( 'Mark as Favorite', 'buddypress' ); ?>"><i class="icon-star"></i>Favorite</a>
					<?php else : ?>
					<a href="<?php bp_activity_unfavorite_link(); ?>" class="button unfav bp-secondary-action" title="<?php esc_attr_e( 'Remove Favorite', 'buddypress' ); ?>"><i class="icon-remove"></i><?php _e( 'Remove Favorite', 'buddypress' ); ?></a>
					<?php endif; ?>
				<?php endif; ?>	
				<?php if ( bp_activity_user_can_delete() ) bp_activity_delete_link(); ?>
			</div>
			<?php endif; ?>
			
			<?php bp_activity_action(); ?>
		</header>
		
		<?php if ( bp_activity_has_content() ) : ?>
		<blockquote class="activity-content">
			<?php bp_activity_content_body(); ?>
		</blockquote>
		<?php endif; ?>
	</div>	
</li><!-- .directory-item -->