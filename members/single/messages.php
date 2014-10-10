<?php 
/**
 * Apocrypha Theme Profile Messages Component
 * Andrew Clayton
 * Version 1.0.0
 * 10-2-2013
 */
?>

<nav class="directory-subheader no-ajax" id="subnav" >
	<ul id="profile-tabs" class="tabs" role="navigation">
		<?php bp_get_options_nav(); ?>
	</ul>
	<?php if ( bp_is_messages_inbox() || bp_is_messages_sentbox() ) : ?>
	<div class="message-search directory-search" role="search">
		<?php apoc_messages_search_form(); ?>
	</div>
	<?php endif; ?>
</nav><!-- #subnav -->

<div id="private-messages" class="messages" role="main">
<?php if ( bp_is_current_action( 'compose' ) ) :
	locate_template( array( 'members/single/messages/compose.php' ), true );
elseif ( bp_is_current_action( 'view' ) ) :
	locate_template( array( 'members/single/messages/single.php' ), true );
elseif ( bp_is_current_action( 'notices' ) ) :
	locate_template( array( 'members/single/messages/notices-loop.php' ), true );
else :
	locate_template( array( 'members/single/messages/messages-loop.php' ), true ); ?>
<?php endif; ?>
</div><!-- #private-messages -->