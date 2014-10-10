<?php 
/**
 * Apocrypha Theme Single Message Screen
 * Andrew Clayton
 * Version 1.0.0
 * 10-2-2013
 */
?>

<?php if ( bp_thread_has_messages() ) : ?>

<h3 id="message-subject">Private Message - <?php bp_the_thread_subject(); ?></h3>
<header id="message-header" class="discussion-header">
	<span id="message-participants">
		<?php if ( !bp_get_the_thread_recipients() ) : ?>
			<?php _e( 'You are alone in this conversation.', 'buddypress' ); ?>
		<?php else : ?>
			<?php printf( __( 'Conversation between %s and you.', 'buddypress' ), bp_get_the_thread_recipients() ); ?>
		<?php endif; ?>
	</span>
	<a class="button confirm" href="<?php bp_the_thread_delete_link(); ?>" title="<?php _e( "Delete Message", "buddypress" ); ?>"><i class="icon-remove"></i>Delete Message</a>
</header>

<ol id="message-thread" class="topic single-topic" role="main">
<?php while ( bp_thread_messages() ) : bp_thread_the_message(); 
	global $thread_template;
	$user = new Apoc_User( $thread_template->message->sender_id , 'reply' ); ?>
	<li class="reply <?php bp_the_thread_message_alt_class(); ?>">
		<header class="reply-header">
			<time class="reply-time"><?php bp_the_thread_message_time_since(); ?></time>
			<?php apoc_report_post_button( 'message' ); ?>
			<div class="reply-admin-links">
				<span class="reply-permalink" href="">#<?php echo $thread_template->current_message + 1; ?></span>
			</div>
		</header>
		
		<div class="reply-body">	
			<div class="reply-author user-block">
				<?php echo $user->block; ?>
			</div>
			<div class="reply-content">
				<?php bp_the_thread_message_content(); ?>
			</div>
			<?php $user->signature(); ?>
		</div>	
	</li>
<?php endwhile; ?>
</ol><!-- #message-thread -->
<?php endif; ?>

<div id="respond" class="create-reply">
	<header class="discussion-header">
		<h2 id="respond-title">Reply To - "<?php bp_the_thread_subject(); ?>"</h2>
		<a class="backtotop button" href="#top" title="Back to top!"><i class="icon-level-up"></i>Back to top</a>
	</header>
	<form id="send-reply" action="<?php bp_messages_form_action(); ?>" method="post" class="standard-form">
		<ol class="reply-form-fields">
			<li class="wp-editor">
			<?php // Load the TinyMCE Editor
				wp_editor( '', 'message_content', array(
					'media_buttons' => false,
					'wpautop'		=> true,
					'editor_class'  => 'private_message',
					'quicktags'		=> false,
					'teeny'			=> true,
					) ); ?>
			</li>
			
			<li class="submit">
				<button type="submit" name="send" id="send_reply_button">
					<i class="icon-envelope-alt"></i>Send Reply
				</button>
			</li>		
			
			<li class="hidden">
				<input type="hidden" id="thread_id" name="thread_id" value="<?php bp_the_thread_id(); ?>" />
				<input type="hidden" id="messages_order" name="messages_order" value="<?php bp_thread_messages_order(); ?>" />
				<?php wp_nonce_field( 'messages_send_message', 'send_message_nonce' ); ?>
			</li>
		</ol>	
	</form>
</div><!-- #respond -->

