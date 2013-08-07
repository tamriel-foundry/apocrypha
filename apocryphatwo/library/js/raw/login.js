/*! Admin bar AJAX login function */
$(document).ready(function(){
	$('#top-login-form').submit( function(){
		
		// Prevent the user from taking any further action
		$('input#login-submit').attr('disabled', 'disabled');
		$('input#login-submit').attr('value', '...');
			
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
					$('input#login-submit').removeAttr('disabled');
					$('input#login-submit').attr('value', 'Log In');
					$('#top-login-error').html(result.error);
					$('#top-login-error').fadeToggle('slow');
				}
			}
		});
		
		// Prevent the default action
		return false;
	});
});