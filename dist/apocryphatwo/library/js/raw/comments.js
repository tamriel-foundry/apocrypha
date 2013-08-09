/*! Delete Comments */
$(document).ready(function(){
    $("a.delete-comment-link").click( function() {
	
		/* Confirm the user's desire to delete the comment */
		confirmation = confirm("Permanently delete this comment?");
		if(confirmation){
		
			/* Visual tooltip */
			button = $(this);
			button.text('Deleting...');
			
			/* Get the arguments */
			commentid	= $(this).data('id');
			nonce		= $(this).data('nonce');

			/* Submit the POST AJAX */
			$.post( ajaxurl, { 
				'action'	: 'apoc_delete_comment',
				'_wpnonce'	: nonce,
				'commentid' : commentid,
				}, 
				function( resp ){	
					if( resp ){	
						$( 'li#comment-' + commentid + ' div.reply-body' ).slideUp( 'slow', function() { 
							$( 'li#comment-' + commentid ).remove(); 
						});						
					}
				}
			);
		}
	});
});



/*! Insert Comments */
$(document).ready( function() {
    $( "form#commentform" ).submit( function( event ) {
	
		// Get the form
		var error		= '';
		var form 		= $(this);
		var submitURL	= form.attr('action');
		var button		= $( '#submit' , form );
		var textarea	= $( '#comment' , form );
		
		// Prevent the default action
		event.preventDefault();
		
		// Prevent double posting
		button.attr( 'disabled' , "disabled" );
		
		// Create a feedback notice
		button.parent().prepend('<div id="comment-notice"></div>');
		$( '#comment-notice' ).hide();
		
		// Save content from TinyMCE into the hidden form textarea
		tinyMCE.triggerSave();
		
		// Make sure the form isn't empty
		if ( '' == textarea.val() ) {
			error = "You didn't write anything!";			
		}
				
		// If there's been no error so far, go ahead and submit the AJAX
		if( !error ) {
		
			// Give a tooltip
			button.attr( 'value' , "Submitting..." );
			
			// Submit the comment form to the wordpress handler
			$.ajax({
				url 	: submitURL,
				type	: 'post',
				data	: form.serialize(),
				success	: function( data ) {
							
							// Display the new comment with sexy jQuery
							$( '#respond' ).slideUp('slow' , function() {
								$( '#respond' ).remove();
								$( 'ol#comment-list' ).append( data );
								$( 'ol#comment-list li.reply:last-child' ).hide().slideDown('slow');
							});
							
				},
				error 	: function( jqXHR , textStatus , errorThrown ) {
							error = "An error occurred during posting.";
				},
			});
		}
		
		// If there was an error at any point, display it
		if ( error ) {
			$( '#comment-notice' ).addClass('error').text(error).fadeToggle('slow');
			button.removeAttr( 'disabled' );
		}

	});
});