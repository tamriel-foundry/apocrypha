/*! Define Some Constants */
var	siteurl		= 'http://localhost/tamrielfoundry/';
var themeurl	= siteurl + 'wp-content/themes/apocrypha/';
var ajaxurl 	= siteurl + 'wp-admin/admin-ajax.php';
var $			= jQuery;

/*! Admin bar AJAX login function */
;$(document).ready(function(){$("#top-login-form").submit(function(){$("input#login-submit").attr("disabled","disabled");$("input#login-submit").attr("value","...");$.ajax({type:"POST",dataType:"json",data:$(this).serialize(),url:ajaxurl,success:function(a){if(a.success==1){window.location=a.redirect}else{$("input#login-submit").removeAttr("disabled");$("input#login-submit").attr("value","Log In");$("#top-login-error").html(a.error);$("#top-login-error").fadeToggle("slow")}}});return false})});

/*! Buddypress Frontend Notifications */
$(document).ready(function(){
    $("a.clear-notification").click( function( event ){

		// Get some info about what we are doing
		var button	= $(this);
        var nonce	= get_var_in_url( button.attr('href') , '_wpnonce' );
		var notid 	= get_var_in_url( button.attr('href') , 'notid' );
		var type 	= get_var_in_url( button.attr('href') , 'type' );

		// Prevent default
		event.preventDefault();

		// Tooltip
		button.removeAttr('href');
		button.html(' &#x2713;' );

		// Submit the POST AJAX
		$.post( ajaxurl, {
				'action'	: 'apoc_clear_notification',
				'_wpnonce'	: nonce,
				'notid' 	: notid,
			},
			function( response ){
				if( response ){

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
						title = title.replace( count , count-1 );
					} else {
						title = title.replace(/\[(.*)\]/,'');
					}
					document.title = title;
				}
			}
		);
    });

	// Helper function to get url variables
	function get_var_in_url(url,name){
		var urla = url.split( "?" );
		var qvars = urla[1].split( "&" );
		for( var i=0; i < qvars.length; i++ ){
			var qv = qvars[i].split( "=" );
			if( qv[0] == name )
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
;$(document).ready(function(){$("a.backtotop").click(function(){$("html, body").animate({scrollTop:0},600);return false})});

/*! Reply Link Scrolling */
;$(document).ready(function(){$("a.reply-link").click(function(b){var a=$(this).attr("href");$("html, body").animate({scrollTop:$(a).offset().top},600);return false})});

/*! Forum Quotes */
;$(document).ready(function(){$("a.quote-link").click(function(b){b.preventDefault();var a=quoteParent=quoteSource=posttext=quote="";postid=$(this).data("id");author=$(this).data("author");date=$(this).data("date");a=$(this).attr("href");if("#forum-reply"==a){quoteParent="#post-"+postid;quoteSource="#content-post-"+postid;editor="bbp_reply_content"}else{if("#respond"==a){quoteParent="#comment-"+postid;quoteSource="#comment-"+postid+" .reply-content";editor="comment"}}if(window.getSelection){posttext=window.getSelection().toString()}else{if(document.selection&&document.selection.type!="Control"){posttext=document.selection.createRange().text}else{return}}if(""!=posttext){postlines=posttext.split(/\r?\n/);firstline=postlines[0];lastline=postlines[postlines.length-1];if(0==$(quoteSource).find(":contains("+firstline+")").length||0==$(quoteSource).find(":contains("+lastline+")").length){alert("This is not a valid quote selection. Either select a specific passage or select nothing to quote the full post.");return}}if(""==posttext){posttext=$(quoteSource).html().toString()}posttext=posttext.replace(/<ul id="bbp-reply-revision((.|\n)*?)(<\/ul>)/,"");posttext=posttext.replace(/<ul id="bbp-topic-revision((.|\n)*?)(<\/ul>)/,"");posttext=posttext.replace(/<div class="spoiler">((.|\n)*?)(<\/div>)/g,"");posttext=posttext.replace(/<img((.|\n)*?)(>)/g,"");posttext=posttext.replace(/<br>/g,"");posttext=posttext.replace(/&nbsp;/g,"");posttext=posttext.replace(/<button class="quote-toggle((.|\n)*?)(<\/button>)/g,"");posttext=posttext.replace(/display: none;/g,"");quote='\r\n\r\n[quote author="'+author+"|"+quoteParent.substring(1)+"|"+date+'"]';quote+="\r\n"+posttext;quote+="\r\n[/quote]\r\n\r\n&nbsp;";editor_html=document.getElementById(editor+"-html");switchEditors.switchto(editor_html);document.getElementById(editor).value+=quote;editor_tmce=document.getElementById(editor+"-tmce");switchEditors.switchto(editor_tmce);$("html, body").animate({scrollTop:$(a).offset().top},600)})});

/*! Collapsing Quotes */
;$(document).ready(function(){$("div.quote").children("div.quote").addClass("subquote");$("div.subquote").children("p.quote-author").append('<button class="quote-toggle button-dark">Expand Quote</button>');$("div.subquote").children().not("p.quote-author,div.subquote").hide();$("button.quote-toggle").click(function(){var a=newtext="";$(this).parent().parent().children().not("p.quote-author,div.subquote").slideToggle(500,"swing");a=$(this).text();newtext=(a=="Expand Quote")?"Collapse Quote":"Expand Quote";$(this).text(newtext)})});

/*! Collapsing Spoilers */
;$(document).ready(function(){$("div.spoiler").children("p.spoiler-title").append('<button class="spoiler-toggle button-dark">Reveal Spoiler</button>');$("div.spoiler").children().not("p.spoiler-title").hide();$("button.spoiler-toggle").click(function(){var a=newtext="";$(this).parent().parent().children().not("p.spoiler-title").slideToggle(500,"swing");a=$(this).text();newtext=(a=="Reveal Spoiler")?"Conceal Spoiler":"Reveal Spoiler";$(this).text(newtext)})});

/*! Delete Comments */
;$(document).ready(function(){$("a.delete-comment-link").click(function(){confirmation=confirm("Permanently delete this comment?");if(confirmation){button=$(this);button.text("Deleting...");commentid=$(this).data("id");nonce=$(this).data("nonce");$.post(ajaxurl,{action:"apoc_delete_comment",_wpnonce:nonce,commentid:commentid},function(a){if(a){$("li#comment-"+commentid+" div.reply-body").slideUp("slow",function(){$("li#comment-"+commentid).remove()})}})}})});

/*! Insert Comments */
;$(document).ready(function(){$("form#commentform").submit(function(f){var c="";var e=$(this);var a=e.attr("action");var d=$("#submit",e);var b=$("#comment",e);f.preventDefault();d.attr("disabled","disabled");d.parent().prepend('<div id="comment-notice"></div>');$("#comment-notice").hide();tinyMCE.triggerSave();if(""==b.val()){c="You didn't write anything!"}if(!c){d.attr("value","Submitting...");$.ajax({url:a,type:"post",data:e.serialize(),success:function(g){$("#respond").slideUp("slow",function(){$("#respond").remove();$("ol#comment-list").append(g);$("ol#comment-list li.reply:last-child").hide().slideDown("slow")})},error:function(g,i,h){c="An error occurred during posting."}})}if(c){$("#comment-notice").addClass("error").text(c).fadeToggle("slow");d.removeAttr("disabled")}})});