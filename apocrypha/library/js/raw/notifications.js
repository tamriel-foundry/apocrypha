/*! Buddypress Frontend Notifications */
$("a.clear-notification").click( function( event ){
	
	// Get some info about what we are doing 
	var button	= $(this);
	var nonce	= get_url_var( button.attr('href') , '_wpnonce' );
	var notid 	= get_url_var( button.attr('href') , 'notid' );
	var type 	= get_url_var( button.attr('href') , 'type' );
	
	// Prevent default
	event.preventDefault();		

	// Tooltip
	button.removeAttr('href');
	button.html('<i class="icon-spinner icon-spin"></i>' );
			
	// Submit the POST AJAX 
	$.post( apoc_ajax, {
			'action'	: 'apoc_clear_notification',
			'_wpnonce'	: nonce,
			'notid' 	: notid,
		},
		function( response ){
			if( response ){
				
				// Change the notification count and remove the notification
				var counter = $( "li#notifications-" + type + " span.notifications-number" );
				var count = parseInt( counter.text() );
				if ( count > 1 ) {
					counter.text( count - 1 );
					button.parent().remove();
				} else {
					counter.remove();
					button.parent().text("Notifications cleared!");
				}
				
				// Update the document title
				var title = $('title').text();
				count = title.split(']')[0].substr(1);
				if ( 1 < count ) {
					title = title.replace( count , count-1 );
				} else {
					title = title.replace(/\[.*\]/,'');
				}
				document.title = title;
			}
		}
	);
});

// Add the total notification count to the title
function title_notification_count() {
	var count = 0;
	$.each( ['activity','messages','groups','friends'] , function(index,type) {
		var target = $("li#notifications-"+type+" span.notifications-number");
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
