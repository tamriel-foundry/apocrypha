/*! Admin bar AJAX login function */
$('#top-login-form').submit( function(){
	
	// Prevent the user from taking any further action
	$('#login-submit').attr('disabled', 'disabled');
	$('#login-submit').html('<i class="icon-unlock-alt"></i> ... ');
	
	// Send the request
	$.ajax({
		type: 'POST',
		dataType: 'json',
		data: $(this).serialize(),
		url: ajaxurl,
		success: function( result ){
			if (result.success == 1) {
				window.location = result.redirect;
			} else {
				$('#login-submit').removeAttr('disabled');
				$('#login-submit').html('<i class="icon-lock"></i>Log In');
				$('#top-login-error').html(result.error);
				$('#top-login-error').hide().fadeToggle('slow');
			}
		}
	});
	
	// Prevent the default action
	return false;
});

// Give a logout tooltip
$('#top-login-logout').click( function(){
	$(this).html('<i class="icon-lock"></i>Logging Out');
	});