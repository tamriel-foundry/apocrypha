/*! Back To Top Link Scrolling */
jQuery(document).ready(function(){
		jQuery('a.backtotop').click(function () {
			jQuery('html, body').animate({scrollTop: 0 }, 600);
			return false;
		});
	});