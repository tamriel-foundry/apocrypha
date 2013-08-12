/*! Define Some Constants */
var	siteurl		= 'http://localhost/tamrielfoundry/';
var themeurl	= siteurl + 'wp-content/themes/apocrypha/';
var ajaxurl 	= siteurl + 'wp-admin/admin-ajax.php';
var $			= jQuery;

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

/*! jQuery Document Ready Functions */
;$(document).ready(function(){

/*! Admin bar AJAX login function */
;$("#top-login-form").submit(function(){$("#login-submit").attr("disabled","disabled");$("#login-submit").html('<i class="icon-unlock-alt"></i> ... ');$.ajax({type:"POST",dataType:"json",data:$(this).serialize(),url:ajaxurl,success:function(a){if(a.success==1){window.location=a.redirect}else{$("#login-submit").removeAttr("disabled");$("#login-submit").html('<i class="icon-lock"></i>Log In');$("#top-login-error").html(a.error);$("#top-login-error").fadeToggle("slow")}}});return false});$("#top-login-logout").click(function(){$(this).html('<i class="icon-lock"></i>Logging Out')});

/*! Buddypress Frontend Notifications */
;$("a.clear-notification").click(function(e){var a=$(this);var d=get_var_in_url(a.attr("href"),"_wpnonce");var c=get_var_in_url(a.attr("href"),"notid");var b=get_var_in_url(a.attr("href"),"type");e.preventDefault();a.removeAttr("href");a.html('<i class="icon-ok"></i>');$.post(ajaxurl,{action:"apoc_clear_notification",_wpnonce:d,notid:c},function(f){if(f){counter=$("li#notifications-"+b+" span.notifications-number");count=parseInt(counter.text());if(count>1){counter.text(count-1);a.parent().remove()}else{counter.remove();a.parent().text("Notifications cleared!")}title=$("title").text();count=title.split("]")[0].substr(1);if(1<count){title.replace(count,count-1)}else{title.replace(/\[.*\]/,"")}document.title=title}})});function get_var_in_url(b,a){var e=b.split("?");var d=e[1].split("&");for(var c=0;c<d.length;c++){var f=d[c].split("=");if(f[0]==a){return f[1]}}return""}function title_notification_count(){count=0;$.each(["activity","messages","groups","friends"],function(b,c){target=$("li#notifications-"+c+" span.notifications-number");if(target.is("*")){count=count+parseInt(target.text())}});if(count>0){var a=$("title").text().replace(/\[.*\]/,"");a="["+count+"]"+a;$("title").text(a)}}title_notification_count();

/*! AJAX Posts Loop */
;$("#content").on("click","nav.ajaxed a.page-numbers",function(c){var b=newPage=type=id=tooltip=dir="";var a=$(this);c.preventDefault();type=$("nav.pagination").data("type");id=$("nav.pagination").data("id");baseURL=window.location.href;b=parseInt($(".page-numbers.current").text());newPage=parseInt(a.text());if(a.hasClass("next")){newPage=b+1}else{if(a.hasClass("prev")){newPage=b-1}}dir=(newPage>b)?".next":".prev";tooltip=(newPage>b)?"Loading &raquo;":"&laquo; Loading";$("a.page-numbers"+dir).html(tooltip);$.post(ajaxurl,{action:"apoc_load_posts",type:type,id:id,paged:newPage,baseurl:baseURL},function(d){if(d!="0"){$(".post").fadeOut("slow").promise().done(function(){$(".post").remove();$("nav.pagination").remove();$("#content").append(d);$("html, body").animate({scrollTop:$("#content").offset().top-15},600);$(".post").hide().fadeIn("slow")})}})});

/*! Load Comments */
;$("#comments").on("click","nav.ajaxed a.page-numbers",function(c){var b=newPage=postid=tooltip=dir="";var a=$(this);c.preventDefault();postid=$("nav.pagination").data("postid");baseURL=window.location.href;b=parseInt($(".page-numbers.current").text());newPage=parseInt(a.text());if(a.hasClass("next")){newPage=b+1}else{if(a.hasClass("prev")){newPage=b-1}}dir=(newPage>b)?".next":".prev";tooltip=(newPage>b)?"Loading &raquo;":"&laquo; Loading";$("a.page-numbers"+dir).html(tooltip);$.post(ajaxurl,{action:"apoc_load_comments",postid:postid,paged:newPage,baseurl:baseURL},function(d){if(d!="0"){$(".reply").fadeOut("slow").promise().done(function(){$(".reply").remove();$("nav.pagination").remove();$("ol#comment-list").append(d);$("ol#comment-list").after($("nav.pagination"));$("html, body").animate({scrollTop:$("#comments").offset().top},600);$(".reply").hide().fadeIn("slow");$("#respond").show()})}})});

/*! Insert Comments */
;$("form#commentform").submit(function(f){var c="";var e=$(this);var a=e.attr("action");var d=$("#submit",e);var b=$("#comment",e);f.preventDefault();d.attr("disabled","disabled");d.parent().prepend('<div id="comment-notice"></div>');$("#comment-notice").hide();tinyMCE.triggerSave();if(""==b.val()){c="You didn't write anything!"}if(!c){d.attr("value","Submitting...");$.ajax({url:a,type:"post",data:e.serialize(),success:function(g){$("#respond").slideUp("slow",function(){$("ol#comment-list").append(g);$("#comments .discussion-header").removeClass("noreplies");$("ol#comment-list li.reply:last-child").hide().slideDown("slow");tinyMCE.activeEditor.setContent("");tinyMCE.triggerSave();d.removeAttr("disabled");d.attr("value","Post Comment")})},error:function(g,i,h){c="An error occurred during posting."}})}if(c){$("#comment-notice").addClass("error").text(c).fadeToggle("slow");d.removeAttr("disabled");d.removeAttr("disabled");d.attr("value","Post Comment")}});

/*! Delete Comments */
;$("ol#comment-list").on("click","a.delete-comment-link",function(a){a.preventDefault();confirmation=confirm("Permanently delete this comment?");if(confirmation){button=$(this);button.text("Deleting...");commentid=$(this).data("id");nonce=$(this).data("nonce");$.post(ajaxurl,{action:"apoc_delete_comment",_wpnonce:nonce,commentid:commentid},function(b){if(b){$("li#comment-"+commentid+" div.reply-body").slideUp("slow",function(){$("li#comment-"+commentid).remove()})}})}});

/*! End Document Ready */
;});



/* ______ REFACTORED / NEW BY ZAYDOK BELOW (TEMPORARY COMMENT) _____ */

// DOM ready
jQuery(function() {
	// Assign jQuery back to $ alias
	var $ = jQuery,
	// Define faux constants
			SITE_URL = document.URL,
			AJAX_URL 	= SITE_URL + 'wp-admin/admin-ajax.php',
	// Define elements
			advSearchForm = $( '#advanced-search' ),
			searchFor = advSearchForm.find( '#search-for' ),
			submitBtn = advSearchForm.find( 'input[type=submit]' )
	;

	// Display appropriate form fields based on "Search For" dropdown
	searchFor.bind( 'change', function() {
		var currentValue = searchFor.val(),
				currentFields = advSearchForm.find( '.dynamic-form-section:visible' ),
				inboundFields = $( '#if-' + currentValue )
		;
		// Animate out current fields
		currentFields
			.hide()
			.slideUp( 'slow' );
		// Animate in new fields based on newly selected "Search For" value
		inboundFields
			.show()
			.slideDown( 'slow' );
	});

	// Custom "Advanced Search" submit handling via AJAX
	submitBtn.bind( 'click', function() {
		// Temporarily do nothing until queries are worked out.
		return false;
	});
});