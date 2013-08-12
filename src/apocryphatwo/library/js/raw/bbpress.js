/*! Submit Topic */
$( ".#bbp_topic_submit" ).click( function( event ) {

	// Give a tooltip
	$(this).html( '<i class="icon-pencil"></i>Submitting ...' );
}

/*! Submit Reply */
$( ".topic form#new-post" ).submit( function( event ) {

	// Get the form
	var error = data =  '';
	var form 		= $(this);
	var button		= $( '#bbp_topic_submit' 	, form );
	var textarea	= $( '#bbp_topic_content' 	, form );
	var title		= $( '#bbp_topic_title' 	, form );
	
	// Prevent the default action
	event.preventDefault();
	
	// Prevent double posting
	button.attr( 'disabled' , "disabled" );
	
	// Create a feedback notice if one doesn't already exist
	if ( $( '#topic-notice' ).length == 0 ) {
		form.prepend('<div id="topic-notice"></div>');
		$( '#topic-notice' ).hide();
	}
	
	// Save content from TinyMCE into the hidden form textarea
	tinyMCE.triggerSave();
	
	// Make sure the form isn't empty
	if ( '' == title.val() ) {
		error = "Your topic must have a title!";
	} else if ( '' == textarea.val() ) {
		error = "You didn't write anything!";			
	}
			
	// If there's been no error so far, go ahead and submit the AJAX
	if( !error ) {
	
		data = form.serialize();
	
		alert( "no error, trying AJAX" );
		alert( "Thread data: " + data );
	
		// Give a tooltip
		button.html( '<i class="icon-pencil"></i>Submitting ...' );
		
		/*
		// Submit the comment form to the wordpress handler
		$.ajax({
			url 	: submitURL,
			type	: 'post',
			data	: form.serialize(),
			success	: function( data ) {
						
				// Display the new comment with sexy jQuery
				$( '#respond' ).slideUp('slow' , function() {
					$( 'ol#comment-list' ).append( data );
					$( '#comments .discussion-header' ).removeClass( 'noreplies' );
					$( 'ol#comment-list li.reply:last-child' ).hide().slideDown('slow');
	
					// Clear the editor
					tinyMCE.activeEditor.setContent('');
					tinyMCE.triggerSave();
					
					// Re-enable the form
					button.removeAttr( 'disabled' );
					button.html( '<i class="icon-pencil"></i>Post Comment' );
				});					
			},
			error 	: function( jqXHR , textStatus , errorThrown ) {
					error = "An error occurred during posting.";
			},
		}); */
		
	}
	
	// If there was an error at any point, display it
	if ( error ) {
		$( '#topic-notice' ).addClass('error').text(error).fadeIn('slow');
		button.removeAttr( 'disabled' );
		
		// Re-enable the form
		button.removeAttr( 'disabled' );
		button.html( '<i class="icon-pencil"></i>Post New Topic' );
	}
	
});



/*! Tab Into TinyMCE From Topic Title */
$( '#bbp_topic_title' ).bind( 'keydown.editor-focus', function(e) {
	if ( e.which !== 9 )
		return;

	if ( !e.ctrlKey && !e.altKey && !e.shiftKey ) {
		if ( typeof( tinymce ) !== 'undefined' ) {
			if ( ! tinymce.activeEditor.isHidden() ) {
				var editor = tinymce.activeEditor.editorContainer;
				$( '#' + editor + ' td.mceToolbar > a' ).focus();
			} else {
				$( 'textarea.bbp-the-content' ).focus();
			}
		} else {
			$( 'textarea.bbp-the-content' ).focus();
		}

		e.preventDefault();
	}
});