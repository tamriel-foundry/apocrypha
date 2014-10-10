/*! Post Reporting */
$("#comments,#forums,#private-messages").on( "click" , "a.report-post" , function( event ){

	// Prevent default
	event.preventDefault();

	// Confirm the user's desire to report
	var confirmation = confirm("Report this post? Please make sure this is a valid report.");
	if(confirmation){
		
		// Get the reason for reporting
		var reason = prompt( "Reason For Report" , "Why you are reporting this post..." );
		if ( "Why you are reporting this post..." == reason ) {
			reason = "No reason given by reporter.";
		}
	
		// Get the arguments
		var type 	= $(this).data('type');
		var postid 	= $(this).data('id');
		var postnum	= $(this).data('number');
		var user	= $(this).data('user');
		
		// Remove the button
		$(this).remove();
		
		// Submit the POST AJAX
		$.post( apoc_ajax, { 
				'action'	: 'apoc_report_post',
				'type'		: type,
				'id' 		: postid,
				'num'		: postnum,
				'user'		: user,
				'reason'	: reason,
				},
			function(resp){
				if( resp == 1 ){
					alert('Report sent successfully, thank you.');
				}
			}
		);
	}
});