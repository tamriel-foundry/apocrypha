<?php 
/**
 * Apocrypha Theme Comment Edit Template
 * Andrew Clayton
 * Version 1.0
 * 7-1-2013
 */
 
global $comment;
$action_url = get_permalink() . 'comment-' . $comment->comment_ID . '/edit/';

if( isset( $_POST['submit'] ) && wp_verify_nonce( $_POST['edit_comment_nonce'] , 'edit-comment' ) ) :

	/* Register the update */
	$comment_tosave = (array) $comment;
	$comment_tosave['comment_content'] = $_POST['comment-edit'];
	wp_update_comment( $comment_tosave );
	
	/* Redirect to the new content */
	$link = get_comment_link( $comment->comment_ID );
	wp_redirect( $link , 302 );
	
endif;
?>

<?php get_header(); // Load the header ?>
	
	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<header class="entry-header <?php post_header_class(); ?>">
			<h1 class="entry-title"><?php entry_header_title( false ); ?> - Edit Comment</h1>
			<p class="entry-byline"><?php comment_edit_header_description(); ?></p>
		</header>
			
		<?php endwhile; endif; ?>
			
		<div id="respond">
			<form id="edit-comment-form" class="standard-form" name="edit-comment-form" method="post" action="<?php echo $action_url ?>">
			
				<?php wp_editor( stripslashes( $comment->comment_content ) , 'comment-edit' , array(
					'media_buttons' => false,
					'wpautop'		=> true,
					'editor_class'  => 'comment-edit',
					'quicktags'		=> true,
					'teeny'			=> false,
					)
				); ?>		

				<p class="form-submit">
					<input name="submit" type="submit" id="submit" value="Edit Comment" />
					<?php wp_nonce_field( 'edit-comment' , 'edit_comment_nonce' ) ?>
				</p>
					
			</form>
		</div><!-- #respond -->
			
	</div><!-- #content -->

<?php get_footer(); ?>
		