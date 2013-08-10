<?php 
/**
 * Apocrypha Theme Article Comments Template
 * Andrew Clayton
 * Version 1.0
 * 8-6-2013
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
		<div class="pagination-links"><?php paginate_comments_links(); ?></div>
	</nav>

</div><!-- #comments -->

<?php apoc_comment_form(); ?>