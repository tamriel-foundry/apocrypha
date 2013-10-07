<?php 
/** 
 * Apocrypha Contact Us Template
 * Template Name: Contact Form
 * Andrew Clayton
 * Version 1.0.0
 * 9-9-2013
 */
 
// Get info on the logged in user
$user 		= apocrypha()->user->data;
$user_id	= $user->ID;

// Let's do some basic PHP processing, in case JavaScript is disabled
if ( isset( $_POST['submitted'] ) ) {

	// Captcha
	if( !empty( $_POST['checking'] ) !== '' )
		$error = "Die Robot!";
	
	// Name	
	if( empty( $_POST['name'] ) )
		$error = "Please enter your name!";
	
	// Email
	if( empty( $_POST['email'] ) )
		$error = "Please enter your email address!";
	elseif ( filter_var( trim( $_POST['email'] ) , FILTER_VALIDATE_EMAIL ) === false )
		$error = "Please enter a valid email address!";
	
	// Comments
	if( empty( $_POST['comments'] ) )
		$error = "Please include a message!";	
	
	// Send the email
	if ( !$error ) {
		
		// Get the data
		$name 		= trim( $_POST['name'] );
		$email 		= trim( $_POST['name'] );
		$comments 	= stripslashes( trim( $_POST['comments'] ) );
		$copy		= $_POST['copy'];
		
		// Send mail
		$emailto	= 'admin@tamrielfoundry.com';
		$subject 	= 'Contact Form Submission from ' . $name;
		$body 		= "Name: $name \n\nEmail: $email \n\nComments: $comments";
		$headers[] 	= "From: $name <$email>\r\n";
		$headers[] 	= "Content-Type: text/html; charset=UTF-8";	
		wp_mail($emailto, $subject, $body, $headers);
		if( true == $copy ) {
			$subject 	= 'Tamriel Foundry Contact Form Submission';
			$headers[0] = 'From: Tamriel Foundry <admin@tamrielfoundry.com>';
			wp_mail($email, $subject, $body, $headers);
		}
	}
} ?>

<?php get_header(); ?>

	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
			<div id="contact-page" class="entry-content">
			
				<header class="entry-header <?php post_header_class(); ?>">
					<h1 class="entry-title"><?php entry_header_title( false ); ?></h1>
					<p class="entry-byline"><?php entry_header_description(); ?></p>
				</header>

				<div class="entry-content">
					<?php the_content(); ?>
				</div>
				
				<form action="<?php the_permalink(); ?>" id="contact-form" method="post">
					
					<?php if ( $error ) : ?>
						<div id="contact-error" class="error"><?php echo $error; ?></div>
					<?php endif; ?>
					
					<ol class="contact-list">
						
						<?php // If it's a registered user, get their info from the theme
						if ( $user_id > 0 ) : ?>
							<li>
								<blockquote>Hey there, <?php echo $user->display_name; ?>. What can we help you with?</blockquote>
								<input type="hidden" name="name" id="name" value="<?php echo $user->user_nicename; ?>"/>
								<input type="hidden" name="email" id="email" value="<?php echo $user->user_email; ?>"/>
							</li>
						<?php // Otherwise, give them name and email input fields
						else : ?>
							<li class="text">
								<label for="name"><i class="icon-user"></i>Your Name:</label>
								<input type="text" name="name" id="name" value="<?php if(isset($_POST['name'])) echo $_POST['name'];?>" size="50"/>
							</li>
							
							<li class="text">
								<label for="email"><i class="icon-envelope-alt"></i>Email Address:</label>
								<input type="text" name="email" id="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" size="50"/>
							</li>					
						<?php endif; ?>
						
						<li class="textarea">		
							<?php $thecontent = stripslashes($_POST['comments']);
							wp_editor( $thecontent, 'comments', array(
								'media_buttons' => false,
								'wpautop'		=> false,
								'editor_class'  => 'contact_form_comment',
								'quicktags'		=> false,
								) ); ?>
						</li>
							
						<li class="checkbox form-left">
							<input type="checkbox" name="copy" id="copy" value="true" <?php checked( $_POST['copy'] , true ); ?>>
							<label for="copy">Send a copy of this email to yourself?</label>
						</li>						
							
						
						<li class="submit form-right">
							<input type="hidden" name="submitted" value="true" />
							<input type="hidden" name="action" value="apoc_contact_form" />
							<button type="submit" id="submit">
								<i class="icon-pencil"></i>Send Message</i>
							</button>
						</li>

						<li class="honeypot">
							<input type="text" name="checking" id="checking" value=""/>
							<label for="checking">LEAVE EMPTY</label>
						</li>						
					</ol>				
				</form>			
			</div>
		<?php endwhile; endif; ?>
		
	</div><!-- #content -->
	<?php apoc_primary_sidebar(); ?>
<?php get_footer(); ?>