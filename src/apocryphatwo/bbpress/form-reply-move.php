<?php 
/**
 * Apocrypha Theme Move Reply Form
 * Andrew Clayton
 * Version 1.0.0
 * 8-17-2013
 */
?>

<?php // Make sure the user has permission to merge the topic
if ( is_user_logged_in() && current_user_can( 'edit_topic', bbp_get_topic_id() ) ) : ?>
<form id="move_reply" name="move_reply" method="post" action="<?php the_permalink(); ?>">
	<div class="instructions">
		<h3>Move Reply Instructions</h3>
		<ul>
			<li>You can either make this reply a new topic with a new title, or merge it into an existing topic.</li>
			<li>If you choose an existing topic, replies will be ordered by the time and date they were created.</li>
		</ul>
	</div>

	<fieldset class="move-form">
		<ol class="move-form-fields">
		
			<li id="split-method" class="form-left">
				<h3>Choose Split Method</h3>
				<ul class="radio-options-list">
				
					<?php // Split to new topic ?>
					<li>
						<input name="bbp_reply_move_option" id="bbp_reply_move_option_reply" type="radio" checked="checked" value="topic" tabindex="<?php bbp_tab_index(); ?>" />
						<label for="bbp_reply_move_option_reply">Create a new topic with the title:</label><br>
						<input type="text" id="bbp_reply_move_destination_title" value="<?php printf( __( 'Moved: %s', 'bbpress' ), bbp_get_reply_title() ); ?>" tabindex="<?php bbp_tab_index(); ?>" size="80" name="bbp_reply_move_destination_title" />
					</li>
				
					<?php // Split to existing topic 
					if ( bbp_has_topics( array( 'show_stickies' => false, 'post_parent' => bbp_get_reply_forum_id( bbp_get_reply_id() ), 'post__not_in' => array( bbp_get_reply_topic_id( bbp_get_reply_id() ) ) ) ) ) : ?>
					<li>
						<input name="bbp_reply_move_option" id="bbp_reply_move_option_existing" type="radio" value="existing" tabindex="<?php bbp_tab_index(); ?>" />
						<label for="bbp_reply_move_option_existing"><?php _e( 'Use an existing topic in this forum:', 'bbpress' ); ?></label>
						<?php bbp_dropdown( array(
							'post_type'   => bbp_get_topic_post_type(),
							'post_parent' => bbp_get_reply_forum_id( bbp_get_reply_id() ),
							'selected'    => -1,
							'exclude'     => bbp_get_reply_topic_id( bbp_get_reply_id() ),
							'select_id'   => 'bbp_destination_topic',
							'none_found'  => __( 'No other topics found!', 'bbpress' )
						) ); ?>
					</li>
				<?php endif; ?>
				</ul>
			</li>
			
			<?php // Show a warning ?>				
			<li class="form-left">	
				<div class="warning">This process cannot be undone.</div>
			</li>
			
			<?php // Submit the split ?>			
			<li class="submit form-right">
				<button type="submit" tabindex="<?php bbp_tab_index(); ?>" id="bbp_move_reply_submit" name="bbp_move_reply_submit"><i class="icon-move"></i>Move Reply</button>
			</li>




		</ol>
	</fieldset>
</form>


<?php // Warn off anyone who got here by mistake
else : ?>
	<p class="notice warning"><?php is_user_logged_in() ? _e('You do not have permissions to move this reply!') : _e('You cannot move this reply.') ?></p>
<?php endif; ?>