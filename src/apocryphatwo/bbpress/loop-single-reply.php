<?php 
/**
 * Apocrypha Theme Single Reply Template
 * Andrew Clayton
 * Version 1.0
 * 8-11-2013
 */
?>

<li id="post-<?php bbp_reply_id(); ?>" <?php bbp_reply_class(); ?>>
	<header class="reply-header">
		<time class="reply-time" datetime="<?php echo get_the_time( 'Y-m-d\TH:i' ); ?>"><?php echo bp_core_time_since( strtotime( get_the_time( 'c' ) ) , current_time( 'timestamp' ) ); ?></time>
		<?php apoc_report_post_button( 'reply' ); ?>
		<div class="reply-admin-links">
			<?php apoc_reply_admin_links( bbp_get_reply_id() ); ?>
			<a class="reply-permalink" href="<?php bbp_reply_url(); ?>" title="<?php bbp_reply_title(); ?>">#<?php echo bbp_get_reply_position(); ?></a>
		</div>
	</header>
	
	<div class="reply-body">
		<div class="reply-author">
			<?php apoc_member_block( bbp_get_reply_author_id() , $context = 'reply' , $avatar = 'thumb' ); ?>
		</div>
		
		<div class="reply-content">
			<?php bbp_reply_content(); ?>
		</div>
		<?php user_signature( bbp_get_reply_author_id() ); ?>
	</div>
</li>