<?php 
/**
 * Apocrypha Theme Article Comments Template
 * Andrew Clayton
 * Version 1.0
 * 8-6-2013
 */

// Kill the page if trying to access this template directly.
if ( 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
	die( 'Please do not attempt to load this page directly!' );
	
// Grab the comments and loop them!
if ( have_comments() ) : ?>
<div id="comments">
	<header class="discussion-header">
		<h2><?php comments_number( sprintf( 'No responses to %1$s' , the_title( '&#8220;', '&#8221;', false ) ) ,sprintf( 'One response to %1$s' , the_title( '&#8220;', '&#8221;', false ) ), sprintf( '%1$s responses to %2$s' , '%' , the_title( '&#8220;', '&#8221;', false ) ) ); ?></h2>
	</header>
	
	<ol id="comment-list">
		<?php wp_list_comments( apoc_comments_args() ); ?>
	</ol>

	<nav class="comment-navigation pagination">
		<div class="comment-pagination-links"><?php // paginate_comments_links(); ?></div>
	</nav>

</div><!-- #comments -->
<?php endif; ?>
	
<?php apoc_comment_form(); ?>