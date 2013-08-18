<?php 
/**
 * Apocrypha Theme Split Topic Form
 * Andrew Clayton
 * Version 1.0.0
 * 8-12-2013
 */
?>

<?php // Make sure the user has permission to merge the topic
if ( is_user_logged_in() && current_user_can( 'edit_topic', bbp_get_topic_id() ) ) : ?>
<form id="split_topic" name="split_topic" method="post" action="<?php the_permalink(); ?>">
	<div class="instructions">
		<h3>Split Topic Instructions</h3>
		<ul>
			<li>When you split a topic, you are slicing it in half starting with the reply you just selected. Choose to use that reply as a new topic with a new title, or merge those replies into an existing topic.</li>
			<li>If you use the existing topic option, replies within both topics will be merged chronologically. The order of the merged replies is based on the time and date they were posted.</li>
			<li>If you need to split a thread to merge part of it into a thread that resides in a different forum, follow these steps. First, split off the desired replies and create a new (temporary) topic for them. Next move that temporary topic into the forum that contains the target topic with which you want to merge. Lastly, use the merge topic form on the temporary topic to combine it with your target.</li>
		</ul>
	</div>
	
	<fieldset class="split-form">
		<ol class="split-form-fields">
		
			<?php // Choose split target ?>
			<li id="split-method" class="form-left">	
				<h3>Choose Split Method</h3>
				
				<?php // Split to new topic ?>
				<input name="bbp_topic_split_option" id="bbp_topic_split_option_reply" type="radio" checked="checked" value="reply" tabindex="<?php bbp_tab_index(); ?>" />
				<label for="bbp_topic_split_option_reply">Create a new topic with the title:</label>
				<input type="text" id="bbp_topic_split_destination_title" value="<?php printf( __( 'Split: %s', 'bbpress' ), bbp_get_topic_title() ); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_topic_split_destination_title" /><br><br>
				
				<?php // Split to existing topic 			
				if ( bbp_has_topics( array( 'show_stickies' => false, 'post_parent' => bbp_get_topic_forum_id( bbp_get_topic_id() ), 'post__not_in' => array( bbp_get_topic_id() ) ) ) ) : ?>
					<input name="bbp_topic_split_option" id="bbp_topic_split_option_existing" type="radio" value="existing" tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_topic_split_option_existing"><?php _e( 'Use an existing topic in this forum:', 'bbpress' ); ?></label>
					<?php bbp_dropdown( array(
						'post_type'   => bbp_get_topic_post_type(),
						'post_parent' => bbp_get_topic_forum_id( bbp_get_topic_id() ),
						'selected'    => -1,
						'exclude'     => bbp_get_topic_id(),
						'select_id'   => 'bbp_destination_topic',
						'none_found'  => __( 'No other topics found!', 'bbpress' )
					) ); ?>
				<?php endif;?>
			</li>

			<?php // Specify subscriber, favorite, and tag options ?>			
			<li id="split-options" class="form-right">	
				<h3>Split Options</h3>
				
				<input name="bbp_topic_subscribers" id="bbp_topic_subscribers" type="checkbox" value="1" tabindex="<?php bbp_tab_index(); ?>" />
				<label for="bbp_topic_subscribers">Transfer Topic Subscribers?</label><br />
				
				<input name="bbp_topic_favoriters" id="bbp_topic_favoriters" type="checkbox" value="1" tabindex="<?php bbp_tab_index(); ?>" />
				<label for="bbp_topic_favoriters">Transfer Topic Favorites?</label><br />

				<input name="bbp_topic_tags" id="bbp_topic_tags" type="checkbox" value="1" tabindex="<?php bbp_tab_index(); ?>" />
				<label for="bbp_topic_tags">Transfer Topic Tags?</label><br />		
			</li>
		
			<?php // Show a warning ?>				
			<li class="form-left">	
				<div class="warning">This process cannot be undone.</div>
			</li>
			
			<?php // Submit the split ?>			
			<li class="submit form-right">
				<button type="submit" tabindex="<?php bbp_tab_index(); ?>" id="bbp_merge_topic_submit" name="bbp_merge_topic_submit"><i class="icon-code-fork "></i>Split Topic</button>
			</li>

			<?php // Hidden fields required by split handler ?>
			<li class="hidden">
				<?php bbp_split_topic_form_fields(); ?>
			</li>	
		
		
		</ol>
	</fieldset>
</form>

<?php // Warn off anyone who got here by mistake
else : ?>
	<p class="notice warning"><?php is_user_logged_in() ? _e('You do not have permissions to split this topic!') : _e('You cannot split this topic.') ?></p>
<?php endif; ?>