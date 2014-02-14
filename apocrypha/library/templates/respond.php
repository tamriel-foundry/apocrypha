<?php 
/**
 * Apocrypha Theme Comment Form Template
 * Andrew Clayton
 * Version 1.0.0
 * 8-12-2013
 */

// Get some info about the current user and post
$apoc		= apocrypha();
$user_id	= get_current_user_id();
$post		= $apoc->queried_object;
$post_id 	= $apoc->queried_id;
$status		= $post->comment_status;
?>
 
<div id="respond">

	<header class="discussion-header">
		<h2 id="respond-title">Reply To: <?php the_title(); ?></h2>
		<a class="backtotop button" href="#top" title="Back to top!">Back to top</a>
	</header>
	
	<?php // The user is not logged in
	if ( 0 == $user_id ) : ?>
	<header id="respond-subheader" class="reply-header" >	
		You are not currently logged in. You must <a class="backtotop" href="<?php echo SITEURL . '/wp-login.php'; ?>" title="Please Log In">log in</a> before commenting.
	</header>
	
	<?php // Comments are allowed
	elseif ( 'open' == $status ) : ?>
	<header id="respond-subheader" class="reply-header" >	
		You are currently logged in as <?php echo $apoc->user->data->display_name; ?>.	
	</header>
	
	<form action="<?php echo SITEURL . '/wp-comments-post.php'; ?>" method="post" id="commentform" name="commentform">
		<fieldset class="comment-form">
			<ol class="comment-form-fields">
			
				<?php // The TinyMCE editor ?>
				<li class="wp-editor">
					<?php wp_editor( '' , 'comment' , array(
						'media_buttons' => false,
						'wpautop'		=> true,
						'editor_class'  => 'comment',
						'quicktags'		=> true,
						'teeny'			=> false,
						)
					); ?>
				</li>
				
				<?php // Hidden fields required by the comment handler ?>
				<li class="hidden">
					<?php do_action( 'comment_form', $post_id ); ?>	
				</li>
				
				<?php // Submit form ?>
				<li class="submit">
					<button name="submit" type="submit" id="submit"><i class="icon-pencil"></i>Post Comment</button>	
					<?php comment_id_fields( $post_id ); ?>
				</li>
				
			</ol>
		</fieldset>
	</form>

	<?php // Comments are closed
	else : ?>
	<header id="respond-subheader" class="reply-header" >	
		Comments are currently closed for this article.	
	</header>	
	<?php endif; ?>
	
</div><!-- #respond -->