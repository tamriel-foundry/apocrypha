/*! Load Comments */
$( '#comments' ).on( "click" , "nav.ajaxed a.page-numbers" , function(event){
	
	// Declare some stuff
	var button	= $(this);
	var nav		= button.parent().parent();
			
	// Prevent default pageload
	event.preventDefault();
	
	// Prevent further clicks
	nav.css( "pointer-events" , "none" );
	
	// Get the pagination context
	var postid 		= $( 'nav.pagination' ).data('postid');
	var baseURL		= window.location.href.replace( window.location.hash , '' );
	
	// Get the current page number
	var curPage = parseInt( $( ".page-numbers.current" ).text() );
	
	// Get the requested page number
	var newPage	= parseInt( button.text() );
	if ( button.hasClass( 'next' ) ) {
		newPage = curPage+1;
	} else if ( button.hasClass( 'prev' ) ) {
		newPage = curPage-1;
	}
	
	// Display a loading tooltip
	button.html('<i class="icon-spinner icon-spin"></i>');
		
	// Send an AJAX request for more comments
	$.post( apoc_ajax , {
			'action'	: 'apoc_load_comments',
			'postid'	: postid,
			'paged'		: newPage,
			'baseurl'	: baseURL,
		},
		function( response ) {
		
			// If we successfully retrieved comments
			if( response != '0' ) {
			
				// Do some beautiful jQuery
				$('.reply').fadeOut('slow').promise().done(function() {
					$('nav.pagination').remove();
					$('ol#comment-list').empty().append(response);
					$( 'ol#comment-list' ).after( $( 'nav.pagination' ) );
					$('html, body').animate({ 
						scrollTop: $( "#comments" ).offset().top 
					}, 600 );
					$('ol#comment-list').hide().fadeIn('slow');
					$( '#respond' ).show();
				});	

				// Change the URL in the browser
				var newURL = "";
				if ( 1 == curPage )
					newURL = baseURL + 'comment-page-' + newPage;
				else if ( 1 == newPage )
					newURL = baseURL.replace( "/comment-page-" + curPage , "" );
				else
					newURL = baseURL.replace( "/comment-page-" + curPage , "/comment-page-" + newPage );
				window.history.replaceState( { 'type' : 'comments' , 'id' : postid , 'paged' : curPage } , document.title , newURL );				
			}
		}
	);	
});

/*! Insert Comments */
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
	
	// Create a feedback notice if one doesn't already exist
	if ( $( '#comment-notice' ).length === 0 ) {
		$(this).prepend('<div id="comment-notice"></div>');
		$( '#comment-notice' ).hide();
	}
	
	// Save content from TinyMCE into the hidden form textarea
	tinyMCE.triggerSave();
	
	// Make sure the form isn't empty
	if ( textarea.val() === "" ) {
		error = "You didn't write anything!";	
	}
			
	// If there's been no error so far, go ahead and submit the AJAX
	if( !error ) {
	
		// Give a tooltip
		button.html( '<i class="icon-spinner icon-spin"></i>Submitting...' );
		
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
		});
	}
	
	// If there was an error at any point, display it
	if ( error ) {
		$( '#comment-notice' ).addClass('error').text(error).fadeIn('slow');
		button.removeAttr( 'disabled' );
		
		// Re-enable the form
		button.removeAttr( 'disabled' );
		button.html( '<i class="icon-pencil"></i>Post Comment' );
	}
	
});

/*! Delete Comments */
$( 'ol#comment-list' ).on( "click" , "a.delete-comment-link" , function(event){

	// Prevent the default action
	event.preventDefault();

	// Confirm the user's desire to delete the comment
	var confirmation = confirm("Permanently delete this comment?");
	if(confirmation){
	
		// Visual tooltip
		var button = $(this);
		button.html('<i class="icon-spinner icon-spin"></i>Deleting');
		
		// Get the arguments
		var commentid	= $(this).data('id');
		var nonce		= $(this).data('nonce');

		// Submit the POST AJAX
		$.post( apoc_ajax, { 
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