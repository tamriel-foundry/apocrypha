/*! Contact Us Form AJAX Handling */
;$(document).ready(function(){
	$( "form#contact-form" ).submit( function( event ) {

		// Get the form
		var error		= '';
		var form 		= $(this);
		var button		= $( '#submit' , form );
		var textarea	= $( '#comments' , form );
	
		// Prevent the default action
		event.preventDefault();
		
		// Prevent double posting
		button.attr( 'disabled' , "disabled" );

		// Create a feedback notice if one doesn't already exist
		if ( $( '#contact-notice' ).length == 0 ) {
			$(this).before('<div id="contact-notice"></div>');
		}
		$( '#contact-notice' ).hide();
		
		// Save content from TinyMCE into the hidden form textarea
		tinyMCE.triggerSave();
		
		// Validate the form
		var data = {};
		$.each( form.serializeArray() , function( _ , kv ) {
			data[kv.name] = kv.value;
		});
		
		// Captcha
		if ( data.checking.length )
			error = "Die Robot!";
			
		// Name
		if ( !data.name.length )
			error = "Please enter your name!";
			
		// Email
		if ( !data.email.length )
			error = "Please enter your email address!";
		var emails = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		elseif ( !emails.test( data.email ) )
			error = "Please enter a valid email address!";
			
		// Comment
		if ( !data.comments.length )
			error = "Please leave a comment!";
			
		// If there was an error, throw it
		if ( error ) {
			$( '#contact-notice' ).addClass( 'error' ).text( error );
		}
		
		// Otherwise, do some AJAX
		else {
		
			// Give a tooltip
			button.html( '<i class="icon-spinner icon-spin"></i>Sending...' );
			
			// Submit the comment form to the wordpress handler
			$.ajax({
				url 	: ajaxurl,
				type	: 'post',
				data	: form.serialize(),
				success	: function( data ) {
							
					// Display the new comment with sexy jQuery
					form.slideUp();
					$( '#contact-notice' ).text( 'Contact submission successful, thank you for your comments!').fadeIn();
				},
				error 	: function( jqXHR , textStatus , errorThrown ) {
					$( '#contact-notice' ).addClass( 'error' ).text( 'Something went wrong...' ).fadeIn();
				},
			});				
		}
});