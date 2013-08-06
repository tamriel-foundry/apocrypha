/*! Define Some Constants */
var	siteurl		= 'http://localhost/tamrielfoundry/';
var themeurl	= siteurl + 'wp-content/themes/apocrypha/';
var ajaxurl 	= siteurl + 'wp-admin/admin-ajax.php';

/*! Admin bar AJAX login function */
jQuery(document).ready(function(){
	jQuery('#top-login-form').submit( function(){
		
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

/*! Buddypress Frontend Notifications */
jQuery(document).ready(function(){
    var jq=jQuery;
    jq("a.clear-notification").click( function(){
        
		// Get some info about what we are doing 
		var button	= jq(this);
        var nonce	= get_var_in_url( button.attr('href') , '_wpnonce' );
		var notid 	= get_var_in_url( button.attr('href') , 'notid' );
		var type 	= get_var_in_url( button.attr('href') , 'type' );
		button.addClass('loading');
		        
		// Submit the POST AJAX 
		jq.ajax({
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
					counter = jq( "li#notifications-" + type + " span.notifications-number" );
					count = parseInt( counter.text() );
					if ( count > 1 ) {
						counter.text( count - 1 );
						button.parent().remove();
					} else {
						counter.remove();
						button.parent().text("Notifications cleared!");
					}
					
					// Update the document title
					title = jq('title').text();
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
		jq.each( ['activity','messages','groups','friends'] , function(index,type) {
			target = jq("li#notifications-"+type+" span.notifications-number");
			if ( target.is('*') ) {
				count = count + parseInt( target.text() );
			}
		});
		
		// If we have notifications, add them to the title
		if ( count > 0 ) {
			var doctitle = jq('title').text().replace(/\[.*\]/,'');
			doctitle = "["+count+"]"+doctitle;
			jq('title').text(doctitle);
		}
	}
	// Run it once on document ready
	title_notification_count();
});

/*! Back To Top Link Scrolling */
jQuery(document).ready(function(){
		jQuery('a.backtotop').click(function () {
			jQuery('html, body').animate({scrollTop: 0 }, 600);
			return false;
		});
	});