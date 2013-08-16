<?php 
/**
 * Apocrypha Theme bbPress Topic Form
 * Andrew Clayton
 * Version 1.0.0
 * 8-12-2013
 */
 
// Get some info
$apoc		= apocrypha();
$user		= $apoc->user->data;
$user_id	= $user->ID;
$name		= $user->display_name;
?>

<?php // Display the header unless its an edit
if ( bbp_is_single_forum() ) : ?>
	<header class="discussion-header">
		<h2 id="respond-title">	<?php printf( 'Create New Topic in &ldquo;%s&rdquo;', bbp_get_forum_title() ); ?>
		</h2>
		<a class="backtotop button" href="#top" title="Back to top!"><i class="icon-level-up"></i>Back to top</a>
	</header>
<?php endif; ?>

<?php // The user is not logged in
if ( 0 == $user_id ) : ?>
	<header id="respond-subheader" class="reply-header" >	
		You are not currently logged in. You must <a class="backtotop" href="<?php echo SITEURL . '/wp-login.php'; ?>" title="Please Log In">log in</a> before creating a new topic.
	</header>

<?php // Display the form to logged in users
elseif ( bbp_current_user_can_access_create_topic_form() ) : ?>

	<?php if ( !bbp_is_topic_edit() ) : ?>
	<header id="respond-subheader" class="reply-header" >	
			You are currently logged in as <?php echo $name; ?>.
	</header>
	<?php endif; ?>
	
	<form id="new-post" name="new-post" method="post" action="<?php the_permalink(); ?>">
		<fieldset class="topic-form">

			<?php if ( !bbp_is_topic_edit() && bbp_is_forum_closed() ) : ?>
			<div class="warning"><?php _e( 'This forum is marked as closed to new topics, however your posting capabilities still allow you to do so.', 'bbpress' ); ?></div>
			<?php endif; ?>
			<?php do_action( 'bbp_template_notices' ); ?>

			<ol class="topic-form-fields">
			
				<?php // Topic title ?>
				<li class="text" id="new-topic-title">
					<label for="bbp_topic_title"><i class="icon-bookmark"></i>Topic Title:</label>
					<input type="text" id="bbp_topic_title" value="<?php bbp_form_topic_title(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="100" name="bbp_topic_title" maxlength="<?php bbp_title_max_length(); ?>" />
				</li>
				
				<?php // The TinyMCE editor ?>
				<li class="wp-editor">
					<?php bbp_the_content( array( 
						'context' 		=> 'topic',
						'media_buttons' => false,
						'wpautop' 		=> true,
						'tinymce'		=> true,
						'quicktags'		=> true,
						'teeny'			=> false,
					) ); ?>
				</li>
				
				<?php // Moderators can set topic tags
				if ( current_user_can( 'moderate' ) ) : ?>
				<li class="select form-left">
					<label for="bbp_stick_topic"><i class="icon-pushpin"></i><?php _e( 'Topic Type: ' , 'bbpress' ); ?></label>
					<?php bbp_topic_type_select(); ?>
				</li>
				<?php endif; ?>
				
				<?php // Move topic to a different forum ?>
				<?php if ( !bbp_is_single_forum() ) : ?>
				<li class="select form-right">
					<label for="bbp_forum_id"><i class="icon-folder-close"></i>In Forum:</label>
					<?php bbp_dropdown( array( 'selected' => bbp_get_form_topic_forum() ) ); ?>
				</li>
				<?php endif; ?>
				
				<?php // Alter subscription preferences ?>
				<li class="checkbox form-right">
						<input name="bbp_topic_subscription" id="bbp_topic_subscription" type="checkbox" value="bbp_subscribe" <?php bbp_form_topic_subscribed(); ?> tabindex="<?php bbp_tab_index(); ?>" />
						<?php if ( bbp_is_topic_edit() && ( get_the_author_meta( 'ID' ) != bbp_get_current_user_id() ) ) : ?>
						<label for="bbp_topic_subscription"><?php _e( 'Notify the author of follow-up replies via email', 'bbpress' ); ?></label>
						<?php else : ?>
						<label for="bbp_topic_subscription"><?php _e( 'Notify me of follow-up replies via email', 'bbpress' ); ?></label>
						<?php endif; ?>
				</li>
				
				<?php // Moderators can edit topic tags
				if ( current_user_can( 'moderate' ) ) : ?>
				<li class="text form-left">
					<label for="bbp_topic_tags"><i class="icon-tags"></i>Topic Tags:</label>
					<input type="text" value="<?php bbp_form_topic_tags(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_topic_tags" id="bbp_topic_tags" <?php disabled( bbp_is_topic_spam() ); ?> />
				</li>
				<?php endif; ?>
						
				<?php // Save revision history on edits
				if ( bbp_allow_revisions() && bbp_is_topic_edit() ) : ?>
				<li class="checkbox form-left">
					<label for="bbp_topic_edit_reason"><i class="icon-eraser"></i>Edit Reason?</label>
					<input type="text" value="<?php bbp_form_topic_edit_reason(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_topic_edit_reason" id="bbp_topic_edit_reason" />
					<br><input name="bbp_log_topic_edit" id="bbp_log_topic_edit" type="checkbox" value="1" <?php bbp_form_topic_log_edit(); ?> tabindex="<?php bbp_tab_index(); ?>" />
					<label for="bbp_log_topic_edit">Display Reason?</label>
				</li>
				<?php endif; ?>
			
				<?php // Submit button ?>			
				<li class="submit form-right">
					<button type="submit" id="bbp_topic_submit" name="bbp_topic_submit"><i class="icon-pencil"></i>
						<?php echo ( bbp_is_topic_edit() ? 'Edit Topic' : 'Post New Topic' ); ?>
					</button>
				</li>
			
				<?php // Hidden fields required by topic handler ?>	
				<li class="hidden">
					<?php bbp_topic_form_fields(); ?>
				</li>		
			</ol>
		</fieldset>
	</form>

<?php // The forum itself is closed
elseif ( bbp_is_forum_closed() ) : ?>
<header id="respond-subheader" class="reply-header" >	
	printf( __( 'The forum &#8216;%s&#8217; is closed to new topics and replies.', 'bbpress' ), bbp_get_forum_title() ); ?>
</header>

<?php // Something else happened?
else : ?>
<header id="respond-subheader" class="reply-header" >	
	Sorry, you cannot create a new topic at this time.
</header>
<?php endif; ?>