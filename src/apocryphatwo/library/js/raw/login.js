/*! Admin Bar Login Tooltip */
$('#top-login-form').submit( function(){
	$('#login-submit').attr('disabled', 'disabled');
	$('#login-submit').html('<i class="icon-spinner icon-spin"></i>Log In');
});
$('#top-login-logout').click( function(){
	$(this).html('<i class="icon-spinner icon-spin"></i>Logging Out');
});