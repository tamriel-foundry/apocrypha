/*! Buddypress Frontend Notifications */
$("a.clear-notification").click( function( event ){

	// Prevent default
	event.preventDefault();		
	
	// Get some info about what we are doing 
	var button	= $(this);
	var type 	= button.data('type');
	var id 		= button.data('id');
	var count	= button.data('count') || 1;

	// Tooltip
	button.html('<i class="icon-spinner icon-spin"></i>' );
			
	// Submit the POST AJAX 
	$.post( apoc_ajax, {
			'action'	: 'apoc_clear_notification',
			'type'		: type,
			'id' 		: id,
			'count'		: count,
		},
		function( response ){
			if( response ){
				
				// Change the notification count and remove the notification
				var counter	= button.closest( 'li.notification-type' ).children('span.notifications-number');
				var total	= parseInt( counter.text() );				
				
				// Are we removing all notifications, or just some?
				if ( total - count > 0 ) {
					counter.text( total - count );
					button.parent().remove();
				} else {
					counter.remove();
					button.parent().text("Notifications cleared!");
				}
				
				// Update the document title
				var title 	= $('title').text();
				total 		= title.split(']')[0].substr(1);
				if ( total - count > 0 ) {
					title = title.replace( total , total - count );
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
