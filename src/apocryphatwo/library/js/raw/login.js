/*! Admin Bar Login Tooltip */
$('#top-login-form').submit( function(){
	
	// Fake AJAX functionality with a login tooltip
	$('#login-submit').attr('disabled', 'disabled');
	$('#login-submit').html('<i class="icon-spinner icon-spin"></i>Log In');
});

// Give a logout tooltip
$('#top-login-logout').click( function(){
	$(this).html('<i class="icon-spinner icon-spin"></i>Logging Out');
});