<?php 
/**
 * Apocrypha Theme Group Request Membership Component
 * Andrew Clayton
 * Version 1.0.0
 * 9-29-2013
 */
 
// Retrieve the guild object
global $guild;
$grammar = ( $guild->guild == 1 ) ? 'guild' : 'group';
?>

<nav class="directory-subheader no-ajax" id="subnav" >
	<ul id="profile-tabs" class="tabs" role="navigation">
		<li class="current"><span>Request <?php echo ucfirst( $grammar ); ?> Membership</span></li>
	</ul>
</nav><!-- #subnav -->

<?php if ( !bp_group_has_requested_membership() ) : ?>
<form action="<?php bp_group_form_action('request-membership'); ?>" method="post" name="request-membership-form" id="request-membership-form" class="standard-form">
		
	<div class="instructions">
		<h3 class="double-border bottom">Applying to Join This <?php echo ucfirst( $grammar ); ?></h2>
		<ul>
			<li>You can request <?php echo $grammar; ?> membership using the form below.</li>
			<li>Please leave any information which you believe to be useful in describing your request to join this <?php echo $grammar; ?>.</li>
			<li>Whether or not your request is accepted will depend on the recruitment policies of the specific <?php echo $grammar; ?> in question.</li>
			<li>As a general rule of thumb, the more relevant information you provide, the higher the likelihood your request will be approved.</li>
		</ul>
	</div>
	
	<ol class="group-edit-list">
		<li class="textfield">
			<p>Application Request:</p>
			<?php // Load the TinyMCE Editor
			wp_editor( '' , 'group-request-membership-comments', array(
				'media_buttons' => false,
				'wpautop'		=> true,
				'editor_class'  => 'group-request-membership-comments',
				'quicktags'		=> false,
				'teeny'			=> true,
				) );?>
		</li>
	
		<?php do_action( 'bp_group_request_membership_content' ); ?>
		<li class="submit">
			<button type="submit" name="group-request-send" id="group-request-send"><i class="icon-envelope-alt"></i>Send Request</button>
		</li>
		
		<li class="hidden">
			<?php wp_nonce_field( 'groups_request_membership' ); ?>		
		</li>
	</ol>
</form>
<?php else : ?>
<p class="message">You have already applied to join this <?php echo $grammar; ?>!</p>
<?php endif; ?>