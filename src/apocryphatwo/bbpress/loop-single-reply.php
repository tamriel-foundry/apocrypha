<?php 
/**
 * Apocrypha Theme Single Reply Template
 * Andrew Clayton
 * Version 1.0.0
 * 8-11-2013
 */
 
// Get the reply author block
$author = new Apoc_User( bbp_get_reply_author_id() , 'reply' );
$block	= $author->block;
$sig	= $author->sig;
?>

<li id="post-<?php bbp_reply_id(); ?>" <?php bbp_reply_class(); ?>>
	<header class="reply-header">
		<time class="reply-time" datetime="<?php echo get_the_time( 'Y-m-d\TH:i' ); ?>"><?php echo bp_core_time_since( strtotime( get_the_time( 'c' ) ) , current_time( 'timestamp' ) ); ?></time>
		<?php apoc_report_post_button( 'reply' ); ?>
		<?php if ( current_user_can( 'moderate' ) ) : ?><a class="author-ip-toggle"><i class="icon-tag"></i></a><?php endif; ?>
		<div class="reply-admin-links">
			<?php apoc_reply_admin_links( bbp_get_reply_id() ); ?>
			<a class="reply-permalink" href="<?php bbp_reply_url(); ?>" title="<?php bbp_reply_title(); ?>">#<?php echo bbp_get_reply_position(); ?></a>
		</div>
	</header>
	
	<div class="reply-body">
		<div class="reply-author">
			<?php echo $block; ?>
			<?php if ( current_user_can( 'moderate' ) ) : ?><p class="author-ip"><?php bbp_author_ip( array( 'post_id' => bbp_get_reply_id() , 'before' => '' , 'after' => '' ) ); ?></p><?php endif; ?>
		</div>
		
		<div class="reply-content">
			<?php bbp_reply_content(); ?>
		</div>
		<?php echo $sig; ?>
	</div>
</li>