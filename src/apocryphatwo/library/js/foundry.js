/*! --------------------------------------- 
0.0 - DEFINE CONSTANTS
----------------------------------------- */
var	siteurl 	= ( window.location.host == 'localhost' ) ? 'http://localhost/tamrielfoundry/' : 'http://tamrielfoundry.com/';
var themeurl	= siteurl + 'wp-content/themes/apocrypha/';
var ajaxurl 	= siteurl + 'wp-admin/admin-ajax.php';
var $			= jQuery;

/*! jQuery Document Ready Functions */
;$(document).ready(function(){

/*! --------------------------------------- 
1.0 - ADMIN BAR
----------------------------------------- */

/*! Admin Bar Login Tooltips */
$('#top-login-form').submit( function(){
	
	// Fake AJAX functionality with a login tooltip
	$('#login-submit').attr('disabled', 'disabled');
	$('#login-submit').html('<i class="icon-spinner icon-spin"></i>Log In');
});
$('#top-login-logout').click( function(){
	$(this).html('<i class="icon-spinner icon-spin"></i>Logging Out');
});

/*! Buddypress Frontend Notifications */
;$("a.clear-notification").click(function(e){var a=$(this);var d=get_var_in_url(a.attr("href"),"_wpnonce");var c=get_var_in_url(a.attr("href"),"notid");var b=get_var_in_url(a.attr("href"),"type");e.preventDefault();a.removeAttr("href");a.html('<i class="icon-spinner icon-spin"></i>');$.post(ajaxurl,{action:"apoc_clear_notification",_wpnonce:d,notid:c},function(f){if(f){counter=$("li#notifications-"+b+" span.notifications-number");count=parseInt(counter.text());if(count>1){counter.text(count-1);a.parent().remove()}else{counter.remove();a.parent().text("Notifications cleared!")}title=$("title").text();count=title.split("]")[0].substr(1);if(1<count){title=title.replace(count,count-1)}else{title=title.replace(/\[.*\]/,"")}document.title=title}})});function get_var_in_url(b,a){var e=b.split("?");var d=e[1].split("&");for(var c=0;c<d.length;c++){var f=d[c].split("=");if(f[0]==a){return f[1]}}return""}function title_notification_count(){count=0;$.each(["activity","messages","groups","friends"],function(b,c){target=$("li#notifications-"+c+" span.notifications-number");if(target.is("*")){count=count+parseInt(target.text())}});if(count>0){var a=$("title").text().replace(/\[.*\]/,"");a="["+count+"]"+a;$("title").text(a)}}title_notification_count();

/*! Back To Top Link Scrolling */
$("a.backtotop").click(function(){$("html, body").animate({scrollTop:0},600);return false});

/*! Scroll To Bottom */
;$("a.downtobottom").click(function(){$("html, body").animate({scrollTop:$(document).height()},600);return false});

/*! --------------------------------------- 
2.0 - POSTS
----------------------------------------- */

/*! AJAX Posts Loop */
;$("#posts").on("click","nav.ajaxed a.page-numbers",function(c){var b=newPage=type=id=tooltip=dir="";var a=$(this);var d=a.parent().parent();c.preventDefault();d.css("pointer-events","none");type=$("nav.pagination").data("type");id=$("nav.pagination").data("id");baseURL=window.location.href.replace(window.location.hash,"");b=parseInt($(".page-numbers.current").text());newPage=parseInt(a.text());if(a.hasClass("next")){newPage=b+1}else{if(a.hasClass("prev")){newPage=b-1}}a.html('<i class="icon-spinner icon-spin"></i>');$.post(ajaxurl,{action:"apoc_load_posts",type:type,id:id,paged:newPage,baseurl:baseURL},function(e){if(e!="0"){$(".post").fadeOut("slow").promise().done(function(){d.remove();$("#posts").empty().append(e);$("html, body").animate({scrollTop:$("#content").offset().top},600);$("#posts").hide().fadeIn("slow")})}if(1==b){newURL=baseURL+"page/"+newPage+"/"}else{if(1==newPage){newURL=baseURL.replace("/page/"+b,"")}else{newURL=baseURL.replace("/page/"+b,"/page/"+newPage)}}window.history.replaceState({id:id,paged:b},document.title,newURL)})});

/*! Tabbed Containers */
;$("div.tab-content").not("div.active").hide();$("ul.tabs li a").click(function(){if(!$(this).parent().hasClass("current")&&!$(this).parent().hasClass("disabled")){var a=$(this).attr("href");$("ul.tabs li").removeClass("current");$(this).parent().addClass("current");$("div.tab-content").hide();$(a).fadeIn("slow")}return false});

/*! Collapsing FAQ Containers */
;$("div.faq-section header").append('<button class="faq-collapse"><i class="icon-expand"></i>Expand Section</button>');$("div.faq-section ul , div.faq-references ol").hide();$("button.faq-collapse").click(function(){var a=newtext="";$(this).parent().siblings("ul"|"ol").slideToggle(500,"swing");a=$(this).text();newtext=(a=="Expand Section")?'<i class="icon-collapse"></i>Collapse Section':'<i class="icon-expand"></i>Expand Section';$(this).html(newtext)});

/*! --------------------------------------- 
3.0 - COMMENTS
----------------------------------------- */

/*! Load Comments */
;$("#comments").on("click","nav.ajaxed a.page-numbers",function(c){var b=newPage=postid=tooltip=dir="";var a=$(this);var d=a.parent().parent();c.preventDefault();d.css("pointer-events","none");postid=$("nav.pagination").data("postid");baseURL=window.location.href.replace(window.location.hash,"");b=parseInt($(".page-numbers.current").text());newPage=parseInt(a.text());if(a.hasClass("next")){newPage=b+1}else{if(a.hasClass("prev")){newPage=b-1}}a.html('<i class="icon-spinner icon-spin"></i>');$.post(ajaxurl,{action:"apoc_load_comments",postid:postid,paged:newPage,baseurl:baseURL},function(e){if(e!="0"){$(".reply").fadeOut("slow").promise().done(function(){$("nav.pagination").remove();$("ol#comment-list").empty().append(e);$("ol#comment-list").after($("nav.pagination"));$("html, body").animate({scrollTop:$("#comments").offset().top},600);$("ol#comment-list").hide().fadeIn("slow");$("#respond").show()});if(1==b){newURL=baseURL+"comment-page-"+newPage}else{if(1==newPage){newURL=baseURL.replace("/comment-page-"+b,"")}else{newURL=baseURL.replace("/comment-page-"+b,"/comment-page-"+newPage)}}window.history.replaceState({type:"comments",id:postid,paged:b},document.title,newURL)}})});

/*! Insert Comments */
;$("form#commentform").submit(function(f){var c="";var e=$(this);var a=e.attr("action");var d=$("#submit",e);var b=$("#comment",e);f.preventDefault();d.attr("disabled","disabled");if($("#comment-notice").length==0){$(this).prepend('<div id="comment-notice"></div>');$("#comment-notice").hide()}tinyMCE.triggerSave();if(""==b.val()){c="You didn't write anything!"}if(!c){d.html('<i class="icon-spinner icon-spin"></i>Submitting...');$.ajax({url:a,type:"post",data:e.serialize(),success:function(g){$("#respond").slideUp("slow",function(){$("ol#comment-list").append(g);$("#comments .discussion-header").removeClass("noreplies");$("ol#comment-list li.reply:last-child").hide().slideDown("slow");tinyMCE.activeEditor.setContent("");tinyMCE.triggerSave();d.removeAttr("disabled");d.html('<i class="icon-pencil"></i>Post Comment')})},error:function(g,i,h){c="An error occurred during posting."}})}if(c){$("#comment-notice").addClass("error").text(c).fadeIn("slow");d.removeAttr("disabled");d.removeAttr("disabled");d.html('<i class="icon-pencil"></i>Post Comment')}});

/*! Delete Comments */
;$("ol#comment-list").on("click","a.delete-comment-link",function(a){a.preventDefault();confirmation=confirm("Permanently delete this comment?");if(confirmation){button=$(this);button.text("Deleting...");commentid=$(this).data("id");nonce=$(this).data("nonce");$.post(ajaxurl,{action:"apoc_delete_comment",_wpnonce:nonce,commentid:commentid},function(b){if(b){$("li#comment-"+commentid+" div.reply-body").slideUp("slow",function(){$("li#comment-"+commentid).remove()})}})}});

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

/*! Load bbPress Topics and Replies */
;$("#forums").on("click","nav.ajaxed a.page-numbers",function(c){var b=newPage=postid=tooltip=dir="";var a=$(this);var d=a.parent().parent();c.preventDefault();d.css("pointer-events","none");type=d.data("type");id=d.data("id");baseURL=window.location.href.replace(window.location.hash,"");b=parseInt($(".page-numbers.current").text());newPage=parseInt(a.text());if(a.hasClass("next")){newPage=b+1}else{if(a.hasClass("prev")){newPage=b-1}}a.html('<i class="icon-spinner icon-spin"></i>');if("replies"==type){$.post(ajaxurl,{action:"apoc_load_replies",type:type,id:id,paged:newPage,baseurl:baseURL},function(e){if(e!="0"){$("ol#topic-"+id).fadeOut("slow").promise().done(function(){d.remove();$("ol#topic-"+id).empty().append(e);$("ol#topic-"+id).after($("nav.forum-pagination"));$("html, body").animate({scrollTop:$("#forums").offset().top},600);$("ol#topic-"+id).hide().fadeIn("slow");$("#respond").show()});if(1==b){newURL=baseURL+"page/"+newPage+"/"}else{if(1==newPage){newURL=baseURL.replace("/page/"+b,"")}else{newURL=baseURL.replace("page/"+b,"page/"+newPage)}}window.history.replaceState({id:id,paged:b},document.title,newURL)}})}else{if("topics"==type){$.post(ajaxurl,{action:"apoc_load_topics",type:type,id:id,paged:newPage,baseurl:baseURL},function(e){if(e!="0"){$("ol#forum-"+id).fadeOut("slow").promise().done(function(){d.remove();$("ol#forum-"+id).empty().append(e);$("ol#forum-"+id).after($("nav.forum-pagination"));$("html, body").animate({scrollTop:$("#forums").offset().top},600);$("ol#forum-"+id).hide().fadeIn("slow");$("#respond").show()});if(1==b){newURL=baseURL+"page/"+newPage+"/"}else{if(1==newPage){newURL=baseURL.replace("/page/"+b,"")}else{newURL=baseURL.replace("page/"+b,"page/"+newPage)}}window.history.replaceState({id:id,paged:b},document.title,newURL)}})}}});

/*! Submit New bbPress Topic */
;$(".forum form#new-post").submit(function(e){var b="";var d=$(this);var c=$("#bbp_topic_submit",d);var a=$("#bbp_topic_content",d);var f=$("#bbp_topic_title",d);c.attr("disabled","disabled");if($("#topic-notice").length==0){d.prepend('<div id="topic-notice"></div>');$("#topic-notice").hide()}c.html('<i class="icon-spinner icon-spin"></i>Submitting ...');tinyMCE.triggerSave();if(""==f.val()){b="Your topic must have a title!"}else{if(""==a.val()){b="You didn't write anything!"}}if(b){e.preventDefault();$("#topic-notice").addClass("error").text(b).fadeIn("slow");c.removeAttr("disabled");c.removeAttr("disabled");c.html('<i class="icon-pencil"></i>Post New Topic')}});

/*! Submit New bbPress Reply */
;$(".topic form#new-post").submit(function(f){var c=data="";var e=$(this);var d=$("#bbp_reply_submit",e);var a=$("#bbp_reply_content",e);var b=$("#bbp_topic_id",e).val();f.preventDefault();d.attr("disabled","disabled");if($("#topic-notice").length==0){e.prepend('<div id="topic-notice"></div>');$("#topic-notice").hide()}tinyMCE.triggerSave();if(""==a.val()){c="You didn't write anything!"}if(!c){$("input#bbp_post_action").attr("value","apoc_bbp_reply");data=e.serialize();d.html('<i class="icon-spinner icon-spin"></i>Submitting ...');$.ajax({url:ajaxurl,type:"post",data:e.serialize(),success:function(g){$("#respond").slideUp("slow",function(){$("ol#topic-"+b).append(g);$("ol#topic-"+b+" li.reply:last-child").hide().slideDown("slow");tinyMCE.activeEditor.setContent("");tinyMCE.triggerSave();d.removeAttr("disabled");d.html('<i class="icon-pencil"></i>Post Reply')})},error:function(g,i,h){c="An error occurred during posting."}})}if(c){$("#topic-notice").addClass("error").text(c).fadeIn("slow");d.removeAttr("disabled");d.removeAttr("disabled");d.html('<i class="icon-pencil"></i>Post Reply')}});

/*! Tab Into TinyMCE From Topic Title */
;$("#bbp_topic_title").bind("keydown.editor-focus",function(b){if(b.which!==9){return}if(!b.ctrlKey&&!b.altKey&&!b.shiftKey){if(typeof(tinymce)!=="undefined"){if(!tinymce.activeEditor.isHidden()){var a=tinymce.activeEditor.editorContainer;$("#"+a+" td.mceToolbar > a").focus()}else{$("textarea.bbp-the-content").focus()}}else{$("textarea.bbp-the-content").focus()}b.preventDefault()}});

/*! bbPress Favorites / Subs */
;function bbp_ajax_call(d,e,c,a){var b={action:d,id:e,nonce:c};$.post(bbpTopicJS.bbp_ajaxurl,b,function(f){if(f.success){$(a).html(f.content)}else{if(!f.content){f.content=bbpTopicJS.generic_ajax_error}alert(f.content)}})}$("#subscription-controls").on("click","a.favorite-toggle",function(a){a.preventDefault();bbp_ajax_call("favorite",$(this).attr("data-topic"),bbpTopicJS.fav_nonce,"#subscription-controls #favorite-toggle")});$("#subscription-controls").on("click","a.subscription-toggle",function(a){a.preventDefault();bbp_ajax_call("subscription",$(this).attr("data-topic"),bbpTopicJS.subs_nonce,"#subscription-controls #subscription-toggle")});

/*! Post Reporting */
;$("#comments,#forums,#private-messages").on("click","a.report-post",function(b){confirmation=confirm("Report this post? Please make sure this is a valid report.");if(confirmation){var a=postid=postnum=author=reason="";reason=prompt("Reason For Report","Why you are reporting this post...");if("Why you are reporting this post..."==reason){reason="No reason given by reporter."}a=$(this).data("type");postid=$(this).data("id");postnum=$(this).data("number");user=$(this).data("user");$(this).remove();$.post(ajaxurl,{action:"apoc_report_post",type:a,id:postid,num:postnum,user:user,reason:reason},function(c){if(c==1){alert("Report sent successfully, thank you.")}});return false}});

/*! Trash Replies */
;$("#forums").on("click","a.bbp-reply-trash-link",function(a){a.preventDefault();confirmation=confirm("Permanently delete this post?");if(confirmation){button=$(this);button.html('<i class="icon-spinner icon-spin"></i>Deleting');reply_id=get_url_var(button.attr("href"),"reply_id");context=get_url_var(button.attr("href"),"action");nonce=get_url_var(button.attr("href"),"_wpnonce");$.post(ajaxurl,{action:"apoc_delete_reply",context:context,reply_id:reply_id,_wpnonce:nonce},function(b){if(b=="1"){thereply=button.parents("li.reply");replybody=thereply.children("div.reply-body");replybody.slideUp("slow",function(){thereply.remove()})}})}});


/*! --------------------------------------- 
5.0 - USERS
----------------------------------------- */

/*! Clear Infraction */
;$("a.clear-infraction").click(function(){var a=$(this);var b=get_var_in_url(a.attr("href"),"_wpnonce");var c=get_var_in_url(a.attr("href"),"id");a.html('<i class="icon-spinner icon-spin"></i>Deleting').attr("disabled","disabled");$.post(ajaxurl,{action:"apoc_clear_infraction",_wpnonce:b,id:c},function(d){if(d==1){$("li#infraction-"+c).slideUp()}});return false});

/*! Clear Mod Notes */
;$("a.clear-mod-note").click(function(){var a=$(this);var b=get_var_in_url(a.attr("href"),"_wpnonce");var c=get_var_in_url(a.attr("href"),"id");a.html('<i class="icon-spinner icon-spin"></i>Deleting').attr("disabled","disabled");$.post(ajaxurl,{action:"apoc_clear_mod_note",_wpnonce:b,id:c},function(d){if(d==1){$("li#modnote-"+c).slideUp()}});return false});

/*! Advanced Search Fields */
;$("ol.adv-search-fields").not("ol.active").hide();$("select#search-for").bind("change",function(){$("#search-results").slideUp();var a=$("select#search-for").val();var b=$("ol#adv-search-"+a);$("ol.adv-search-fields").removeClass("active").hide();b.addClass("active").fadeIn()});

/*! End Document Ready */
;});

/*! --------------------------------------- 
X.X - PROCEDURAL FUNCTIONS
----------------------------------------- */

/*! Parse URL Variables */
function get_url_var(b,a){var e=b.split("?");var d=e[1].split("&");for(var c=0;c<d.length;c++){var f=d[c].split("=");if(f[0]==a){return f[1]}}return""};


function apoc_ajax_debug() {

	jQuery.post( ajaxurl , {
		'action'	: 'apoc_debug',
	},
	function( response ) {
		alert( response);
	} );
}



