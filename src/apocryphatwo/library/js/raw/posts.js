/*! AJAX Posts Loop */
$( '#content' ).on( "click" , "nav.ajaxed a.page-numbers" , function(event){
	
	// Declare some stuff
	var curPage = newPage = type = id = tooltip = dir = '';
	var button	= $(this);
	
	// Prevent default pageload
	event.preventDefault();
	
	// Get the pagination context
	type 	= $( 'nav.pagination' ).data('type');
	id 		= $( 'nav.pagination' ).data('id');
	baseURL	= window.location.href;
	
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
	dir = ( newPage > curPage ) ? ".next" : ".prev";
	tooltip = ( newPage > curPage ) ? "Loading &raquo;" : "&laquo; Loading";
	$( 'a.page-numbers' + dir ).html(tooltip);
		
	// Send an AJAX request for more posts
	$.post( ajaxurl , {
			'action'	: 'apoc_load_posts',
			'type'		: type,
			'id'		: id,
			'paged'		: newPage,
			'baseurl'	: baseURL,
		},
		function( response ) {
		
			// If we successfully retrieved posts
			if( response != '0' ) {
			
				// Do some beautiful jQuery
				$('.post').fadeOut('slow').promise().done(function() {
					$('.post').remove();
					$('nav.pagination').remove();
					$('#content').append(response);
					$('html, body').animate({ scrollTop: $( "#content" ).offset().top - 15 }, 600 );
					$('.post').hide().fadeIn('slow');				
				});			
			}
		}
	);	
});