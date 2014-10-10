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
				
				<?php // Favorite Activity - disabled
				if ( bp_activity_can_favorite() ) : ?>
					<?php if ( !bp_get_activity_is_favorite() ) : ?>
					<a href="<?php bp_activity_favorite_link(); ?>" class="button fav bp-secondary-action" title="<?php esc_attr_e( 'Mark as Favorite', 'buddypress' ); ?>"><i class="icon-star"></i>Favorite</a>
					<?php else : ?>
					<a href="<?php bp_activity_unfavorite_link(); ?>" class="button unfav bp-secondary-action" title="<?php esc_attr_e( 'Remove Favorite', 'buddypress' ); ?>"><i class="icon-remove"></i><?php _e( 'Remove Favorite', 'buddypress' ); ?></a>
					<?php endif; ?>
				<?php endif; ?>	
			
				<?php // Activity Comment Button
				if ( bp_activity_can_comment() ) : ?>
					<a href="<?php bp_activity_comment_link(); ?>" class="button acomment-reply bp-primary-action" id="acomment-comment-<?php bp_activity_id(); ?>"><i class="icon-comments"></i>Comment <span class="comments-link-count activity-count"><?php echo bp_activity_get_comment_count(); ?></span></a>
				<?php endif; ?>
				
				<?php // Delete Activity
				if ( bp_activity_user_can_delete() ) bp_activity_delete_link(); ?>
			</div>
			<?php endif; ?>
			
			<?php bp_activity_action(); ?>
		</header>
		
		<?php if ( bp_activity_has_content() ) : ?>
		<blockquote class="activity-content">
			<?php bp_activity_content_body(); ?>
		</blockquote>
		<?php endif; ?>
		
		<?php // Activity Comments
		if ( is_user_logged_in() && bp_activity_can_comment() || bp_activity_get_comment_count() ) : ?>
		<div class="activity-comments recent-discussion-list">
			<?php bp_activity_comments(); ?>
			
			<?php if ( is_user_logged_in() && bp_activity_can_comment() ) : ?>
				<form action="<?php bp_activity_comment_form_action(); ?>" method="post" id="ac-form-<?php bp_activity_id(); ?>" class="ac-form" <?php bp_activity_comment_form_nojs_display(); ?>>
					<ol class="ac-form-list">
						<li class="textarea">
							<textarea id="ac-input-<?php bp_activity_id(); ?>" class="ac-input" name="ac_input_<?php bp_activity_id(); ?>"></textarea>
						</li>
						
						<li class="form-left">
							Reply to this activity, or press escape to cancel.
						</li>
						
						<li class="submit form-right">
							<button type="submit" name="ac_form_submit" ><i class="icon-pencil"></i>Post Comment</button>
						</li>
						
						<li class="hidden">
							<input type="hidden" name="comment_form_id" value="<?php bp_activity_id(); ?>" />
							<?php wp_nonce_field( 'new_activity_comment', '_wpnonce_new_activity_comment' ); ?>
						</li>
					</ol>
				</form>
			<?php endif; ?>
		</div>	
		<?php endif; ?>
	</div>	
</li><!-- .directory-item -->