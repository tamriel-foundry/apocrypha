/*! Define Some Constants */
var	siteurl		= 'http://localhost/tamrielfoundry/';
var themeurl	= siteurl + 'wp-content/themes/apocrypha/';
var ajaxurl 	= siteurl + 'wp-admin/admin-ajax.php';
var $			= jQuery;


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

/*! Buddypress Frontend Notifications */
$(document).ready(function(){
    $("a.clear-notification").click( function(){
        
		// Get some info about what we are doing 
		var button	= $(this);
        var nonce	= get_var_in_url( button.attr('href') , '_wpnonce' );
		var notid 	= get_var_in_url( button.attr('href') , 'notid' );
		var type 	= get_var_in_url( button.attr('href') , 'type' );
		button.addClass('loading');
		        
		// Submit the POST AJAX 
		$.ajax({
			type: 'POST',
			url : ajaxurl,
			data : { 
				'action'	: 'apoc_clear_notification',
				'_wpnonce'	: nonce,
				'notid' 	: notid
			},
			success: function( response ){
				
				if( response == '1' ){
				
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
		});
		
		// Prevent the default pageload 
        return false;
    });
	
	// Helper function to get url variables
	function get_var_in_url(url,name){
		var urla=url.split("?");
		var qvars=urla[1].split("&"); //so we have an arry of name=val,name=val
		for(var i=0;i<qvars.length;i++){
			var qv=qvars[i].split("=");
			if(qv[0]==name)
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

/*! Back To Top Link Scrolling */
$(document).ready(function(){
		$('a.backtotop').click(function () {
			$('html, body').animate({scrollTop: 0 }, 600);
			return false;
		});
	});