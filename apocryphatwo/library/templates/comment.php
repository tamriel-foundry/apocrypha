<?php 
/**
 * Apocrypha Theme Single Comment Template
 * Andrew Clayton
 * Version 1.0
 * 1-19-2013
 */

// Get some information
global $comment, $apocrypha;
$count = $apocrypha->comment_count

// Display the comment ?>
<li id="comment-<?php echo $comment->comment_ID; ?>" class="<?php display_comment_class(); ?>">
	<header class="reply-header">
		<time class="reply-time" datetime="<?php echo date( 'Y-m-d\TH:i' , strtotime($comment->comment_date) ); ?>"><?php echo bp_core_time_since( $comment->comment_date_gmt , current_time( 'timestamp' , true ) )?></time>
		<?php apoc_report_post_button( 'comment' ); ?>
		<div class="reply-admin-links">
			<?php apoc_comment_admin_links(); ?>
			<a class="reply-permalink" href="<?php echo get_comment_link( $comment->comment_ID ); ?>" title="Link directly to this comment">#<?php echo $count; ?></a>
		</div>
	</header>
	
	<div class="reply-body">
		<div class="reply-author">
			<?php apoc_member_block( $comment->user_id , $context = 'reply' , $avatar = 'thumb' ); ?>
		</div>
		
		<div class="reply-content">
			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="warning comment-moderation">Your comment is awaiting moderation.</p>
			<?php endif; ?>
			<?php comment_text( $comment->comment_ID ); ?>
		</div>
		<?php user_signature( $comment->user_id ); ?>
	</div>
</li>