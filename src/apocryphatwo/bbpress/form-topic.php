<?php 
/**
 * Apocrypha Theme bbPress Respond Form
 * Andrew Clayton
 * Version 1.0
 * 8-11-2013
 */
 
// Get some info
global $apocrypha;
$user		= $apocrypha->user->data;
$user_id	= $user->ID;
$name		= $user->display_name;
?>

<?php // Display the header unless its an edit
if ( bbp_is_single_forum() ) : ?>
	<header class="discussion-header">
		<h2 id="respond-title">	<?php printf( 'Create New Topic in &ldquo;%s&rdquo;', bbp_get_forum_title() ); ?>
		</h2>
		<a class="backtotop button" href="#top" title="Back to top!">Back to top</a>
	</header>
<?php endif; ?>

<?php // Display the form to logged in users
if ( bbp_current_user_can_access_create_topic_form() ) : ?>

	<header id="respond-subheader" class="reply-header" >	
			You are currently logged in as <?php echo $name; ?>.
	</header>
	
	<form id="new-post" name="new-post" method="post" action="<?php the_permalink(); ?>">	

	<?php if ( !bbp_is_topic_edit() && bbp_is_forum_closed() ) : ?>
	<div class="warning"><?php _e( 'This forum is marked as closed to new topics, however your posting capabilities still allow you to do so.', 'bbpress' ); ?></div>
	<?php endif; ?>
	<?php do_action( 'bbp_template_notices' ); ?>

		<ol class="respond-form-fields">
			<li class="text">
				<label for="bbp_topic_title"><i class="icon-bookmark"></i>Topic Title:</label>
				<input type="text" id="bbp_topic_title" value="<?php bbp_form_topic_title(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="100" name="bbp_topic_title" maxlength="<?php bbp_title_max_length(); ?>" />
			</li>
			
			<li class="wp-editor">
				<?php $content = bbp_is_topic_edit() ? bbp_get_global_post_field( 'post_content', 'raw' ) : '' ;
				wp_editor( stripslashes( $content ) , 'bbp_topic_content' , array( 
					'context' 			=> 'topic',
					'media_buttons' 	=> false,
					'wpautop'    	 	=> true,
					'quicktags'    	 	=> true,
					'teeny'				=> false,
					)); ?>
			</li>
			
			<?php if ( current_user_can( 'moderate' ) ) : ?>
			<li class="select form-left">
				<label for="bbp_stick_topic"><i class="icon-pushpin"></i><?php _e( 'Topic Type: ' , 'bbpress' ); ?></label>
				<?php bbp_topic_type_select(); ?>
			</li>
			<?php endif; ?>
			
			<?php if ( !bbp_is_single_forum() ) : ?>
			<li class="select form-right">
				<label for="bbp_forum_id"><i class="icon-folder-closed"></i>Post in Forum:</label>
				<?php bbp_dropdown( array( 'selected' => bbp_get_form_topic_forum() ) ); ?>
			</li>
			<?php endif; ?>
			
			<?php if ( !bbp_is_topic_edit() ) : ?>
			<li class="checkbox form-right">
					<input name="bbp_topic_subscription" id="bbp_topic_subscription" type="checkbox" value="bbp_subscribe" <?php bbp_form_topic_subscribed(); ?> tabindex="<?php bbp_tab_index(); ?>" />
					<?php if ( bbp_is_topic_edit() && ( get_the_author_meta( 'ID' ) != bbp_get_current_user_id() ) ) : ?>
					<label for="bbp_topic_subscription"><?php _e( 'Notify the author of follow-up replies via email', 'bbpress' ); ?></label>
					<?php else : ?>
					<label for="bbp_topic_subscription"><?php _e( 'Notify me of follow-up replies via email', 'bbpress' ); ?></label>
					<?php endif; ?>
			</li>
			<?php endif; ?>	
			
			<?php if ( current_user_can( 'moderate' ) ) : ?>
			<li class="text form-left">
				<label for="bbp_topic_tags"><i class="icon-tags"></i>Topic Tags:</label>
				<input type="text" value="<?php bbp_form_topic_tags(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_topic_tags" id="bbp_topic_tags" <?php disabled( bbp_is_topic_spam() ); ?> />
			</li>
			<?php endif; ?>
		
			
			<?php if ( bbp_allow_revisions() && bbp_is_topic_edit() ) : ?>
			<li class="checkbox form-right">
				<input name="bbp_log_topic_edit" id="bbp_log_topic_edit" type="checkbox" value="1" <?php bbp_form_topic_log_edit(); ?> tabindex="<?php bbp_tab_index(); ?>" />
				<label for="bbp_log_topic_edit"><?php _e( 'Display edit history', 'bbpress' ); ?></label>
				<label for="bbp_topic_edit_reason"><?php printf( __( 'Reason for editing:', 'bbpress' ), bbp_get_current_user_name() ); ?></label>
				<input type="text" value="<?php bbp_form_topic_edit_reason(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_topic_edit_reason" id="bbp_topic_edit_reason" />
			</li>
			<?php endif; ?>
					
			<li class="submit form-right">
				<button type="submit" id="bbp_topic_submit" name="bbp_topic_submit"><i class="icon-pencil"></i>Post New Topic</button>
			</li>
		
			<li class="hidden">
				<?php bbp_topic_form_fields(); ?>
			</li>		
		</ol>
	</form>

<?php elseif ( bbp_is_forum_closed() ) : ?>
<header id="respond-subheader" class="reply-header" >	
	printf( __( 'The forum &#8216;%s&#8217; is closed to new topics and replies.', 'bbpress' ), bbp_get_forum_title() ); ?>
</header>

<?php else : ?>
<header id="respond-subheader" class="reply-header" >	
	You are not currently logged in. You must <a class="backtotop" href="<?php echo SITEURL . '/wp-login.php'; ?>" title="Please Log In">log in</a> before creating a new topic.
</header>
<?php endif; ?>