/*! Back To Top Link Scrolling */
$(document).ready(function(){
		$('a.backtotop').click(function () {
			$('html, body').animate({scrollTop: 0 }, 600);
			return false;
		});
	});