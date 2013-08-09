/*! Buddypress Frontend Notifications */
$(document).ready(function(){
    $("a.clear-notification").click( function( event ){
        
		// Get some info about what we are doing 
		var button	= $(this);
        var nonce	= get_var_in_url( button.attr('href') , '_wpnonce' );
		var notid 	= get_var_in_url( button.attr('href') , 'notid' );
		var type 	= get_var_in_url( button.attr('href') , 'type' );
		
		// Prevent default
		event.preventDefault();		

		// Tooltip
		button.removeAttr('href');
		button.html(' &#x2713;' );
		        
		// Submit the POST AJAX 
		$.post( ajaxurl, {
				'action'	: 'apoc_clear_notification',
				'_wpnonce'	: nonce,
				'notid' 	: notid,
			},
			function( response ){
				if( response ){
					
					alert( 'we have response' );
				
					// Change the notification count and remove the notification
					counter = $( "li#notifications-" + type + " span.notifications-number" );
					count = parseInt( counter.text() );
					if ( count > 1 ) {
						counter.text( count - 1 );
						button.parent().remove();
					} else {
						counter.remove();
						button.parent().text("Notifications cleared!");
					}
					
					// Update the document title
					title = $('title').text();
					count = title.split(']')[0].substr(1);
					if ( 1 < count ) {
						title.replace( count , count-1 );
					} else {
						title.replace(/\[.*\]/,'');
					}
					document.title = title;
				}
			}
		);
    });
	
	// Helper function to get url variables
	function get_var_in_url(url,name){
		var urla = url.split( "?" );
		var qvars = urla[1].split( "&" );
		for( var i=0; i < qvars.length; i++ ){
			var qv = qvars[i].split( "=" );
			if( qv[0] == name )
				return qv[1];
		}
		return '';
	}
	
	// Add the total notification count to the title
	function title_notification_count() {
		count = 0;
		$.each( ['activity','messages','groups','friends'] , function(index,type) {
			target = $("li#notifications-"+type+" span.notifications-number");
			if ( target.is('*') ) {
				count = count + parseInt( target.text() );
			}
		});
		
		// If we have notifications, add them to the title
		if ( count > 0 ) {
			var doctitle = $('title').text().replace(/\[.*\]/,'');
			doctitle = "["+count+"]"+doctitle;
			$('title').text(doctitle);
		}
	}
	
	// Run it once on document ready
	title_notification_count();
});