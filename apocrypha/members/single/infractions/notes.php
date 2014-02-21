<?php 
/**
 * Apocrypha Theme User Moderator Notes Component
 * Andrew Clayton
 * Version 1.0.0
 * 10-2-2013
 */
 
// Get the currently displayed user
global $user;
$user 		= new Apoc_User( bp_displayed_user_id() , 'profile' );
$user_id 	= $user->id;
$count		= $user->mod_notes['count'];
$level		= $user->warnings['level'] > 0 ? $user->warnings['level'] : 0;

// Get the url
global $bp;
$action_url = $bp->displayed_user->domain . 'infractions/notes';

// Process the form
if ( isset( $_POST['moderator_note_nonce'] ) && wp_verify_nonce( $_POST['moderator_note_nonce'] , 'moderator-note' ) )  {

	// Get some data
	$notes 			= ( $count > 0 ) ? $user->mod_notes['history'] : array();
	$current_user 	= apocrypha()->user->data;
	$moderator		= $current_user->display_name;
	$date 			= date('M j, Y', current_time( 'timestamp' ) );
	
	// Validate form contents
	if ( empty( $_POST['note-content'] ) )
		$error = 'You have to actually write something, dummy!';
	else
		$note = wpautop( $_POST['note-content'] );
		
	// Add the note to the array
	if ( !$error ) {
		
		$notes[] = array(
			'note' 	=> trim( $note ),
			'moderator' => $moderator,
			'date' 		=> $date,
			);
			
		// Update the usermeta
		update_user_meta( $user_id , 'moderator_notes' , $notes );	
		
		// Redirect
		wp_redirect( $action_url , 302 );
	}
}
?>

<?php get_header(); ?>

	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>	
		
		<div id="profile-body">
			<?php do_action( 'template_notices' ); ?>
			<nav class="directory-subheader no-ajax" id="subnav" >
				<ul id="profile-tabs" class="tabs" role="navigation">
					<?php bp_get_options_nav(); ?>
				</ul>
			</nav><!-- #subnav -->

			<div id="user-infractions">
				<h3>Moderator Notes</h3>
				<div class="warning">The current warning level for <?php bp_displayed_user_username(); ?> is <?php echo $level; ?> points.</div>
				
				<?php if ( $user->mod_notes['history'] ) : ?>
				<ol class="infraction-list">
					<?php foreach( $user->mod_notes['history'] as $id => $entry ) : ?>
					<li id="modnote-<?php echo $id; ?>" class="infraction-entry">
						<header>
							<span class="infraction-meta activity"><?php echo $entry['date']; ?></span>
							<span class="infraction-mod">Issued By: <?php echo $entry['moderator']; ?></span>
						</header>
						
						<div class="infraction-content">
							<?php echo $entry['note']; ?>
							
							<?php if ( current_user_can( 'moderate' ) ) : ?>
								<a class="clear-mod-note button" href="<?php echo $action_url . '?id=' . $id . '&amp;_wpnonce=' . wp_create_nonce( 'clear-moderator-note' ); ?>" title="Delete Note"><i class="icon-trash"></i>Delete</a>
							<?php endif; ?>
						</div>	
					</li>
					<?php endforeach; ?>	
				</ol>	
				<?php else : ?>
					<p class="no-results"><i class="icon-ok"></i>No notes here!</p>
				<?php endif; ?>
			</div>
			
			<form action="<?php echo $action_url ?>" name="moderator-note-form" id="moderator-note-form" class="standard-form" method="post">
				<?php if ( $error ) : ?>
				<div class="error"><?php echo $error; ?></div>
				<?php endif; ?>
				<ol id="new-modnote">
					<li class="textfield">
						<textarea name="note-content" rows="5"></textarea>
					</li>
					
					<li class="submit">
						<button type="submit" name="issuewarning" class="submit button">
							<i class="icon-pencil"></i>Add Note
						</button>
					</li>				
					
					<li class="hidden">
						<?php wp_nonce_field( 'moderator-note' , 'moderator_note_nonce' ) ?>
						<input name="action" type="hidden" id="action" value="issue-mod-note" />
					</li>
				</ol>		
			</form>			
		</div>		
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>