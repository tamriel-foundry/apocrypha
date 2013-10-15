/*! Post Reporting */
$("#comments,#forums,#private-messages").on( "click" , "a.report-post" , function( event ){

	// Confirm the user's desire to report
	confirmation = confirm("Report this post? Please make sure this is a valid report.");
	if(confirmation){
				
		var type = postid = postnum = author = reason = '';
		
		reason = prompt( "Reason For Report" , "Why you are reporting this post..." );
		if ( "Why you are reporting this post..." == reason ) {
			reason = "No reason given by reporter.";
		}
	
		// Get the arguments
		type 	= $(this).data('type');
		postid 	= $(this).data('id');
		postnum	= $(this).data('number');
		user	= $(this).data('user');
		
		// Remove the button
		$(this).remove();
		
		// Submit the POST AJAX
		$.post( ajaxurl, { 
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
			});
		
		// Prevent the default pageload
		return false;
	}
});