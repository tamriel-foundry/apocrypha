<?php 
/**
 * Apocrypha Theme Profile Messages Loop Screen
 * Andrew Clayton
 * Version 1.0.0
 * 10-2-2013
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 */
?>

<?php if ( bp_has_message_threads( bp_ajax_querystring( 'messages' ) ) ) : ?>
<ol id="message-threads" class="directory-list" role="main">
	<?php while ( bp_message_threads() ) : bp_message_thread(); 
	global $messages_template;
	$sender_id 	= $messages_template->thread->last_sender_id; 
	$avatar		= new Apoc_Avatar( array( 'user_id' => $sender_id , 'size' => 50 , 'link' => true ) ); ?>
	<li id="m-<?php bp_message_thread_id(); ?>" class="message <?php bp_message_css_class(); ?> <?php if ( bp_message_thread_has_unread() ) : ?>unread"<?php else: ?>read"<?php endif; ?>>
		
		<div class="forum-content">
			<h3 class="forum-title">
				<a class="topic-title-link" href="<?php bp_message_thread_view_link(); ?>" title="Read this message"><?php bp_message_thread_subject(); ?></a>
				<?php if( bp_get_message_thread_unread_count() ) : ?>
					<span class="unread-count">&rarr; <?php bp_message_thread_unread_count(); ?> Unread Message</span>
				<?php endif; ?>
			</h3>
			
			<p class="forum-description">
				<?php bp_message_thread_excerpt(); ?>
			</p>
			
			<div class="thread-options checkbox">
				<input type="checkbox" name="message_ids[]" value="<?php bp_message_thread_id(); ?>" /><label>
				<a class="button confirm" href="<?php bp_message_thread_delete_link(); ?>" title="<?php _e( "Delete Message", "buddypress" ); ?>"><i class="icon-remove"></i>Delete</a></label>
			</div>
		</div>	
	
		<div class="forum-freshness">
			<?php echo $avatar->avatar; ?>
			<div class="freshest-meta">
				<span class="freshest-author">By <?php bp_message_thread_from(); ?></span><br/>
				<span class="freshest-time"><?php echo bp_core_time_since( strtotime( $messages_template->thread->last_message_date ) ); ?></span>
			</div>
		</div>
	</li>
	<?php endwhile; ?>
</ol>

<div class="messages-options-nav">
	<label for="message-type-select">Select</label>
	<select name="message-type-select" id="message-type-select">
		<option value=""></option>
		<option value="read"><?php _e('Read', 'buddypress') ?></option>
		<option value="unread"><?php _e('Unread', 'buddypress') ?></option>
		<option value="all"><?php _e('All', 'buddypress') ?></option>
	</select>

	<?php if ( !bp_is_current_action( 'sentbox' ) && bp_is_current_action( 'notices' ) ) : ?>
		<a class="button" href="#" id="mark_as_read"><?php _e('Mark as Read', 'buddypress') ?></a>
		<a class="button" href="#" id="mark_as_unread"><?php _e('Mark as Unread', 'buddypress') ?></a>
	<?php endif; ?>
	<a class="button" href="#" id="delete_<?php echo bp_current_action(); ?>_messages"><i class="icon-trash"></i><?php _e( 'Delete Selected', 'buddypress' ); ?></a>
</div><!-- .messages-options-nav -->

<nav class="pagination no-ajax" id="messages-pagination">
	<div class="pagination-count" id="messages-dir-count">
		<?php bp_messages_pagination_count(); ?>
	</div>
	<div class="pagination-links" id="messages-dir-pag">
		<?php bp_messages_pagination(); ?>
	</div>
</nav><!-- .pagination -->

<?php else : ?>
	<?php if ( 'sentbox' == bp_current_action() ) : ?>	
	<p class="no-results"><i class="icon-inbox"></i>Your outbox is empty!</p>
	<?php else : ?>
	<p class="no-results"><i class="icon-inbox"></i>Your inbox is empty!</p>
	<?php endif; ?>
<?php endif; ?>