<?php 
/**
 * Apocrypha Theme Activity Post Form
 * Andrew Clayton
 * Version 1.0.0
 * 9-19-2013
 */

// Get the user info
$user 		= apocrypha()->user->data;
$user_id	= $user->ID;
?>

<form action="<?php bp_activity_post_form_action(); ?>" method="post" id="whats-new-form" name="whats-new-form" role="complementary">

	<ol id="whats-new-fields">
		
		<?php // The status update textarea ?>
		<li class="textarea">
			<?php $placeholder = bp_is_group() ? "What's new in " . bp_get_group_name() . ', ' . $user->display_name . '?' : "What's on your mind " . $user->display_name . '?'; ?>
			<textarea name="whats-new" id="whats-new" placeholder="<?php echo $placeholder; ?>" rows="1"><?php if ( isset( $_GET['r'] ) ) : ?>@<?php echo esc_attr( $_GET['r'] ); ?> <?php endif; ?></textarea>
		</li>
	
		<?php // Post directly to group from main activity feed
		if ( !bp_is_my_profile() && !bp_is_group() ) : ?>	
		<li class="select form-left">
			<label for="whats-new-post-in"><i class="icon-home"></i>Post Update To:</label>
			<select id="whats-new-post-in" name="whats-new-post-in">
				<option selected="selected" value="0"><?php _e( 'My Profile', 'buddypress' ); ?></option>
				<?php if ( bp_has_groups( 'user_id=' . $user_id . '&type=alphabetical&max=100&per_page=100&populate_extras=0' ) ) : while ( bp_groups() ) : bp_the_group(); ?>
					<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>
				<?php endwhile; endif; ?>
			</select>
		</li>
		<?php endif; ?>
		
		<?php // Submit the status ?>
		<li class="submit form-right">
			<button type="submit" name="aw-whats-new-submit" id="aw-whats-new-submit">
				<i class="icon-pencil"></i>Post Update
			</button>		
		</li>
		
		<?php // Hidden fields for processing ?>
		<li class="hidden">
			<?php wp_nonce_field( 'post_update', '_wpnonce_post_update' ); ?>
			<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />
			<?php if ( bp_is_group() ) : ?>
			<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups" />
			<input type="hidden" id="whats-new-post-in" name="whats-new-post-in" value="<?php bp_group_id(); ?>" />
			<?php endif; ?>
		</li>
	</ol>
</form><!-- #whats-new-form" -->