<?php 
/**
 * Apocrypha Theme Comment Respond Template
 * Andrew Clayton
 * Version 1.0
 * 8-7-2013
 */

// Get some info about the post and current user
global $post, $apocrypha;
$post_id 	= $post->ID;
$user		= $apocrypha->user->data;
$user_id	= $user->ID;
$name		= $user->display_name;
?>
 
<div id="respond">
<?php if ( comments_open( $post_id ) ) : ?>
	
	<header class="discussion-header">
		<h2 id="respond-title">Reply To: <?php the_title(); ?></h2>
		<a class="backtotop button" href="#top" title="Back to top!">Back to top</a>
	</header>
	
	<header id="respond-subheader" class="reply-header" >	
	<?php if ( $user_id == 0 ) : ?>
		You are not currently logged in. You must <a class="backtotop" href="<?php echo SITEURL . '/wp-login.php'; ?>" title="Please Log In">log in</a> before commenting.
	</header>

	<?php else : ?>
		You are currently logged in as <?php echo $name; ?>.	
	</header>
	
	<form action="<?php echo SITEURL . '/wp-comments-post.php'; ?>" method="post" id="commentform">	
		<ol>
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
			
			<li class="hidden">
				<?php do_action( 'comment_form', $post_id ); ?>	
			</li>
			
			<li class="submit">
				<button name="submit" type="submit" id="submit"><i class="icon-pencil"></i>Post Comment</button>	
				<?php comment_id_fields( $post_id ); ?>
			</li>	
		</ol>
	</form>
	<?php endif; ?>
<?php endif; ?>
</div><!-- #respond -->