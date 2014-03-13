/*! --------------------------------------- 
0.0 - DEFINE CONSTANTS
----------------------------------------- */
var	siteurl		= (window.location.host === 'localhost') ? 'http://localhost/tamrielfoundry/' : 'http://tamrielfoundry.com/';
var themeurl	= siteurl	+ 'wp-content/themes/apocrypha/';
var ajaxurl 	= siteurl	+ 'wp-admin/admin-ajax.php';
var apoc_ajax 	= themeurl	+ "library/ajax.php";
var $			= jQuery;

/*! jQuery Document Ready Functions */
$(document).ready(function(){

/*! --------------------------------------- 
1.0 - ADMIN BAR
----------------------------------------- */

/*! Admin Bar Login Tooltips */
$('#top-login-form').submit( function(){
	$('#login-submit').attr('disabled', 'disabled');
	$('#login-submit').html('<i class="icon-spinner icon-spin"></i>Log In');
});
$('#top-login-logout').click( function(){
	$(this).html('<i class="icon-spinner icon-spin"></i>Logging Out');
});

/*! Buddypress Frontend Notifications */
$("a.clear-notification").click( function( event ){

	// Prevent default
	event.preventDefault();		
	
	// Get some info about what we are doing 
	var button	= $(this);
	var type 	= button.data('type');
	var id 		= button.data('id');
	var count	= button.data('count') || 1;

	// Tooltip
	button.html('<i class="icon-spinner icon-spin"></i>' );
			
	// Submit the POST AJAX 
	$.post( apoc_ajax, {
			'action'	: 'apoc_clear_notification',
			'type'		: type,
			'id' 		: id,
			'count'		: count,
		},
		function( response ){
			if( response ){
				console.log(response);
				
				// Change the notification count and remove the notification
				var counter	= button.closest( 'li.notification-type' ).children('span.notifications-number');
				var total	= parseInt( counter.text() );				
				
				// Are we removing all notifications, or just some?
				if ( total - count > 0 ) {
					counter.text( total - count );
					button.parent().remove();
				} else {
					counter.remove();
					button.parent().text("Notifications cleared!");
				}
				
				// Update the document title
				var title 	= $('title').text();
				total 		= title.split(']')[0].substr(1);
				if ( total - count > 0 ) {
					title = title.replace( total , total - count );
				} else {
					title = title.replace(/\[.*\]/,'');
				}
				document.title = title;
			}
		}
	);
});

// Add the total notification count to the title
function title_notification_count() {
	var count = 0;
	$.each( ['activity','messages','groups','friends'] , function(index,type) {
		var target = $("li#notifications-"+type+" span.notifications-number");
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


/*! --------------------------------------- 
2.0 - NAVIGATION
----------------------------------------- */

/*! Top and Bottom Scrolling */
$("a.backtotop").click(function(){$("html, body").animate({scrollTop:0},600);return false});
;$("a.downtobottom").click(function(){$("html, body").animate({scrollTop:$(document).height()},600);return false});

/*! Donation Widget Verification */
$('form#donation-form').submit(function(){

	var user_id = $(this).children('input[name=user_id]').val();
	if ( parseInt( user_id ) === 0 ) { 
		if ( confirm( 'If you wish to recieve credit for your donation, please log in first. You may continue and donate anonymously if you wish!' ) ) return true; 
		else return false;
	}
	return true;
});


/*! --------------------------------------- 
3.0 - POSTS
----------------------------------------- */

/*! AJAX Posts Loop */
$( '#posts' ).on( "click" , "nav.ajaxed a.page-numbers" , function(event){
	
	// Declare some stuff
	var curPage = newPage = type = id = tooltip = dir = '';
	var button	= $(this);
	var nav		= button.parent().parent();
	
	// Prevent default pageload
	event.preventDefault();
	
	// Prevent further clicks
	nav.css( "pointer-events" , "none" );
	
	// Get the pagination context
	type 	= $( 'nav.pagination' ).data('type');
	id 		= $( 'nav.pagination' ).data('id');
	baseURL	= window.location.href.replace( window.location.hash , '' );
	
	// Get the current page number
	curPage = parseInt( $( ".page-numbers.current" ).text() );
	
	// Get the requested page number
	newPage	= parseInt( button.text() );
	if ( button.hasClass( 'next' ) ) {
		newPage = curPage+1;
	} else if ( button.hasClass( 'prev' ) ) {
		newPage = curPage-1;
	}
	
	// Display a loading tooltip
	button.html('<i class="icon-spinner icon-spin"></i>');
		
	// Send an AJAX request for more posts
	$.post( apoc_ajax , {
			'action'	: 'apoc_load_posts',
			'type'		: type,
			'id'		: id,
			'paged'		: newPage,
			'baseurl'	: baseURL,
		},
		function( response ) {
		
			// If we successfully retrieved posts
			if( response != '0' ) {
			
				// Do some beautiful jQuery
				$('.post').fadeOut('slow').promise().done(function() {
					nav.remove();
					$('#posts').empty().append(response);
					$('html').animate({ scrollTop: $( "#content" ).offset().top }, 600 );
					$('#posts').hide().fadeIn('slow');				
				});			
			}
			
				// Change the URL in the browser
				if ( 1 == curPage )
					newURL = baseURL + 'page/' + newPage + '/';
				else if ( 1 == newPage )
					newURL = baseURL.replace( "/page/" + curPage, "" );
				else
					newURL = baseURL.replace( "/page/" + curPage, "/page/" + newPage );
				window.history.replaceState( { 'id' : id , 'paged' : curPage } , document.title , newURL );			
		}
	);	
});

/*! Tabbed Containers */
;$("div.tab-content").not("div.active").hide();$("ul.tabs li a").click(function(){if(!$(this).parent().hasClass("current")&&!$(this).parent().hasClass("disabled")){var a=$(this).attr("href");$("ul.tabs li").removeClass("current");$(this).parent().addClass("current");$("div.tab-content").hide();$(a).fadeIn("slow")}return false});

/*! Collapsing FAQ Containers */
;$("div.faq-section header").append('<button class="faq-collapse"><i class="icon-expand"></i>Expand Section</button>');$("div.faq-section ul , div.faq-references ol").hide();$("button.faq-collapse").click(function(){var a=newtext="";$(this).parent().siblings("ul"|"ol").slideToggle(500,"swing");a=$(this).text();newtext=(a=="Expand Section")?'<i class="icon-collapse"></i>Collapse Section':'<i class="icon-expand"></i>Expand Section';$(this).html(newtext)});

/*! --------------------------------------- 
3.0 - COMMENTS
----------------------------------------- */

/*! Load Comments */
$( '#comments' ).on( "click" , "nav.ajaxed a.page-numbers" , function(event){
	
	// Declare some stuff
	var button	= $(this);
	var nav		= button.parent().parent();
			
	// Prevent default pageload
	event.preventDefault();
	
	// Prevent further clicks
	nav.css( "pointer-events" , "none" );
	
	// Get the pagination context
	var postid 		= $( 'nav.pagination' ).data('postid');
	var baseURL		= window.location.href.replace( window.location.hash , '' );
	
	// Get the current page number
	var curPage = parseInt( $( ".page-numbers.current" ).text() );
	
	// Get the requested page number
	var newPage	= parseInt( button.text() );
	if ( button.hasClass( 'next' ) ) {
		newPage = curPage+1;
	} else if ( button.hasClass( 'prev' ) ) {
		newPage = curPage-1;
	}
	
	// Display a loading tooltip
	button.html('<i class="icon-spinner icon-spin"></i>');
		
	// Send an AJAX request for more comments
	$.post( apoc_ajax , {
			'action'	: 'apoc_load_comments',
			'postid'	: postid,
			'paged'		: newPage,
			'baseurl'	: baseURL,
		},
		function( response ) {
		
			// If we successfully retrieved comments
			if( response != '0' ) {
			
				// Do some beautiful jQuery
				$('.reply').fadeOut('slow').promise().done(function() {
					$('nav.pagination').remove();
					$('ol#comment-list').empty().append(response);
					$( 'ol#comment-list' ).after( $( 'nav.pagination' ) );
					$('html').animate({ 
						scrollTop: $( "#comments" ).offset().top 
					}, 600 );
					$('ol#comment-list').hide().fadeIn('slow');
					$( '#respond' ).show();
				});	

				// Change the URL in the browser
				var newURL = "";
				if ( 1 == curPage )
					newURL = baseURL + 'comment-page-' + newPage;
				else if ( 1 == newPage )
					newURL = baseURL.replace( "/comment-page-" + curPage , "" );
				else
					newURL = baseURL.replace( "/comment-page-" + curPage , "/comment-page-" + newPage );
				window.history.replaceState( { 'type' : 'comments' , 'id' : postid , 'paged' : curPage } , document.title , newURL );				
			}
		}
	);	
});

/*! Insert Comments */
$( "form#commentform" ).submit( function( event ) {

	// Get the form
	var error		= '';
	var form 		= $(this);
	var submitURL	= form.attr('action');
	var button		= $( '#submit' , form );
	var textarea	= $( '#comment' , form );
	
	// Prevent the default action
	event.preventDefault();
	
	// Prevent double posting
	button.attr( 'disabled' , "disabled" );
	
	// Create a feedback notice if one doesn't already exist
	if ( $( '#comment-notice' ).length === 0 ) {
		$(this).prepend('<div id="comment-notice"></div>');
		$( '#comment-notice' ).hide();
	}
	
	// Save content from TinyMCE into the hidden form textarea
	tinyMCE.triggerSave();
	
	// Make sure the form isn't empty
	if ( textarea.val() === "" ) {
		error = "You didn't write anything!";	
	}
			
	// If there's been no error so far, go ahead and submit the AJAX
	if( !error ) {
	
		// Give a tooltip
		button.html( '<i class="icon-spinner icon-spin"></i>Submitting...' );
		
		// Submit the comment form to the wordpress handler
		$.ajax({
			url 	: submitURL,
			type	: 'post',
			data	: form.serialize(),
			success	: function( data ) {
						
				// Display the new comment with sexy jQuery
				$( '#respond' ).slideUp('slow' , function() {
					$( 'ol#comment-list' ).append( data );
					$( '#comments .discussion-header' ).removeClass( 'noreplies' );
					$( 'ol#comment-list li.reply:last-child' ).hide().slideDown('slow');
	
					// Clear the editor
					tinyMCE.activeEditor.setContent('');
					tinyMCE.triggerSave();
					
					// Re-enable the form
					button.removeAttr( 'disabled' );
					button.html( '<i class="icon-pencil"></i>Post Comment' );
				});					
			},
			error 	: function( jqXHR , textStatus , errorThrown ) {
					error = "An error occurred during posting.";
			},
		});
	}
	
	// If there was an error at any point, display it
	if ( error ) {
		$( '#comment-notice' ).addClass('error').text(error).fadeIn('slow');
		button.removeAttr( 'disabled' );
		
		// Re-enable the form
		button.removeAttr( 'disabled' );
		button.html( '<i class="icon-pencil"></i>Post Comment' );
	}
	
});

/*! Delete Comments */
$( 'ol#comment-list' ).on( "click" , "a.delete-comment-link" , function(event){

	// Prevent the default action
	event.preventDefault();

	// Confirm the user's desire to delete the comment
	var confirmation = confirm("Permanently delete this comment?");
	if(confirmation){
	
		// Visual tooltip
		var button = $(this);
		button.html('<i class="icon-spinner icon-spin"></i>Deleting');
		
		// Get the arguments
		var commentid	= $(this).data('id');
		var nonce		= $(this).data('nonce');

		// Submit the POST AJAX
		$.post( apoc_ajax, { 
			'action'	: 'apoc_delete_comment',
			'_wpnonce'	: nonce,
			'commentid' : commentid,
			}, 
			function( resp ){	
				if( resp ){	
					$( 'li#comment-' + commentid + ' div.reply-body' ).slideUp( 'slow', function() { 
						$( 'li#comment-' + commentid ).remove(); 
					});						
				}
			}
		);
	}
});

/*! Quote Button */
;$("#comments,#forums").on("click","a.quote-link",function(b){var a=quoteParent=quoteSource=posttext=quote="";b.preventDefault();a=$(this).data("context");postid=$(this).data("id");author=$(this).data("author");date=$(this).data("date");if("reply"==a){quoteParent="#post-"+postid;quoteSource="#post-"+postid+" .reply-content";editor="bbp_reply_content"}else{if("comment"==a){quoteParent="#comment-"+postid;quoteSource="#comment-"+postid+" .reply-content";editor="comment"}}if(window.getSelection){posttext=window.getSelection().toString()}else{if(document.selection&&document.selection.type!="Control"){posttext=document.selection.createRange().text}else{return}}if(""!=posttext){postlines=posttext.split(/\r?\n/);firstline=postlines[0];lastline=postlines[postlines.length-1];if(0==$(quoteSource).find(":contains("+firstline+")").length||0==$(quoteSource).find(":contains("+lastline+")").length){alert("This is not a valid quote selection. Either select a specific passage or select nothing to quote the full post.");return}}if(""==posttext){posttext=$(quoteSource).html()}posttext=posttext.replace(/<ul id="bbp-reply-revision((.|\n)*?)(<\/ul>)/,"");posttext=posttext.replace(/<ul id="bbp-topic-revision((.|\n)*?)(<\/ul>)/,"");posttext=posttext.replace(/<div class="spoiler">((.|\n)*?)(<\/div>)/g,"");posttext=posttext.replace(/<img((.|\n)*?)(>)/g,"");posttext=posttext.replace(/<br>/g,"");posttext=posttext.replace(/&nbsp;/g,"");posttext=posttext.replace(/<button class="quote-toggle((.|\n)*?)(<\/button>)/g,"");posttext=posttext.replace(/display: none;/g,"");quote='\r\n\r\n[quote author="'+author+"|"+quoteParent.substring(1)+"|"+date+'"]';quote+="\r\n"+posttext;quote+="\r\n[/quote]\r\n\r\n&nbsp;";editor_html=document.getElementById(editor+"-html");switchEditors.switchto(editor_html);document.getElementById(editor).value+=quote;editor_tmce=document.getElementById(editor+"-tmce");switchEditors.switchto(editor_tmce);$("html, body").animate({scrollTop:$("#respond").offset().top},600)});

/*! Collapsing Quotes */
;$("div.quote").children("div.quote").addClass("subquote");$("div.subquote").prepend('<button class="quote-toggle button-dark">Expand Quote</button>');$("div.subquote").children().not("p.quote-author,div.subquote,button").hide();$("button.quote-toggle").click(function(){var a=newtext="";$(this).parent().children().not("p.quote-author,div.subquote,button").slideToggle(500,"swing");a=$(this).text();newtext=(a=="Expand Quote")?"Collapse Quote":"Expand Quote";$(this).text(newtext)});

/*! Collapsing Spoilers */
;$("div.spoiler").prepend('<button class="spoiler-toggle button-dark">Reveal Spoiler</button>');$("div.spoiler").children().not("p.spoiler-title,button").hide();$("button.spoiler-toggle").click(function(){var a=newtext="";$(this).parent().children().not("p.spoiler-title,button").slideToggle(500,"swing");a=$(this).text();newtext=(a=="Reveal Spoiler")?"Conceal Spoiler":"Reveal Spoiler";$(this).text(newtext)});

/*! Reply Button */
;$("#comments,#forums").on("click","a.reply-link",function(a){a.preventDefault();$("html, body").animate({scrollTop:$("#respond").offset().top},600)});

/*! Show Author IP Address on Click */
;$("p.author-ip").hide();$("a.author-ip-toggle").click(function(){var a=$(this).parent().parent();a.find("p.author-ip").slideToggle()});

/*! --------------------------------------- 
4.0 - BBPRESS
----------------------------------------- */

/*! Load bbPress Topics and Replies
;$("#forums").on("click","nav.ajaxed a.page-numbers",function(c){var b=newPage=postid=tooltip=dir="";var a=$(this);var d=a.parent().parent();c.preventDefault();d.css("pointer-events","none");type=d.data("type");id=d.data("id");baseURL=window.location.href.replace(window.location.hash,"");b=parseInt($(".page-numbers.current").text());newPage=parseInt(a.text());if(a.hasClass("next")){newPage=b+1}else{if(a.hasClass("prev")){newPage=b-1}}a.html('<i class="icon-spinner icon-spin"></i>');if("replies"==type){$.post(ajaxurl,{action:"apoc_load_replies",type:type,id:id,paged:newPage,baseurl:baseURL},function(e){if(e!="0"){$("ol#topic-"+id).fadeOut("slow").promise().done(function(){d.remove();$("ol#topic-"+id).empty().append(e);$("ol#topic-"+id).after($("nav.forum-pagination"));$("html, body").animate({scrollTop:$("#forums").offset().top},600);$("ol#topic-"+id).hide().fadeIn("slow");$("#respond").show()});if(1==b){newURL=baseURL+"page/"+newPage+"/"}else{if(1==newPage){newURL=baseURL.replace("/page/"+b,"")}else{newURL=baseURL.replace("page/"+b,"page/"+newPage)}}window.history.replaceState({id:id,paged:b},document.title,newURL)}})}else{if("topics"==type){$.post(ajaxurl,{action:"apoc_load_topics",type:type,id:id,paged:newPage,baseurl:baseURL},function(e){if(e!="0"){$("ol#forum-"+id).fadeOut("slow").promise().done(function(){d.remove();$("ol#forum-"+id).empty().append(e);$("ol#forum-"+id).after($("nav.forum-pagination"));$("html, body").animate({scrollTop:$("#forums").offset().top},600);$("ol#forum-"+id).hide().fadeIn("slow");$("#respond").show()});if(1==b){newURL=baseURL+"page/"+newPage+"/"}else{if(1==newPage){newURL=baseURL.replace("/page/"+b,"")}else{newURL=baseURL.replace("page/"+b,"page/"+newPage)}}window.history.replaceState({id:id,paged:b},document.title,newURL)}})}}});  */

/*! Submit New bbPress Topic
;$(".forum form#new-post").submit(function(e){var b="";var d=$(this);var c=$("#bbp_topic_submit",d);var a=$("#bbp_topic_content",d);var f=$("#bbp_topic_title",d);c.attr("disabled","disabled");if($("#topic-notice").length==0){d.prepend('<div id="topic-notice"></div>');$("#topic-notice").hide()}c.html('<i class="icon-spinner icon-spin"></i>Submitting ...');tinyMCE.triggerSave();if(""==f.val()){b="Your topic must have a title!"}else{if(""==a.val()){b="You didn't write anything!"}}if(b){e.preventDefault();$("#topic-notice").addClass("error").text(b).fadeIn("slow");c.removeAttr("disabled");c.removeAttr("disabled");c.html('<i class="icon-pencil"></i>Post New Topic')}});  */

/*! Submit New bbPress Reply
;$(".topic form#new-post").submit(function(f){var c=data="";var e=$(this);var d=$("#bbp_reply_submit",e);var a=$("#bbp_reply_content",e);var b=$("#bbp_topic_id",e).val();f.preventDefault();d.attr("disabled","disabled");if($("#topic-notice").length==0){e.prepend('<div id="topic-notice"></div>');$("#topic-notice").hide()}tinyMCE.triggerSave();if(""==a.val()){c="You didn't write anything!"}if(!c){$("input#bbp_post_action").attr("value","apoc_bbp_reply");data=e.serialize();d.html('<i class="icon-spinner icon-spin"></i>Submitting ...');$.ajax({url:ajaxurl,type:"post",data:e.serialize(),success:function(g){$("#respond").slideUp("slow",function(){$("ol#topic-"+b).append(g);$("ol#topic-"+b+" li.reply:last-child").hide().slideDown("slow");tinyMCE.activeEditor.setContent("");tinyMCE.triggerSave();d.removeAttr("disabled");d.html('<i class="icon-pencil"></i>Post Reply')})},error:function(g,i,h){c="An error occurred during posting."}})}if(c){$("#topic-notice").addClass("error").text(c).fadeIn("slow");d.removeAttr("disabled");d.removeAttr("disabled");d.html('<i class="icon-pencil"></i>Post Reply')}});  */

/*! Tab Into TinyMCE From Topic Title */
;$("#bbp_topic_title").bind("keydown.editor-focus",function(b){if(b.which!==9){return}if(!b.ctrlKey&&!b.altKey&&!b.shiftKey){if(typeof(tinymce)!=="undefined"){if(!tinymce.activeEditor.isHidden()){var a=tinymce.activeEditor.editorContainer;$("#"+a+" td.mceToolbar > a").focus()}else{$("textarea.bbp-the-content").focus()}}else{$("textarea.bbp-the-content").focus()}b.preventDefault()}});

/*! bbPress Favorites / Subs */
;function bbp_ajax_call(d,e,c,a){var b={action:d,id:e,nonce:c};$.post(bbpTopicJS.bbp_ajaxurl,b,function(f){if(f.success){$(a).html(f.content)}else{if(!f.content){f.content=bbpTopicJS.generic_ajax_error}alert(f.content)}})}$("#subscription-controls").on("click","a.favorite-toggle",function(a){a.preventDefault();bbp_ajax_call("favorite",$(this).attr("data-topic"),bbpTopicJS.fav_nonce,"#subscription-controls #favorite-toggle")});$("#subscription-controls").on("click","a.subscription-toggle",function(a){a.preventDefault();bbp_ajax_call("subscription",$(this).attr("data-topic"),bbpTopicJS.subs_nonce,"#subscription-controls #subscription-toggle")});

/*! Post Reporting */
$("#comments,#forums,#private-messages").on( "click" , "a.report-post" , function( event ){

	// Prevent default
	event.preventDefault();

	// Confirm the user's desire to report
	var confirmation = confirm("Report this post? Please make sure this is a valid report.");
	if(confirmation){
		
		// Get the reason for reporting
		var reason = prompt( "Reason For Report" , "Why you are reporting this post..." );
		if ( "Why you are reporting this post..." == reason ) {
			reason = "No reason given by reporter.";
		}
	
		// Get the arguments
		var type 	= $(this).data('type');
		var postid 	= $(this).data('id');
		var postnum	= $(this).data('number');
		var user	= $(this).data('user');
		
		// Remove the button
		$(this).remove();
		
		// Submit the POST AJAX
		$.post( apoc_ajax, { 
				'action'	: 'apoc_report_post',
				'type'		: type,
				'id' 		: postid,
				'num'		: postnum,
				'user'		: user,
				'reason'	: reason,
				},
			function(resp){
				if( resp == 1 ){
					alert('Report sent successfully, thank you.');
				}
			}
		);
	}
});

/*! Trash Replies */
$("#forums").on("click","a.bbp-reply-trash-link",function( event ){
	
	// Prevent default
	event.preventDefault();

	// Confirm the user's desire to delete the comment
	var confirmation = confirm("Permanently delete this post?");
	if(confirmation){
	
		// Visual tooltip
		var button = $(this);
		button.html('<i class="icon-spinner icon-spin"></i>Deleting');
		
		// Get the arguments
		var reply_id	= get_url_var( button.attr('href') , 'reply_id' );
		var context		= get_url_var( button.attr('href') , 'action' 	);
		var nonce		= get_url_var( button.attr('href') , '_wpnonce' );

		// Submit the POST AJAX
		$.post( apoc_ajax, { 
			'action'	: 'apoc_delete_reply',
			'context'	: context,
			'reply_id'	: reply_id,
			'_wpnonce'	: nonce,
			},
			function( resp ){
				if( resp == "1" ){
					var thereply 	= button.parents('li.reply');
					var replybody	= thereply.children('div.reply-body');
					replybody.slideUp( 'slow', function() { 
						thereply.remove(); 
					});			
				}
			}
		);
	}
});


/*! --------------------------------------- 
5.0 - USERS
----------------------------------------- */

/*! Clear Infraction */
$("a.clear-infraction").click( function(event) {

	// Prevent default
	event.preventDefault();
		
	// Get some info about what we are doing
	var button	= $(this);
	var nonce	= get_url_var( button.attr('href') , '_wpnonce' );
	var id 		= get_url_var( button.attr('href') , 'id' );
	
	// Disable the button
	button.html('<i class="icon-spinner icon-spin"></i>Deleting').attr("disabled","disabled");

	// Submit the POST AJAX
	$.post( apoc_ajax, { 
			'action': 'apoc_clear_infraction',
			'_wpnonce': nonce,
			'id' : id,
		},
		function( resp ) {
			if( resp == 1 ) {
				$( "li#infraction-" + id ).slideUp();
			}
		}
	);
});

/*! Clear Mod Notes */
$("a.clear-mod-note").click( function(event) {

	// Prevent default
	event.preventDefault();
		
	// Get some info about what we are doing
	var button	= $(this);
	var nonce	= get_url_var( button.attr('href') , '_wpnonce' );
	var id 		= get_url_var( button.attr('href') , 'id' );
	
	// Disable the button
	button.html('<i class="icon-spinner icon-spin"></i>Deleting').attr("disabled","disabled");

	// Submit the POST AJAX
	$.post( apoc_ajax, { 
			'action': 'apoc_clear_mod_note',
			'_wpnonce': nonce,
			'id' : id,
		},
		function( resp ) {
			if( resp == 1 ) {
				$( "li#modnote-" + id ).slideUp();
			}
		}
	);
});



/*! Advanced Search Fields */
;$("ol.adv-search-fields").not("ol.active").hide();$("select#search-for").bind("change",function(){$("#search-results").slideUp();var a=$("select#search-for").val();var b=$("ol#adv-search-"+a);$("ol.adv-search-fields").removeClass("active").hide();b.addClass("active").fadeIn()});

/*! End Document Ready */
;});

/*! --------------------------------------- 
X.X - PROCEDURAL FUNCTIONS
----------------------------------------- */

/*! Parse URL Variables */
function get_url_var(b,a){var e=b.split("?");var d=e[1].split("&");for(var c=0;c<d.length;c++){var f=d[c].split("=");if(f[0]==a){return f[1]}}return""};


function wp_ajax_debug() {

	jQuery.post( ajaxurl , {
		'action'	: 'apoc_debug',
	},
	function( response ) {
		alert( response);
	} );
}


function apoc_ajax_debug() {

	jQuery.post( apoc_ajax, {
		'action'	: 'apoc_debug',
	},
	function( response ) {
		alert( response);
	} );
}
