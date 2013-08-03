/*! Admin bar AJAX login function */
jQuery(document).ready(function(){
	jQuery('#top-login-form').submit( function(){
	
		// Fade out the error dialogue
		jQuery('#top-login-error').fadeOut();
		
		// Prevent the user from taking any further action
		jQuery('input#login-submit').attr('disabled', 'disabled');
		jQuery('input#login-submit').attr('value', '...');
			
		// Send the request
		jQuery.ajax({
			type: 'POST',
			dataType: 'json',
			data: jQuery(this).serialize(),
			url: ajaxurl,
			success: function( result ){
				if (result.success == 1) {
					window.location = result.redirect;
				} else {
					jQuery('input#login-submit').removeAttr('disabled');
					jQuery('input#login-submit').attr('value', 'Log In');
					jQuery('#top-login-error').html(result.error);
					jQuery('#top-login-error').fadeToggle('slow');
				}
			}
		});
		
		// Prevent the default action
		return false;
	});
});