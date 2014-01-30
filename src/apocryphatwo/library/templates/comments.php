<?php 
/**
 * Apocrypha Theme Article Comments Template
 * Andrew Clayton
 * Version 1.0.1
 * 1-26-2014
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
	
// Grab the comments and loop them! ?>
<div id="comments">
	<header class="discussion-header <?php if ( !have_comments() ) echo 'noreplies' ?>">
		<h2><?php comments_number( sprintf( 'No responses to %1$s' , the_title( '&#8220;', '&#8221;', false ) ) ,sprintf( 'One response to %1$s' , the_title( '&#8220;', '&#8221;', false ) ), sprintf( '%1$s responses to %2$s' , '%' , the_title( '&#8220;', '&#8221;', false ) ) ); ?></h2>
	</header>
	
	<ol id="comment-list">
		<?php if ( have_comments() ) : ?>
		<?php wp_list_comments( apoc_comments_args() ); ?>
		<?php endif; ?>
	</ol>

	<nav class="comment-navigation pagination ajaxed" data-postid="<?php the_ID(); ?>">
		<div class="pagination-links"><?php apoc_pagination( array( 'context' => 'comment' ) , $baseurl = get_permalink() ); ?></div>
	</nav>

</div><!-- #comments -->

<?php apoc_comment_form(); ?>