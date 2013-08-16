/*! AJAX Posts Loop */
$( '#posts' ).on( "click" , "nav.ajaxed a.page-numbers" , function(event){
	
	// Declare some stuff
	var curPage = newPage = type = id = tooltip = dir = '';
	var button	= $(this);
	var nav		= button.parent().parent();
	
	// Prevent default pageload
	event.preventDefault();
	
	// Prevent further clicks
	nav.css( "pointer-events" , "none" );
	
	// Get the pagination context
	type 	= $( 'nav.pagination' ).data('type');
	id 		= $( 'nav.pagination' ).data('id');
	baseURL	= window.location.href.replace( window.location.hash , '' );
	
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
					nav.remove();
					$('#posts').empty().append(response);
					$('html, body').animate({ scrollTop: $( "#content" ).offset().top }, 600 );
					$('#posts').hide().fadeIn('slow');				
				});			
			}
			
				// Change the URL in the browser
				if ( 1 == curPage )
					newURL = baseURL + 'page/' + newPage + '/';
				else if ( 1 == newPage )
					newURL = baseURL.replace( "/page/" + curPage, "" );
				else
					newURL = baseURL.replace( "/page/" + curPage, "/page/" + newPage );
				window.history.replaceState( { 'id' : id , 'paged' : curPage } , document.title , newURL );			
		}
	);	
});