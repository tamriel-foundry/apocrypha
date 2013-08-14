/*! Load bbPress Topics and Replies */
$( '#forums' ).on( "click" , "nav.ajaxed a.page-numbers" , function(event){
	
	// Declare some stuff
	var curPage = newPage = postid = tooltip = dir = '';
	var button	= $(this);
	var nav		= button.parent().parent();
			
	// Prevent default pageload
	event.preventDefault();
	
	// Prevent further clicks
	nav.css( "pointer-events" , "none" );
	
	// Get the pagination context
	type		= nav.data('type');
	id 			= nav.data('id');
	baseURL		= window.location.href.replace( window.location.hash , '' );
	
	// Get the current page number
	curPage = parseInt( $( ".page-numbers.current" ).text() );
	
	// Get the requested page number
	newPage	= parseInt( button.text() );
	if ( button.hasClass( 'next' ) ) {
		newPage = curPage+1;
	} else if ( button.hasClass( 'prev' ) ) {
		newPage = curPage-1;
	}
	
	// Display a loading tooltip
	button.html('<i class="icon-spinner icon-spin"></i>');
	
	// Load new replies
	if ( 'replies' == type ) {

		// Send an AJAX request for more replies
		$.post( ajaxurl , {
				'action'	: 'apoc_load_replies',
				'type'		: type,
				'id'		: id,
				'paged'		: newPage,
				'baseurl'	: baseURL,
			},
			function( response ) {
			
				// If we successfully retrieved comments
				if( response != '0' ) {
				
					// Do some beautiful jQuery
					$('ol#topic-' + id ).fadeOut('slow').promise().done(function() {
						nav.remove();
						$('ol#topic-' + id ).empty().append(response);
						$('ol#topic-' + id ).after( $( 'nav.forum-pagination' ) );
						$('html, body').animate({ scrollTop: $( "#forums" ).offset().top }, 600 );
						$('ol#topic-' + id ).hide().fadeIn('slow');
						$( '#respond' ).show();
					});
					
					// Change the URL in the browser
					if ( 1 == curPage )
						newURL = baseURL + 'page/' + newPage + '/';
					else if ( 1 == newPage )
						newURL = baseURL.replace( "/page/" + curPage , "" );
					else
						newURL = baseURL.replace( "page/" + curPage, "page/" + newPage );
					window.history.replaceState( { 'id' : id , 'paged' : curPage } , document.title , newURL );
				}
			}
		);
		
	} // Load new topics
	else if ( 'topics' == type ) {

		// Send an AJAX request for more topics
		$.post( ajaxurl , {
				'action'	: 'apoc_load_topics',
				'type'		: type,
				'id'		: id,
				'paged'		: newPage,
				'baseurl'	: baseURL,
			},
			function( response ) {
			
				// If we successfully retrieved topics
				if( response != '0' ) {
				
					// Do some beautiful jQuery
					$('ol#forum-' + id ).fadeOut('slow').promise().done(function() {
						nav.remove();
						$('ol#forum-' + id ).empty().append(response);
						$('ol#forum-' + id ).after( $( 'nav.forum-pagination' ) );
						$('html, body').animate({ scrollTop: $( "#forums" ).offset().top }, 600 );
						$('ol#forum-' + id ).hide().fadeIn('slow');
						$( '#respond' ).show();
					});
					
					// Change the URL in the browser
					if ( 1 == curPage )
						newURL = baseURL + 'page/' + newPage + '/';
					else if ( 1 == newPage )
						newURL = baseURL.replace( "/page/" + curPage , "" );
					else
						newURL = baseURL.replace( "page/" + curPage, "page/" + newPage );
					window.history.replaceState( { 'id' : id , 'paged' : curPage } , document.title , newURL );				
				}
			}
		);	
	}
});

/*! Submit New bbPress Topic */
$( ".forum form#new-post" ).submit( function( event ) {

	// Get the form
	var error = '';
	var form 		= $(this);
	var button		= $( '#bbp_topic_submit' 	, form );
	var textarea	= $( '#bbp_topic_content' 	, form );
	var title		= $( '#bbp_topic_title' 	, form );
	
	// Prevent double posting
	button.attr( 'disabled' , "disabled" );
	
	// Create a feedback notice if one doesn't already exist
	if ( $( '#topic-notice' ).length == 0 ) {
		form.prepend('<div id="topic-notice"></div>');
		$( '#topic-notice' ).hide();
	}

	// Give a tooltip
	button.html( '<i class="icon-spinner icon-spin"></i>Submitting ...' );
	
	// Save content from TinyMCE into the hidden form textarea
	tinyMCE.triggerSave();
	
	// Make sure the form isn't empty
	if ( '' == title.val() ) {
		error = "Your topic must have a title!";
	} else if ( '' == textarea.val() ) {
		error = "You didn't write anything!";			
	}
	
	// If something went wrong, stop from submitting the POST
	if ( error ) {
		event.preventDefault();
		
		// Display the error
		$( '#topic-notice' ).addClass('error').text(error).fadeIn('slow');
		button.removeAttr( 'disabled' );
		
		// Re-enable the form
		button.removeAttr( 'disabled' );
		button.html( '<i class="icon-pencil"></i>Post New Topic' );
	}
});


/*! Submit New bbPress Reply */
$( ".topic form#new-post" ).submit( function( event ) {

	// Get the form
	var error = data =  '';
	var form 		= $(this);
	var button		= $( '#bbp_reply_submit' 	, form );
	var textarea	= $( '#bbp_reply_content' 	, form );
	var topic_id	= $( '#bbp_topic_id'		, form ).val();
	
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
	if ( '' == textarea.val() ) {
		error = "You didn't write anything!";			
	}
		
	// If there's been no error so far, go ahead and submit the AJAX
	if( !error ) {
	
		// Change the hidden "action" input to point to our AJAX handler
		$( 'input#bbp_post_action' ).attr( 'value' , "apoc_bbp_reply" );

		// Serialize the data
		data = form.serialize();
	
		// Give a tooltip
		button.html( '<i class="icon-spinner icon-spin"></i>Submitting ...' );
		
		// Submit the comment form to the wordpress handler
		$.ajax({
			url 	: ajaxurl,
			type	: 'post',
			data	: form.serialize(),
			success	: function( data ) {
				
				// Display the new comment with sexy jQuery
				$( '#respond' ).slideUp('slow' , function() {
					$( 'ol#topic-' + topic_id ).append( data );
					$( 'ol#topic-' + topic_id + ' li.reply:last-child' ).hide().slideDown('slow');
	
					// Clear the editor
					tinyMCE.activeEditor.setContent('');
					tinyMCE.triggerSave();
					
					// Re-enable the form
					button.removeAttr( 'disabled' );
					button.html( '<i class="icon-pencil"></i>Post Reply' );
					
				});			
			},
			error 	: function( jqXHR , textStatus , errorThrown ) {
					error = "An error occurred during posting.";
			},
		});	
	}
	
	// If there was an error at any point, display it
	if ( error ) {
		$( '#topic-notice' ).addClass('error').text(error).fadeIn('slow');
		button.removeAttr( 'disabled' );
		
		// Re-enable the form
		button.removeAttr( 'disabled' );
		button.html( '<i class="icon-pencil"></i>Post Reply' );
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