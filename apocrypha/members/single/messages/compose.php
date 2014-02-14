<?php 
/**
 * Apocrypha Theme Profile Messages Compose Screen
 * Andrew Clayton
 * Version 1.0.0
 * 10-2-2013
 */
?>

<form action="<?php bp_messages_form_action('compose'); ?>" method="post" id="send_message_form" class="standard-form" role="main" enctype="multipart/form-data">
	<ol class="message-compose-form">
		<li class="text">
			<label for="send-to-input"><i class="icon-user"></i>Send To:</label>
			<input type="text" name="send-to-input" class="send-to-input" id="send-to-input" size="50" />
			<span> (Separate Multiple Users With Commas)</span>
		</li>
		
		<li class="recipients">
			<ul id="message-recipients">
				<?php bp_message_get_recipient_tabs(); ?>
			</ul>
		</li>
		
		<li class="text">
			<label for="subject"><i class="icon-bookmark"></i>Subject:</label>
			<input type="text" name="subject" id="subject" value="<?php bp_messages_subject_value(); ?>" size="80"/>	

		</li>
		
		<li class="textarea">
			<?php // Load the TinyMCE Editor
			$thecontent = bp_get_messages_content_value();
			wp_editor( htmlspecialchars_decode( $thecontent, ENT_QUOTES ), 'message_content', array(
				'media_buttons' => false,
				'wpautop'		=> true,
				'editor_class'  => 'private_message',
				'quicktags'		=> false,
				'teeny'			=> true,
				'tabindex'		=> 52
			) ); ?>
		</li>
		
		<?php if ( bp_current_user_can( 'bp_moderate' ) ) : ?>
		<li class="checkbox form-left">
			<input type="checkbox" id="send-notice" name="send-notice" value="1" tabindex="-1" />
			<label for="send-notice">This is an admin notice to all users.</label>
		</li>
		<?php endif; ?>
		
		<li class="submit form-right">
			<button type="submit" name="send" id="send"><i class="icon-envelope-alt"></i>Send Message</button>		
		</li>
	
		<li class="hidden">
			<input type="hidden" name="send_to_usernames" id="send-to-usernames" value="<?php bp_message_get_recipient_usernames(); ?>" class="<?php bp_message_get_recipient_usernames(); ?>" />
			<?php wp_nonce_field( 'messages_send_message' ); ?>
		</li>	
	</ol>
</form><!-- #send_message_form -->

<script type="text/javascript">document.getElementById("send-to-input").focus();</script>