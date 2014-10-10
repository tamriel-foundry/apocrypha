// AJAX Functions
var	siteurl = ( window.location.host == 'localhost' ) ? 'http://localhost/tamrielfoundry/' : 'http://localhost/tamrielfoundry/';
var ajaxurl 	= siteurl + 'wp-admin/admin-ajax.php';
var jq 			= jQuery;

// Global variable to prevent multiple AJAX requests
var bp_ajax_request = null;

jq(document).ready( function() {
	/**** Page Load Actions *******************************************************/

	/* Activity filter and scope set */
	bp_init_activity();

	/* Object filter and scope set. */
	var objects = [ 'members', 'groups', 'blogs', 'forums' ];
	bp_init_objects( objects );

	/* @mention Compose Scrolling, but only on "Public Messages */
	if ( jq.query.get('r') && jq('body.activity textarea#whats-new').length ) {
		jq('#whats-new-options').animate({
			height:'40px'
		});
		jq("form#whats-new-form textarea").animate({
			height:'50px'
		});
		jq.scrollTo( jq('textarea#whats-new'), 500, {
			offset:-125,
			easing:'easeOutQuad'
		} );
		jq('textarea#whats-new').focus();
	}
	/* private messaging scroll to form */
	else if ( jq.query.get('r') && jq('form#send_message_form').length ) {
		jq.scrollTo( jq('form#send_message_form'), 500 );	
	}

	/**** Activity Posting ********************************************************/
	
	/* Hide the form until the "Whats New" button is clicked" */
	jq( 'form#whats-new-form' ).hide();
	jq( 'a.update-status-button' ).click( function() {
		jq( 'form#whats-new-form' ).slideToggle( 'fast' , function() {
			jq('textarea#whats-new').focus().animate({ height:'50px' });
		});
	});

	/* Textarea focus */
	jq('#whats-new').focus( function(){
		jq("#whats-new-options").animate({
			height:'40px'
		});
		jq("form#whats-new-form textarea").animate({
			height:'50px'
		});
		jq("#aw-whats-new-submit").prop("disabled", false);

		var $whats_new_form = jq("form#whats-new-form");
		if ( $whats_new_form.hasClass("submitted") ) {
			$whats_new_form.removeClass("submitted");	
		}
	});

	/* On blur, shrink if it's empty */
	jq('#whats-new').blur( function(){
		if (!this.value.match(/\S+/)) {
			this.value = "";
			jq("#whats-new-options").animate({
				height:'40px'
			});
			jq("form#whats-new-form textarea").animate({
				height:'20px'
			});
			jq("#aw-whats-new-submit").prop("disabled", true);
		}
	});

	/* New posts */
	jq("button#aw-whats-new-submit").click( function() {
		var button = jq(this);
		var form = button.closest("form#whats-new-form");

		form.children().each( function() {
			if ( jq.nodeName(this, "textarea") || jq.nodeName(this, "input") )
				jq(this).prop( 'disabled', true );
		});

		// Remove any errors
		jq('div.error').remove();
		button.html( '<i class="icon-spinner icon-spin"></i>Submitting' );
		button.prop('disabled', true);
		form.addClass("submitted");

		// Default POST values
		var object = '';
		var item_id = jq("#whats-new-post-in").val();
		var content = jq("textarea#whats-new").val();

		// Set object for non-profile posts
		if ( item_id > 0 ) {
			object = jq("#whats-new-post-object").val();
		}

		jq.post( ajaxurl, {
			action: 'post_update',
			'cookie': bp_get_cookies(),
			'_wpnonce_post_update': jq("input#_wpnonce_post_update").val(),
			'content': content,
			'object': object,
			'item_id': item_id,
			'_bp_as_nonce': jq('#_bp_as_nonce').val() || ''
		},
		function(response) {
		
			form.children().each( function() {
				if ( jq.nodeName(this, "textarea") || jq.nodeName(this, "input") ) {
					jq(this).prop( 'disabled', false );
				}
			});

			// Check for errors and append if found
			if ( response[0] + response[1] == '-1' ) {
				form.prepend( response.substr( 2, response.length ) );
				jq( 'form#' + form.attr('id') + ' div.error').hide().fadeIn( 200 );
			
			// No error
			} else {
			
				// If the activity stream was previously empty, create a container
				if ( 0 == jq("ul#activity-stream").length ) {
					jq("div.error").slideUp(100).remove();
					jq("div#message").slideUp(100).remove();
					jq("div#activity-directory").append( '<ul id="activity-stream" class="activity-list item-list">' );
				}

				jq("ul#activity-stream").prepend(response);
				jq("ul#activity-stream li:first").addClass('new-update just-posted');

				// If we are on an activity-stream page, add the new update to the stream
				if ( 0 != jq("ul#activity-stream").length ) {
					var l = jq("ul#activity-stream li.new-update .activity-content .activity-inner p").html();
					var v = jq("ul#activity-stream li.new-update .activity-content .activity-header p a.view").attr('href');

					var ltext = jq("ul#activity-stream li.new-update .activity-content .activity-inner p").text();

					var u = '';
					if ( ltext != '' )
						u = l + ' ';

					jq("#latest-update").slideUp(300,function(){
						jq("#latest-update").html( u );
						jq("#latest-update").slideDown(300);
					});
				}
				
				// If we are on a user profile page, add the new update to their recent status
				if ( 0 != jq("#profile-status").length ) {
						jq("#profile-status").slideUp(300,function(){				
						jq("#profile-status span#latest-status").html( content );
						jq("#profile-status").slideDown(300);
					});
				}
				
				// Reveal the new update, and clear the form
				jq("li.new-update").hide().slideDown( 300 );
				jq("li.new-update").removeClass( 'new-update' );
				jq("textarea#whats-new").val('');
			}

			// Reset the post form
			jq("#whats-new-options").animate({
				height:'0px'
			});
			jq("form#whats-new-form textarea").animate({
				height:'20px'
			});
			jq("#aw-whats-new-submit").prop("disabled", true).html( '<i class="icon-pencil"></i>Post Update' );
		});

		return false;
	});

	/* Activity Type Tab Switching */
	jq('nav.activity-type-tabs').click( function(event) {
	
		var target = jq(event.target).parent();
		if ( event.target.nodeName == 'STRONG' || event.target.nodeName == 'SPAN' )
			target = target.parent();
		else if ( event.target.nodeName != 'A' )
			return false;

		// Reset the page
		jq.cookie( 'bp-activity-oldestpage', 1, {
			path: '/'
		} );

		// Get data from the tabs
		var scope = target.attr('id').substr( 9, target.attr('id').length );
		var filter = jq("#activity-filter-select select").val();

		if ( scope == 'mentions' )
			jq( 'li#' + target.attr('id') + ' a strong' ).remove();

		// Update the CSS
		jq( 'ul#directory-actions li' ).removeClass( 'selected' );
		target.addClass( 'selected' );
		jq( 'li.selected a' ).prepend( '<i class="icon-spinner icon-spin"></i>' );
			
		// Get new activity from AJAX
		bp_activity_request(scope, filter);
		
		// Remove the tooltip
		jq( 'li.selected a i' ).delay( 1000 ).fadeOut( 400 , function() {
			jq( 'li.selected a i' ).remove();
		});
		
		// Prevent default
		return false;
	});

	/* Activity filter select */
	jq('#activity-filter-select select').change( function() {
		
		// Get the current activity tab
		var selected_tab = jq( 'nav.activity-type-tabs li.selected' );
		if ( !selected_tab.length )
			var scope = null;
		else
			var scope = selected_tab.attr('id').substr( 9, selected_tab.attr('id').length );
			
		// Get the dropdown filter
		var filter = jq(this).val();

		// Get new activity from AJAX
		bp_activity_request(scope, filter);
		
		// Remove the tooltip
		jq( 'li.selected a i' ).delay( 1000 ).fadeOut( 400 , function() {
			jq( 'li.selected a i' ).remove();
		});

		// Prevent default
		return false;
	});

	/**** Activity Stream *******************************************************/
	
	jq('div.activity').click( function(event) {
		var target = jq(event.target);
		
		// Activity Comment Button
		if ( target.hasClass('acomment-reply') ) {
		
			// Get data about the activity entry
			var id = target.attr('id');
			ids = id.split('-');
			var a_id = ids[2]
			var c_id = target.attr('href').substr( 10, target.attr('href').length );
			
			// Make sure the form is hidden
			var form = jq( '#ac-form-' + a_id );
			form.css( 'display', 'none' );
			form.removeClass('root');
			jq('form.ac-form').hide();

			// Hide any error messages
			form.children('div').each( function() {
				if ( jq(this).hasClass( 'error' ) )
					jq(this).hide();
			});

			// Put the activity reply form in the right place
			if ( ids[1] != 'comment' ) {
				jq('.activity-comments li#acomment-' + c_id).append( form );
			} else {
				jq('li#activity-' + a_id + ' .activity-comments').append( form );
			}
			if ( form.parent().hasClass( 'activity-comments' ) )
				form.addClass('root');

			// Scroll to the form, display it, and focus it
			form.slideDown( 200 );
			jq.scrollTo( form, 500, {
				offset:-100,
				easing:'easeOutQuad'
			} );
			jq('#ac-form-' + ids[2] + ' textarea').focus();
			
			// Prevent default
			return false;
		}

		// Delete activity stream items
		if ( target.hasClass('delete-activity') ) {
		
			// Get the data
			var li        = target.parents('div.activity ul li');
			var id        = li.attr('id').substr( 9, li.attr('id').length );
			var link_href = target.attr('href');
			var nonce     = link_href.split('_wpnonce=');
			nonce = nonce[1];

			// Tooltip
			target.html( '<i class="icon-spinner icon-spin"></i>Deleting' );

			// AJAX
			jq.post( ajaxurl, {
				action: 'delete_activity',
				'cookie': bp_get_cookies(),
				'id': id,
				'_wpnonce': nonce
			},
			function(response) {

				if ( response[0] + response[1] == '-1' ) {
					li.prepend( response.substr( 2, response.length ) );
					li.children('div#message').hide().fadeIn(300);
				} else {
					li.slideUp(300);
				}
			});

			return false;
		}

		// Load more updates at the end of the page
		if ( target.parent().hasClass('load-more') ) {
		
			// Tooltip
			jq("#activity-stream li.load-more a").html( '<i class="icon-spinner icon-spin"></i>Loading' );

			// Determine the page
			if ( null == jq.cookie('bp-activity-oldestpage') )
				jq.cookie('bp-activity-oldestpage', 1, {
					path: '/'
				} );
			var oldest_page = ( jq.cookie('bp-activity-oldestpage') * 1 ) + 1;
			var just_posted = [];
			
			// Update the list
			jq('.activity-list li.just-posted').each( function(){
				just_posted.push( jq(this).attr('id').replace( 'activity-','' ) );
			});

			// Retrieve activity
			jq.post( ajaxurl, {
				action: 'activity_get_older_updates',
				'cookie': bp_get_cookies(),
				'page': oldest_page,
				'exclude_just_posted': just_posted.join(',')
			},
			function(response)
			{
				jq("#activity-stream li.load-more a").html( '<i class="icon-expand-alt"></i>Load More' );
				jq.cookie( 'bp-activity-oldestpage', oldest_page, {
					path: '/'
				} );
				jq("#content ul.activity-list").append(response.contents);

				target.parent().hide();
			}, 'json' );

			// Prevent Default
			return false;
		}
	});

	// Activity "Read More" links
	jq('.activity-read-more a').on('click', function(event) {
	
		// Get data
		var target = jq(event.target);
		var link_id = target.parent().attr('id').split('-');
		var a_id = link_id[3];
		var type = link_id[0];
		var inner_class = type == 'acomment' ? 'acomment-content' : 'activity-content';
		var a_inner = jq('li#' + type + '-' + a_id + ' .' + inner_class + ':first' );
		
		// Provide a tooltip
		jq(target).text('[Loading...]');

		// Retrieve the full content
		jq.post( ajaxurl, {
			action: 'get_single_activity_content',
			'activity_id': a_id
		},
		function(response) {
			jq(a_inner).slideUp(300).html(response).slideDown(300);
		});

		// Prevent default action
		return false;
	});
	
	/**** Activity Comments *******************************************************/

	/* Hide all activity comment forms */
	jq('form.ac-form').hide();

	/* Hide excess comments */
	if ( jq('.activity-comments').length )
		bp_dtheme_hide_comments();

	/* Activity list event delegation */
	jq('div.activity-comments').click( function(event) {
		var target = jq(event.target);

		// Post new activity comment
		if ( target.attr('name') == 'ac_form_submit' ) {
		
			// Get the data
			var form        = target.parents( 'form' );
			var form_parent = form.parent();
			var form_id     = form.attr('id').split('-');
			if ( !form_parent.hasClass('activity-comments') ) {
				var tmp_id = form_parent.attr('id').split('-');
				var comment_id = tmp_id[1];
			} else {
				var comment_id = form_id[2];
			}

			// Hide any error messages
			jq( 'form#' + form.attr('id') + ' div.error').hide();
			
			// Disable the button + tooltip
			target.prop('disabled', true).html( '<i class="icon-spinner icon-spin"></i>Submitting' );

			// Submit the AJAX
			var ajaxdata = {
				action: 'new_activity_comment',
				'cookie': bp_get_cookies(),
				'_wpnonce_new_activity_comment': jq("input#_wpnonce_new_activity_comment").val(),
				'comment_id': comment_id,
				'form_id': form_id[2],
				'content': jq('form#' + form.attr('id') + ' textarea').val()
			};

			// Akismet
			var ak_nonce = jq('#_bp_as_nonce_' + comment_id).val();
			if ( ak_nonce ) {
				ajaxdata['_bp_as_nonce_' + comment_id] = ak_nonce;
			}
			
			jq.post( ajaxurl, ajaxdata, function(response) {
				target.removeClass('loading');

				// Check for errors and append if found
				if ( response[0] + response[1] == '-1' ) {
					form.append( jq( response.substr( 2, response.length ) ).hide().fadeIn( 200 ) );
				} else {
					var activity_comments = form.parent();
					form.fadeOut( 200, function() {
						if ( 0 == activity_comments.children('ul').length ) {
							if ( activity_comments.hasClass('activity-comments') ) {
								activity_comments.prepend('<ul></ul>');
							} else {
								activity_comments.append('<ul></ul>');
							}
						}

						// Preceeding whitespace breaks output with jQuery 1.9.0
						var the_comment = jq.trim( response );

						// Append the new comment to the form
						activity_comments.children('ul').append( jq( the_comment ).hide().fadeIn( 200 ) );
						form.children('textarea').val('');
						activity_comments.parent().addClass('has-comments');
						jq('form.ac-form').hide();
					} );

					// Empty the textarea
					jq( 'form#' + form.attr('id') + ' textarea').val('');

					/* Increase the "Reply (X)" button count */
					jq('li#activity-' + form_id[2] + ' a.acomment-reply span').html( Number( jq('li#activity-' + form_id[2] + ' a.acomment-reply span').html() ) + 1 );

					// Increment the 'Show all x comments' string, if present
					var show_all_a = activity_comments.find('.show-all').find('a');
					if ( show_all_a ) {
						var new_count = jq('li#activity-' + form_id[2] + ' a.acomment-reply span').html( new_count);
						//show_all_a.html( BP_DTheme.show_x_comments.replace( '%d', new_count ) );
					}
				}

				// Restore the button
				jq(target).prop("disabled", false).html( '<i class="icon-pencil"></i>Post Comment' );
			});

			// Prevent default
			return false;
		}
		
		/* Deleting an activity comment */
		if ( target.hasClass('acomment-delete') ) {
			
			// Get some data
			var link_href = target.attr('href');
			var comment_li = target.parent().parent().parent();
			var form = comment_li.parents('div.activity-comments').children('form');
			var nonce = link_href.split('_wpnonce=');
			nonce = nonce[1];
			var comment_id = link_href.split('cid=');
			comment_id = comment_id[1].split('&');
			comment_id = comment_id[0];

			// Give a tooltip
			target.html( '<i class="icon-spinner icon-spin"></i>Deleting' );

			// Remove any error messages
			jq('.activity-comments ul .error').remove();

			// Reset the form position
			comment_li.parents('.activity-comments').append(form);

			// Do the AJAX
			jq.post( ajaxurl, {
				action: 'delete_activity_comment',
				'cookie': bp_get_cookies(),
				'_wpnonce': nonce,
				'id': comment_id
			},
			function(response)
			{
				// Check for errors and append if found
				if ( response[0] + response[1] == '-1' ) {
					comment_li.prepend( jq( response.substr( 2, response.length ) ).hide().fadeIn( 200 ) );
				} else {
					var children = jq( 'li#' + comment_li.attr('id') + ' ul' ).children('li');
					var child_count = 0;
					jq(children).each( function() {
						if ( !jq(this).is(':hidden') )
							child_count++;
					});
					comment_li.fadeOut(200, function() {
						comment_li.remove();
					});

					// Decrease the "Reply (X)" button count
					var count_span = jq('li#' + comment_li.parents('ul#activity-stream > li').attr('id') + ' a.acomment-reply span');
					var new_count = count_span.html() - ( 1 + child_count );
					count_span.html(new_count);
	
					// Change the 'Show all x comments' text
					var show_all_a = comment_li.siblings('.show-all').find('a');
					if ( show_all_a ) {
						//show_all_a.html( BP_DTheme.show_x_comments.replace( '%d', new_count ) );
					}

					// If that was the last comment for the item, remove the has-comments class to clean up the styling
					if ( 0 == new_count ) {
						jq(comment_li.parents('ul#activity-stream > li')).removeClass('has-comments');
					}
				}
			});

			// Prevent default
			return false;
		}

		/* Showing hidden comments - pause for half a second */
		if ( target.parent().hasClass('show-all') ) {
			target.parent().addClass('loading');

			setTimeout( function() {
				target.parent().parent().children('li').fadeIn(200, function() {
					target.parent().remove();
				});
			}, 600 );

			return false;
		}
	});

	/* Escape Key Press for cancelling comment forms */
	jq(document).keydown( function(e) {
		e = e || window.event;
		if (e.target)
			element = e.target;
		else if (e.srcElement)
			element = e.srcElement;

		if( element.nodeType == 3)
			element = element.parentNode;

		if( e.ctrlKey == true || e.altKey == true || e.metaKey == true )
			return;

		var keyCode = (e.keyCode) ? e.keyCode : e.which;

		if ( keyCode == 27 ) {
			if (element.tagName == 'TEXTAREA') {
				if ( jq(element).hasClass('ac-input') )
					jq(element).parent().parent().parent().slideUp( 200 );
			}
		}
	});

	/**** Directory Search ****************************************************/

	/* The search form on all directory pages
	jq('.dir-search').click( function(event) {
		if ( jq(this).hasClass('no-ajax') )
			return;

		var target = jq(event.target);

		if ( target.attr('type') == 'submit' ) {
			var css_id = jq('.item-list-tabs li.selected').attr('id').split( '-' );
			var object = css_id[0];

			bp_filter_request( object, jq.cookie('bp-' + object + '-filter'), jq.cookie('bp-' + object + '-scope') , 'div.' + object, target.parent().children('label').children('input').val(), 1, jq.cookie('bp-' + object + '-extras') );

			return false;
		}
	}); */

	/**** Tabs and Filters ****************************************************/

	/* When a navigation tab is clicked - e.g. | All Groups | My Groups | */
	jq('nav.dir-list-tabs').click( function(event) {
		if ( jq(this).hasClass('no-ajax') )
			return;

		// Get the target
		var targetElem = ( event.target.nodeName == 'SPAN' ) ? event.target.parentNode : event.target;
		var target     = jq( targetElem ).parent();
		if ( 'LI' == target[0].nodeName && !target.hasClass('last') ) {
			var css_id = target.attr('id').split( '-' );
			var object = css_id[0];

			if ( 'activity' == object )
				return false;

			// Give a tooltip
			jq( 'nav.dir-list-tabs li' ).removeClass( 'selected' );
			target.addClass( 'selected' );
			jq( 'li.selected a' ).prepend( '<i class="icon-spinner icon-spin"></i>' );
			
			// Filter the directory
			var scope = css_id[1];
			var filter = jq("#" + object + "-order-select select").val();
			var search_terms = jq("#" + object + "_search").val();
			bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, jq.cookie('bp-' + object + '-extras') );
			
			// Remove the tooltip
			jq( 'li.selected a i' ).delay( 1000 ).fadeOut( 400 , function() {
				jq( 'li.selected a i' ).remove();
			});
			
			// Prevent Default
			return false;
		}
	});

	/* When the filter select box is changed re-query */
	jq('div.filter select').change( function() {
		if ( jq('.dir-list-tabs li.selected').length )
			var el = jq('.dir-list-tabs li.selected');
		else
			var el = jq(this);

		// Get the data
		var css_id = el.attr('id').split('-');
		var object = css_id[0];
		var scope = css_id[1];
		var filter = jq(this).val();
		var search_terms = false;

		// Check if there is a current search
		if ( jq('.dir-search input').length )
			search_terms = jq('.directory-search input').val();
		if ( 'friends' == object )
			object = 'members';
			
		// Give a tooltip
		jq( 'li.selected a' ).prepend( '<i class="icon-spinner icon-spin"></i>' );

		// Get the new data
		bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, jq.cookie('bp-' + object + '-extras') );
		
		// Remove the tooltip
		jq( 'li.selected a i' ).delay( 1000 ).fadeOut( 400 , function() {
			jq( 'li.selected a i' ).remove();
		});

		// Prevent default
		return false;
	});
	
	/* All pagination links run through this function */
	jq('div#content').click( function(event) {
		var target = jq(event.target);

		if ( target.hasClass('button') )
			return true;

		if ( target.parents( 'nav.pagination' ).length != 0 && !target.parents( 'nav.pagination' ).hasClass('no-ajax') ) {
						
			if ( target.hasClass('dots') || target.hasClass('current') )
				return false;

			// See if the directory is currently filtered or scoped
			if ( jq('.dir-list-tabs li.selected').length )
				var el = jq('.dir-list-tabs li.selected');
			else
				var el = jq('div.filter select');

			// Figure out the page number
			var page_number = 1;
			var css_id = el.attr('id').split( '-' );
			var object = css_id[0];
			var search_terms = false;

			// Check for search terms
			if ( jq('div.directory-search input').length )
				search_terms = jq('.directory-search input').val();

			// Change the page numbers
			if ( jq(target).hasClass('next') )
				var page_number = Number( jq('.pagination span.current').html() ) + 1;
			else if ( jq(target).hasClass('prev') )
				var page_number = Number( jq('.pagination span.current').html() ) - 1;
			else
				var page_number = Number( jq(target).html() );

			// Get the new content
			bp_filter_request( object, jq.cookie('bp-' + object + '-filter'), jq.cookie('bp-' + object + '-scope'), 'div.' + object, search_terms, page_number, jq.cookie('bp-' + object + '-extras') );

			// Prevent default
			return false;
		}

	});

	/** Invite Friends Interface ****************************************/

	/* Select a user from the list of friends and add them to the invite list */
	jq("div#invite-list input").click( function() {
		jq('.ajax-loader').toggle();
		
		// Get the data
		var friend_id = jq(this).val();

		// Check to invite, unckeck to uninvite
		if ( jq(this).prop('checked') == true )
			var friend_action = 'invite';
		else
			var friend_action = 'uninvite';

		// Give a loading tooltip
		jq('#invited-list h3').prepend( '<i class="icon-spinner icon-spin"></i>' );

		// AJAX the invitation
		jq.post( ajaxurl, {
			action: 'groups_invite_user',
			'friend_action': friend_action,
			'cookie': bp_get_cookies(),
			'_wpnonce': jq("input#_wpnonce_invite_uninvite_user").val(),
			'friend_id': friend_id,
			'group_id': jq("input#group_id").val()
		},
		function(response)
		{
			if ( jq("#message") )
				jq("#message").hide();

			jq('.ajax-loader').toggle();

			if ( friend_action == 'invite' ) {
				jq('#friend-list').append(response);
			} else if ( friend_action == 'uninvite' ) {
				jq('#friend-list li#uid-' + friend_id).remove();
			}

			// Remove the loading tooltip
			jq('#invited-list h3').text( 'Selected Friends' );
		});
	});

	/* Remove a user from the list of users to invite to a group */
	jq("#friend-list").on('click', 'li a.remove', function() {
		jq('.ajax-loader').toggle();

		// Get the data
		var friend_id = jq(this).attr('id');
		friend_id = friend_id.split('-');
		friend_id = friend_id[1];
		
		// Give a loading tooltip
		jq('#invited-list h3').prepend( '<i class="icon-spinner icon-spin"></i>' );

		// Send the AJAX
		jq.post( ajaxurl, {
			action: 'groups_invite_user',
			'friend_action': 'uninvite',
			'cookie': bp_get_cookies(),
			'_wpnonce': jq("input#_wpnonce_invite_uninvite_user").val(),
			'friend_id': friend_id,
			'group_id': jq("input#group_id").val()
		},
		function(response)
		{
			jq('.ajax-loader').toggle();
			jq('#friend-list li#uid-' + friend_id).remove();
			jq('#invite-list input#f-' + friend_id).prop('checked', false);
		});
		
		// Remove the loading tooltip
		jq('#invited-list h3').text( 'Selected Friends' );

		// Prevent default
		return false;
	});

	/** Friendship Requests **************************************/

	/* Accept and Reject friendship request buttons */
	jq("ul#friend-request-list a.accept, ul#friend-request-list a.reject").click( function() {
			
		// Get the data
		var button = jq(this);
		var li = jq(this).parents('ul#friend-request-list li');
		var action_div = jq(this).parents('li div.actions');
		var id = li.attr('id').substr( 11, li.attr('id').length );
		var link_href = button.attr('href');
		var nonce = link_href.split('_wpnonce=');
		nonce = nonce[1];		

		// Prevent double clicks
		if ( jq(this).hasClass('accepted') || jq(this).hasClass('rejected') )
			return false;
			
		// Determine the appropriate action	and remove the alternative
		if ( jq(this).hasClass('accept') ) {
			var action = 'accept_friendship';
			action_div.children('a.reject').fadeOut();
		} else {
			var action = 'reject_friendship';
			action_div.children('a.accept').fadeOut();
		}

		// Get the current icon
		var icon = button.children('i').attr( 'class' );
		button.children('i').attr( "class", "icon-spinner icon-spin" );
		
		// Submit the AJAX
		jq.post( ajaxurl, {
			action: action,
			'cookie': bp_get_cookies(),
			'id': id,
			'_wpnonce': nonce
		},
		function(response) {
			if ( response[0] + response[1] == '-1' ) {
				li.prepend( response.substr( 2, response.length ) );
				li.children('div#message').hide().fadeIn(200);
			} else {
					
				// Successfully accepted
				if ( jq(this).hasClass('accept') ) {
					action_div.children('a.reject').hide();
					button.html('<i class="icon-ok"></i>Accepted');
				
				// Successfully rejected
				} else {
					action_div.children('a.accept').hide();
					button.html('<i class="icon-remove"></i>Rejected');
				}
				
				// Decrement the counter
				var count = jq('#requests-personal-li span.activity-count');
				var countn = count.text().split('+');
				countn = countn[1] - 1;
				if ( countn > 0 ) {
					count.text('+' + countn );			
				} else {
					count.fadeOut();
				}
			}
		});

		// Prevent default
		return false;
	});

	/* Add / Remove friendship buttons */
	jq('#members-dir-list').on('click', 'a.friendship-button', function() {
		
		// Get the data
		var fid = jq(this).attr('id');
		fid = fid.split('-');
		fid = fid[1];
		var nonce = jq(this).attr('href');
		nonce = nonce.split('?_wpnonce=');
		nonce = nonce[1].split('&');
		nonce = nonce[0];
		var thelink = jq(this);
		
		// Give a tooltip
		thelink.children('i').attr( "class", "icon-spinner icon-spin" );

		// Send the AJAX
		jq.post( ajaxurl, {
			action: 'addremove_friend',
			'cookie': bp_get_cookies(),
			'fid': fid,
			'_wpnonce': nonce
		},
		function(response)
		{
			var action = thelink.attr('rel');
			var parentdiv = thelink.parent();

			// Display the new cancel button
			if ( action == 'add' ) {
				jq(parentdiv).fadeOut(200,
					function() {
						thelink.removeClass('add_friend').addClass('pending_friend');
						parentdiv.fadeIn(200).html(response);
						parentdiv.children('a').addClass('friendship-button button').prepend('<i class="icon-remove"></i>');
					} );

			// Display the new add button
			} else if ( action == 'remove' ) {
				jq(parentdiv).fadeOut(200,
					function() {
						thelink.removeClass('remove_friend').addClass('add');
						parentdiv.fadeIn(200).html(response);
						parentdiv.children('a').addClass('friendship-button button').prepend('<i class="icon-male"></i>');
					} );
			}			
		});
		
		// Prevent Default
		return false;
	} );

	/** Group Join / Leave Buttons **************************************/

	jq('#groups-dir-list').on('click', 'a.group-button', function() {
		
		// Get the group data
		var gid = jq(this).parents( 'li.group' ).attr('id');
		gid = gid.split('-');
		gid = gid[1];
		var nonce = jq(this).attr('href');
		nonce = nonce.split('?_wpnonce=');
		nonce = nonce[1].split('&');
		nonce = nonce[0];
		var thelink = jq(this);
		var parentdiv = thelink.parent();

		// Display a tooltip
		thelink.children('i').attr( "class", "icon-spinner icon-spin" );

		// Send the AJAX		
		jq.post( ajaxurl, {
			action: 'joinleave_group',
			'cookie': bp_get_cookies(),
			'gid': gid,
			'_wpnonce': nonce
		},
		function(response)
		{
			// If it's not the groups directory, actually follow the link
			if ( !jq('body.directory').length )
				location.href = location.href;
			
			// Otherwise, display the AJAXed button
			else {
			
				// Determine the action
				var action = thelink.hasClass('join-group') ? 'join' : 'leave';
				jq(parentdiv).fadeOut(200, function() {

					// Restore the tooptip
					parentdiv.fadeIn(200).html(response);
					if ( 'join' == action ) {
						parentdiv.children('a').addClass('group-button button').prepend('<i class="icon-remove"></i>');
					} else {
						parentdiv.children('a').addClass('group-button button').prepend('<i class="icon-group"></i>');
					}
				});
			}
		});
		
		// Prevent default
		return false;
	} );

	/** Button disabling ************************************************/
	jq( 'a.confirm').click( function() {
		if ( confirm( 'Are you sure?' ) )
			return true; 
		else 
			return false;
	});
	
	jq('.pending').click(function() {
		return false;
	});
	
	/** Notifications ******************************************/	
	jq('#notifications-actions a').click( function(){
	
		// Add a tooltip
		jq(this).html('<i class="icon-spinner icon-spin"></i>Deleting');
		
		// Get the context
		var context = jq(this).attr('id');
		
		// Get data
		var userid = jq(this).data('id');
		
		// Configure action by context
		if ( 'mark_as_read' == context ) {
			action	= 'apoc_mark_notifications_read';
			success	= 'All notifications marked as read!';
		} else if ( 'delete_all_notifications' == context ) {
			action = 'apoc_delete_all_notifications';	
			success	= 'All notifications deleted!';			
		}
		
		// Submit the POST AJAX
		jq.post( ajaxurl, { 
				'action'	: action,
				'id'			: userid
				},
			function(response){
				if ( response == 1 ) {
					jq('div#notifications-dir-list').slideUp().html('<div class="updated">' + success + '</div>').slideDown();
				}
			});
	
		// Prevent default action
		return false;
	});

	/** Private Messaging ******************************************/

	/** Message search*/
	jq('form#search-message-form').submit( function() {
		if ( jq(this).hasClass('no-ajax') )
			return;

		var target = jq('input#messages_search');

		//var css_id = jq('.item-list-tabs li.selected').attr('id').split( '-' );
		var object = 'messages';
		bp_filter_request( object, jq.cookie('bp-' + object + '-filter'), jq.cookie('bp-' + object + '-scope') , 'div.' + object, target.val(), 1, jq.cookie('bp-' + object + '-extras') );
		return false;
	});
	
	/* AJAX send reply functionality */
	jq("button#send_reply_button").click( function() {
		
		// Get the data
		var order 	= jq('#messages_order').val() || 'ASC';
		var button 	= jq(this);
		
		// Tooltip
		button.attr('disabled','disabled').children('i').attr( "class", "icon-spinner icon-spin" );
		
		// Save content from TinyMCE into the hidden form textarea
		tinyMCE.triggerSave();
	
		// Send the AJAX using my own handler
		jq.post( ajaxurl, {
			action		: 'apoc_private_message_reply',
			'cookie'	: bp_get_cookies(),
			'_wpnonce'	: jq("input#send_message_nonce").val(),

			'content'	: jq("#message_content").val(),
			'send_to'	: jq("input#send_to").val(),
			'subject'	: jq("input#subject").val(),
			'thread_id'	: jq("input#thread_id").val()
		}, 	function(response) {
			
			// Process errors
			if ( response[0] + response[1] == "-1" ) {
				jq('form#send-reply').prepend( response.substr( 2, response.length ) );
			} else {
				
				// Clear errors
				jq('form#send-reply div#message').remove();

				// Append the new message
				if ( 'ASC' == order ) {
					jq('ol#message-thread').append( response );
				} else {
					jq('ol#message-thread').prepend( response );
					jq(window).scrollTop();
				}

				// Slide down the new message
				jq(".new-message").hide().slideDown( 200, function() {
					jq('.new-message').removeClass('new-message');
				});
				
				// Clear the editor
				tinyMCE.activeEditor.setContent('');
				tinyMCE.triggerSave();
			}
			
			// Remove the tooltip
			button.removeAttr('disabled').children('i').attr( "class", "icon-envelope-alt" );
		});

		// Prevent default
		return false;
	});

	/* Marking private messages as read and unread */
	jq("a#mark_as_read, a#mark_as_unread").click(function() {

		// Get the checkboxes
		var checkboxes_tosend = '';
		var checkboxes = jq("#message-threads li input[type='checkbox']");

		if ( 'mark_as_unread' == jq(this).attr('id') ) {
			var currentClass = 'read';
			var newClass = 'unread';
			var unreadCount = 1;
			var inboxCount = 0;
			var unreadCountDisplay = 'inline';
			var action = 'messages_markunread';
		} else {
			var currentClass = 'unread'
			var newClass = 'read';
			var unreadCount = 0;
			var inboxCount = 1;
			var unreadCountDisplay = 'none';
			var action = 'messages_markread';
		}

		// Loop through checkboxes, doing each message
		checkboxes.each( function(i) {
			if(jq(this).is(':checked')) {
				if ( jq('li#m-' + jq(this).attr('value')).hasClass(currentClass) ) {
					
					// Mark the message
					checkboxes_tosend += jq(this).attr('value');
					jq('li#m-' + jq(this).attr('value')).removeClass(currentClass);
					jq('li#m-' + jq(this).attr('value')).addClass(newClass);
					
					// Display the new unread count
					if ( unreadCount > 0 ) {
						jq('li#m-' + jq(this).attr('value') + ' span.unread-count').hide().html( '&rarr; ' + unreadCount + ' Unread Message' ).fadeIn();
					} else {
						jq('li#m-' + jq(this).attr('value') + ' span.unread-count').fadeOut();
					}
					
					// Count the total number of unread messages and increment the tab
					var inboxcount = jq('li.unread').length;
					jq('a#user-messages span').html( inboxcount );
					if ( inboxcount > 0 ) {
						jq('li#inbox-personal-li span.activity-count').html( '+' + inboxcount ).fadeIn();
					} else {
						jq('li#inbox-personal-li span.activity-count').fadeOut();
					}
					
					// If there are more to do, comma separate them
					if ( i != checkboxes.length - 1 ) {
						checkboxes_tosend += ','
					}
				}
			}
		});
		
		// Submit the AJAX
		jq.post( ajaxurl, {
			action: action,
			'thread_ids': checkboxes_tosend
		});
		
		// Prevent Default
		return false;
	});

	/* Selecting unread and read messages in inbox */
	jq("select#message-type-select").change( function() {
			
			// Get the data
			var selection = jq("select#message-type-select").val();
			var checkboxes = jq("li.message input[type='checkbox']");
			
			// Clear existing checks
			checkboxes.each( function(i) {
				checkboxes[i].checked = "";
			});

			// Switch read/unread
			switch(selection) {
				case 'unread':
					var checkboxes = jq("li.unread input[type='checkbox']");
					break;
				case 'read':
					var checkboxes = jq("li.read input[type='checkbox']");
					break;
			}
			
			// Check the boxes for read/unread
			if ( selection != '' ) {
				checkboxes.each( function(i) {
					checkboxes[i].checked = "checked";
				});
				
			// Otherwise, uncheck everything
			} else {
				checkboxes.each( function(i) {
					checkboxes[i].checked = "";
				});
			}
	} );

	/* Bulk delete messages */
	jq("a#delete_inbox_messages, a#delete_sentbox_messages").click( function() {
		
		// Get the data		
		checkboxes_tosend = '';
		checkboxes = jq("ol#message-threads li input[type='checkbox']");
		jq('div#message').remove();
		
		// Give a tooltip
		jq(this).children('i').attr('class' , 'icon-spinner icon-spin' );
		
		// Loop through checkboxes
		jq(checkboxes).each( function(i) {
			if( jq(this).is(':checked') )
				checkboxes_tosend += jq(this).attr('value') + ',';
		});
		
		// If there are no checkboxes, bail
		if ( '' == checkboxes_tosend ) {
			jq(this).children('i').attr('class' , 'icon-trash' );
			return false;
		}
		
		// Submit the AJAX
		jq.post( ajaxurl, {
			action		: 'messages_delete',
			'thread_ids': checkboxes_tosend
		}, function(response) {
			if ( response[0] + response[1] == "-1" ) {
				jq('#profile-body').prepend( response.substr( 2, response.length ) );
			} else {
				jq('#profile-body').prepend( '<div id="message" class="updated"><p>' + response + '</p></div>' );

				// Visibly fade out the deleted messages
				jq(checkboxes).each( function(i) {
					if( jq(this).is(':checked') )
						jq(this).parents( 'li.message' ).fadeOut(150).remove();
				});
			}

			// Show the outcome message
			jq('div#message').hide().slideDown(150);
			
			// Count the total number of unread messages and increment the tab
			var inboxcount = jq('li.unread').length;
			jq('a#user-messages span').html( inboxcount );
			if ( inboxcount > 0 ) {
				jq('li#inbox-personal-li span.activity-count').html( '+' + inboxcount ).fadeIn();
			} else {
				jq('li#inbox-personal-li span.activity-count').fadeOut();
			}
			
			// If we deleted everything, hide the parent OL too
			var parentlist = jq('ol#message-threads');
			if ( jq('ol#message-threads li').length == 0 ) {
				jq( 'div#private-messages' ).html( '<p class="no-results"><i class="icon-inbox"></i>Your inbox is empty!</p>' ).hide().fadeIn();
			}
				
			// Restore the button icon
			jq("a#delete_inbox_messages, a#delete_sentbox_messages").children('i').attr('class' , 'icon-trash' );
		});
		
		// Prevent default
		return false;
	});
	
	/* Delete single message */
	jq("a.delete-single-message").click( function(event) {

		// Prevent default action
		event.preventDefault();
	
		// Get the data
		var button 	= jq(this);
		var message	= button.parents( 'li.message' );
		var mid		= message.attr('id')
		mid			= mid.split('-');
		mid			= mid[1];
		
		if ( confirm( 'Delete this message?' ) ) {
	
			// Show a tooltip
			button.children('i').attr('class','icon-spinner icon-spin');
			
			// Submit the AJAX
			jq.post( ajaxurl, {
				action		: 'messages_delete',
				'thread_ids': mid }, 
				function(response) {
					if ( response[0] + response[1] == "-1" ) {
						jq('#profile-body').prepend( response.substr( 2, response.length ) );
					} else {
						jq('#profile-body').prepend( '<div id="message" class="updated"><p>' + response + '</p></div>' );

						// Visibly fade out the deleted messages
						message.fadeOut(150).remove();				
					}
					
					// Show the outcome message
					jq('div#message').hide().slideDown(150);
					
					// Count the total number of unread messages and increment the tab
					var inboxcount = jq('li.unread').length;
					jq('a#user-messages span').html( inboxcount );
					if ( inboxcount > 0 ) {
						jq('li#inbox-personal-li span.activity-count').html( '+' + inboxcount ).fadeIn();
					} else {
						jq('li#inbox-personal-li span.activity-count').fadeOut();
					}
					
					// If we deleted everything, hide the parent OL too
					var parentlist = jq('ol#message-threads');
					if ( jq('ol#message-threads li').length == 0 ) {
						jq( 'div#private-messages' ).html( '<p class="no-results"><i class="icon-inbox"></i>Your inbox is empty!</p>' ).hide().fadeIn();
					}
				}
			);
		}
	});
	
	/* Compose New Message */
	jq("form#send_message_form").submit( function() {
		var button = jq('button#send');
		button.children('i').attr( "class", "icon-spinner icon-spin" );
	});

	/* Close site wide notices in the sidebar 
	jq("a#close-notice").click( function() {
		jq(this).addClass('loading');
		jq('div#sidebar div.error').remove();

		jq.post( ajaxurl, {
			action: 'messages_close_notice',
			'notice_id': jq('.notice').attr('rel').substr( 2, jq('.notice').attr('rel').length )
		},
		function(response) {
			jq("a#close-notice").removeClass('loading');

			if ( response[0] + response[1] == '-1' ) {
				jq('.notice').prepend( response.substr( 2, response.length ) );
				jq( 'div#sidebar div.error').hide().fadeIn( 200 );
			} else {
				jq('.notice').slideUp( 100 );
			}
		});
		return false;
	}); */

	/* Toolbar & wp_list_pages Javascript IE6 hover class 
	jq("#wp-admin-bar ul.main-nav li, #nav li").mouseover( function() {
		jq(this).addClass('sfhover');
	});

	jq("#wp-admin-bar ul.main-nav li, #nav li").mouseout( function() {
		jq(this).removeClass('sfhover');
	}); */

	/* Clear BP cookies on logout */
	jq('a.logout').click( function() {
		jq.cookie('bp-activity-scope', null, {
			path: '/'
		});
		jq.cookie('bp-activity-filter', null, {
			path: '/'
		});
		jq.cookie('bp-activity-oldestpage', null, {
			path: '/'
		});

		var objects = [ 'members', 'groups', 'blogs', 'forums' ];
		jq(objects).each( function(i) {
			jq.cookie('bp-' + objects[i] + '-scope', null, {
				path: '/'
			} );
			jq.cookie('bp-' + objects[i] + '-filter', null, {
				path: '/'
			} );
			jq.cookie('bp-' + objects[i] + '-extras', null, {
				path: '/'
			} );
		});
	});
});

/* Setup activity scope and filter based on the current cookie settings. */
function bp_init_activity() {
	/* Reset the page */
	jq.cookie( 'bp-activity-oldestpage', 1, {
		path: '/'
	} );

	if ( null != jq.cookie('bp-activity-filter') && jq('#activity-filter-select').length )
		jq('#activity-filter-select select option[value="' + jq.cookie('bp-activity-filter') + '"]').prop( 'selected', true );

	/* Activity Tab Set */
	if ( null != jq.cookie('bp-activity-scope') && jq('.activity-type-tabs').length ) {
		jq('.activity-type-tabs li').each( function() {
			jq(this).removeClass('selected');
		});
		jq('li#activity-' + jq.cookie('bp-activity-scope') + ', .activity-type-tabs li.current').addClass('selected');
	}
}

/* Setup object scope and filter based on the current cookie settings for the object. */
function bp_init_objects(objects) {
	jq(objects).each( function(i) {
		if ( null != jq.cookie('bp-' + objects[i] + '-filter') && jq('div#' + objects[i] + '-order-select select').length )
			jq('div#' + objects[i] + '-order-select select option[value="' + jq.cookie('bp-' + objects[i] + '-filter') + '"]').prop( 'selected', true );

		if ( null != jq.cookie('bp-' + objects[i] + '-scope') && jq('div.' + objects[i]).length ) {
			jq('.dir-list-tabs li').each( function() {
				jq(this).removeClass('selected');
			});
			jq('.dir-list-tabs li#' + objects[i] + '-' + jq.cookie('bp-' + objects[i] + '-scope') + ', div.dir-list-tabs#object-nav li.current').addClass('selected');
		}
	});
}

/* Filter the current content list (groups/members/blogs/topics) */
function bp_filter_request( object, filter, scope, target, search_terms, page, extras ) {
	if ( 'activity' == object )
		return false;

	if ( jq.query.get('s') && !search_terms )
		search_terms = jq.query.get('s');

	if ( null == scope )
		scope = 'all';

	/* Save the settings we want to remain persistent to a cookie */
	jq.cookie( 'bp-' + object + '-scope', scope, {
		path: '/'
	} );
	jq.cookie( 'bp-' + object + '-filter', filter, {
		path: '/'
	} );
	jq.cookie( 'bp-' + object + '-extras', extras, {
		path: '/'
	} );

	/* Set the correct selected nav and filter */
	jq('.dir-list-tabs li').each( function() {
		jq(this).removeClass('selected');
	});
	jq('.dir-list-tabs li#' + object + '-' + scope + ', .dir-list-tabs#object-nav li.current').addClass('selected');
	jq('.dir-list-tabs li.selected').addClass('loading');
	jq('.dir-list-tabs select option[value="' + filter + '"]').prop( 'selected', true );

	if ( 'friends' == object )
		object = 'members';

	if ( bp_ajax_request )
		bp_ajax_request.abort();

	bp_ajax_request = jq.post( ajaxurl, {
		action: object + '_filter',
		'cookie': bp_get_cookies(),
		'object': object,
		'filter': filter,
		'search_terms': search_terms,
		'scope': scope,
		'page': page,
		'extras': extras
	},
	function(response)
	{
		jq(target).fadeOut( 100, function() {
			jq(this).html(response);
			jq(this).fadeIn(100);
		});
		jq('.dir-list-tabs li.selected').removeClass('loading');
	});
}

/* Activity Loop Requesting */
function bp_activity_request(scope, filter) {
	/* Save the type and filter to a session cookie */
	jq.cookie( 'bp-activity-scope', scope, {
		path: '/'
	} );
	jq.cookie( 'bp-activity-filter', filter, {
		path: '/'
	} );
	jq.cookie( 'bp-activity-oldestpage', 1, {
		path: '/'
	} );

	/* Remove selected and loading classes from tabs */
	jq('.activity-type-tabs li').each( function() {
		jq(this).removeClass('selected loading');
	});
	/* Set the correct selected nav and filter */
	jq('li#activity-' + scope + ', .activity-type-tabs li.current').addClass('selected');
	jq('#object-nav.activity-type-tabs li.selected, div.activity-type-tabs li.selected').addClass('loading');
	jq('#activity-filter-select select option[value="' + filter + '"]').prop( 'selected', true );

	/* Reload the activity stream based on the selection */
	jq('.widget_bp_activity_widget h2 span.ajax-loader').show();

	if ( bp_ajax_request )
		bp_ajax_request.abort();

	bp_ajax_request = jq.post( ajaxurl, {
		action: 'activity_widget_filter',
		'cookie': bp_get_cookies(),
		'_wpnonce_activity_filter': jq("input#_wpnonce_activity_filter").val(),
		'scope': scope,
		'filter': filter
	},
	function(response)
	{
		jq('.widget_bp_activity_widget h2 span.ajax-loader').hide();

		jq('div.activity').fadeOut( 100, function() {
			jq(this).html(response.contents);
			jq(this).fadeIn(100);

			/* Selectively hide comments */
			bp_dtheme_hide_comments();
		});

		/* Update the feed link */
		if ( null != response.feed_url )
			jq('.directory #subnav li.feed a, .home-page #subnav li.feed a').attr('href', response.feed_url);

		jq('.activity-type-tabs li.selected').removeClass('loading');

	}, 'json' );
}

/* Hide long lists of activity comments, only show the latest five root comments. */
function bp_dtheme_hide_comments() {
	var comments_divs = jq('div.activity-comments');

	if ( !comments_divs.length )
		return false;

	comments_divs.each( function() {
		if ( jq(this).children('ul').children('li').length < 5 ) return;

		var comments_div = jq(this);
		var parent_li = comments_div.parents('ul#activity-stream > li');
		var comment_lis = jq(this).children('ul').children('li');
		var comment_count = ' ';

		if ( jq('li#' + parent_li.attr('id') + ' a.acomment-reply span').length )
			var comment_count = jq('li#' + parent_li.attr('id') + ' a.acomment-reply span').html();
		
		// Show the latest 5 root comments
		comment_lis.each( function(i) {
			if ( i < comment_lis.length - 5 ) {
				jq(this).addClass('hidden');
				jq(this).toggle();
				if ( !i ) {
					console.log(jq(this))
					jq(this).before( '<li class="show-all"><a class="button" href="#' + parent_li.attr('id') + '/show-all/" title="Show Older Comments">Show Older Comments</a></li>' );
				}
			}
		});

	});
}

/* Helper Functions */

function checkAll() {
	var checkboxes = document.getElementsByTagName("input");
	for(var i=0; i<checkboxes.length; i++) {
		if(checkboxes[i].type == "checkbox") {
			if($("check_all").checked == "") {
				checkboxes[i].checked = "";
			}
			else {
				checkboxes[i].checked = "checked";
			}
		}
	}
}

function clear(container) {
	if( !document.getElementById(container) ) return;

	var container = document.getElementById(container);

	if ( radioButtons = container.getElementsByTagName('INPUT') ) {
		for(var i=0; i<radioButtons.length; i++) {
			radioButtons[i].checked = '';
		}
	}

	if ( options = container.getElementsByTagName('OPTION') ) {
		for(var i=0; i<options.length; i++) {
			options[i].selected = false;
		}
	}

	return;
}

/* Returns a querystring of BP cookies (cookies beginning with 'bp-') */
function bp_get_cookies() {
	// get all cookies and split into an array
	var allCookies   = document.cookie.split(";");

	var bpCookies    = {};
	var cookiePrefix = 'bp-';

	// loop through cookies
	for (var i = 0; i < allCookies.length; i++) {
		var cookie    = allCookies[i];
		var delimiter = cookie.indexOf("=");
		var name      = unescape( cookie.slice(0, delimiter) ).trim();
		var value     = unescape( cookie.slice(delimiter + 1) );

		// if BP cookie, store it
		if ( name.indexOf(cookiePrefix) == 0 ) {
			bpCookies[name] = value;
		}
	}

	// returns BP cookies as querystring
	return encodeURIComponent( jq.param(bpCookies) );
}

/* ScrollTo plugin - just inline and minified */
;(function($){var h=$.scrollTo=function(a,b,c){$(window).scrollTo(a,b,c)};h.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1,limit:true};h.window=function(a){return $(window)._scrollable()};$.fn._scrollable=function(){return this.map(function(){var a=this,isWin=!a.nodeName||$.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!isWin)return a;var b=(a.contentWindow||a).document||a.ownerDocument||a;return/webkit/i.test(navigator.userAgent)||b.compatMode=='BackCompat'?b.body:b.documentElement})};$.fn.scrollTo=function(e,f,g){if(typeof f=='object'){g=f;f=0}if(typeof g=='function')g={onAfter:g};if(e=='max')e=9e9;g=$.extend({},h.defaults,g);f=f||g.duration;g.queue=g.queue&&g.axis.length>1;if(g.queue)f/=2;g.offset=both(g.offset);g.over=both(g.over);return this._scrollable().each(function(){if(e==null)return;var d=this,$elem=$(d),targ=e,toff,attr={},win=$elem.is('html,body');switch(typeof targ){case'number':case'string':if(/^([+-]=?)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=$(targ,this);if(!targ.length)return;case'object':if(targ.is||targ.style)toff=(targ=$(targ)).offset()}$.each(g.axis.split(''),function(i,a){var b=a=='x'?'Left':'Top',pos=b.toLowerCase(),key='scroll'+b,old=d[key],max=h.max(d,a);if(toff){attr[key]=toff[pos]+(win?0:old-$elem.offset()[pos]);if(g.margin){attr[key]-=parseInt(targ.css('margin'+b))||0;attr[key]-=parseInt(targ.css('border'+b+'Width'))||0}attr[key]+=g.offset[pos]||0;if(g.over[pos])attr[key]+=targ[a=='x'?'width':'height']()*g.over[pos]}else{var c=targ[pos];attr[key]=c.slice&&c.slice(-1)=='%'?parseFloat(c)/100*max:c}if(g.limit&&/^\d+$/.test(attr[key]))attr[key]=attr[key]<=0?0:Math.min(attr[key],max);if(!i&&g.queue){if(old!=attr[key])animate(g.onAfterFirst);delete attr[key]}});animate(g.onAfter);function animate(a){$elem.animate(attr,f,g.easing,a&&function(){a.call(this,e,g)})}}).end()};h.max=function(a,b){var c=b=='x'?'Width':'Height',scroll='scroll'+c;if(!$(a).is('html,body'))return a[scroll]-$(a)[c.toLowerCase()]();var d='client'+c,html=a.ownerDocument.documentElement,body=a.ownerDocument.body;return Math.max(html[scroll],body[scroll])-Math.min(html[d],body[d])};function both(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);

/* jQuery Easing Plugin, v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/ */
jQuery.easing.jswing=jQuery.easing.swing;jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(e,f,a,h,g){return jQuery.easing[jQuery.easing.def](e,f,a,h,g)},easeInQuad:function(e,f,a,h,g){return h*(f/=g)*f+a},easeOutQuad:function(e,f,a,h,g){return -h*(f/=g)*(f-2)+a},easeInOutQuad:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f+a}return -h/2*((--f)*(f-2)-1)+a},easeInCubic:function(e,f,a,h,g){return h*(f/=g)*f*f+a},easeOutCubic:function(e,f,a,h,g){return h*((f=f/g-1)*f*f+1)+a},easeInOutCubic:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f+a}return h/2*((f-=2)*f*f+2)+a},easeInQuart:function(e,f,a,h,g){return h*(f/=g)*f*f*f+a},easeOutQuart:function(e,f,a,h,g){return -h*((f=f/g-1)*f*f*f-1)+a},easeInOutQuart:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f+a}return -h/2*((f-=2)*f*f*f-2)+a},easeInQuint:function(e,f,a,h,g){return h*(f/=g)*f*f*f*f+a},easeOutQuint:function(e,f,a,h,g){return h*((f=f/g-1)*f*f*f*f+1)+a},easeInOutQuint:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f*f+a}return h/2*((f-=2)*f*f*f*f+2)+a},easeInSine:function(e,f,a,h,g){return -h*Math.cos(f/g*(Math.PI/2))+h+a},easeOutSine:function(e,f,a,h,g){return h*Math.sin(f/g*(Math.PI/2))+a},easeInOutSine:function(e,f,a,h,g){return -h/2*(Math.cos(Math.PI*f/g)-1)+a},easeInExpo:function(e,f,a,h,g){return(f==0)?a:h*Math.pow(2,10*(f/g-1))+a},easeOutExpo:function(e,f,a,h,g){return(f==g)?a+h:h*(-Math.pow(2,-10*f/g)+1)+a},easeInOutExpo:function(e,f,a,h,g){if(f==0){return a}if(f==g){return a+h}if((f/=g/2)<1){return h/2*Math.pow(2,10*(f-1))+a}return h/2*(-Math.pow(2,-10*--f)+2)+a},easeInCirc:function(e,f,a,h,g){return -h*(Math.sqrt(1-(f/=g)*f)-1)+a},easeOutCirc:function(e,f,a,h,g){return h*Math.sqrt(1-(f=f/g-1)*f)+a},easeInOutCirc:function(e,f,a,h,g){if((f/=g/2)<1){return -h/2*(Math.sqrt(1-f*f)-1)+a}return h/2*(Math.sqrt(1-(f-=2)*f)+1)+a},easeInElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return -(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e},easeOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return g*Math.pow(2,-10*h)*Math.sin((h*k-i)*(2*Math.PI)/j)+l+e},easeInOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k/2)==2){return e+l}if(!j){j=k*(0.3*1.5)}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}if(h<1){return -0.5*(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e}return g*Math.pow(2,-10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j)*0.5+l+e},easeInBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*(f/=h)*f*((g+1)*f-g)+a},easeOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*((f=f/h-1)*f*((g+1)*f+g)+1)+a},easeInOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}if((f/=h/2)<1){return i/2*(f*f*(((g*=(1.525))+1)*f-g))+a}return i/2*((f-=2)*f*(((g*=(1.525))+1)*f+g)+2)+a},easeInBounce:function(e,f,a,h,g){return h-jQuery.easing.easeOutBounce(e,g-f,0,h,g)+a},easeOutBounce:function(e,f,a,h,g){if((f/=g)<(1/2.75)){return h*(7.5625*f*f)+a}else{if(f<(2/2.75)){return h*(7.5625*(f-=(1.5/2.75))*f+0.75)+a}else{if(f<(2.5/2.75)){return h*(7.5625*(f-=(2.25/2.75))*f+0.9375)+a}else{return h*(7.5625*(f-=(2.625/2.75))*f+0.984375)+a}}}},easeInOutBounce:function(e,f,a,h,g){if(f<g/2){return jQuery.easing.easeInBounce(e,f*2,0,h,g)*0.5+a}return jQuery.easing.easeOutBounce(e,f*2-g,0,h,g)*0.5+h*0.5+a}});

/* jQuery Cookie plugin */
jQuery.cookie=function(name,value,options){if(typeof value!='undefined'){options=options||{};if(value===null){value='';options.expires=-1;}var expires='';if(options.expires&&(typeof options.expires=='number'||options.expires.toUTCString)){var date;if(typeof options.expires=='number'){date=new Date();date.setTime(date.getTime()+(options.expires*24*60*60*1000));}else{date=options.expires;}expires='; expires='+date.toUTCString();}var path=options.path?'; path='+(options.path):'';var domain=options.domain?'; domain='+(options.domain):'';var secure=options.secure?'; secure':'';document.cookie=[name,'=',encodeURIComponent(value),expires,path,domain,secure].join('');}else{var cookieValue=null;if(document.cookie&&document.cookie!=''){var cookies=document.cookie.split(';');for(var i=0;i<cookies.length;i++){var cookie=jQuery.trim(cookies[i]);if(cookie.substring(0,name.length+1)==(name+'=')){cookieValue=decodeURIComponent(cookie.substring(name.length+1));break;}}}return cookieValue;}};

/* jQuery querystring plugin */
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('M 6(A){4 $11=A.11||\'&\';4 $V=A.V===r?r:j;4 $1p=A.1p===r?\'\':\'[]\';4 $13=A.13===r?r:j;4 $D=$13?A.D===j?"#":"?":"";4 $15=A.15===r?r:j;v.1o=M 6(){4 f=6(o,t){8 o!=1v&&o!==x&&(!!t?o.1t==t:j)};4 14=6(1m){4 m,1l=/\\[([^[]*)\\]/g,T=/^([^[]+)(\\[.*\\])?$/.1r(1m),k=T[1],e=[];19(m=1l.1r(T[2]))e.u(m[1]);8[k,e]};4 w=6(3,e,7){4 o,y=e.1b();b(I 3!=\'X\')3=x;b(y===""){b(!3)3=[];b(f(3,L)){3.u(e.h==0?7:w(x,e.z(0),7))}n b(f(3,1a)){4 i=0;19(3[i++]!=x);3[--i]=e.h==0?7:w(3[i],e.z(0),7)}n{3=[];3.u(e.h==0?7:w(x,e.z(0),7))}}n b(y&&y.T(/^\\s*[0-9]+\\s*$/)){4 H=1c(y,10);b(!3)3=[];3[H]=e.h==0?7:w(3[H],e.z(0),7)}n b(y){4 H=y.B(/^\\s*|\\s*$/g,"");b(!3)3={};b(f(3,L)){4 18={};1w(4 i=0;i<3.h;++i){18[i]=3[i]}3=18}3[H]=e.h==0?7:w(3[H],e.z(0),7)}n{8 7}8 3};4 C=6(a){4 p=d;p.l={};b(a.C){v.J(a.Z(),6(5,c){p.O(5,c)})}n{v.J(1u,6(){4 q=""+d;q=q.B(/^[?#]/,\'\');q=q.B(/[;&]$/,\'\');b($V)q=q.B(/[+]/g,\' \');v.J(q.Y(/[&;]/),6(){4 5=1e(d.Y(\'=\')[0]||"");4 c=1e(d.Y(\'=\')[1]||"");b(!5)8;b($15){b(/^[+-]?[0-9]+\\.[0-9]*$/.1d(c))c=1A(c);n b(/^[+-]?[0-9]+$/.1d(c))c=1c(c,10)}c=(!c&&c!==0)?j:c;b(c!==r&&c!==j&&I c!=\'1g\')c=c;p.O(5,c)})})}8 p};C.1H={C:j,1G:6(5,1f){4 7=d.Z(5);8 f(7,1f)},1h:6(5){b(!f(5))8 d.l;4 K=14(5),k=K[0],e=K[1];4 3=d.l[k];19(3!=x&&e.h!=0){3=3[e.1b()]}8 I 3==\'1g\'?3:3||""},Z:6(5){4 3=d.1h(5);b(f(3,1a))8 v.1E(j,{},3);n b(f(3,L))8 3.z(0);8 3},O:6(5,c){4 7=!f(c)?x:c;4 K=14(5),k=K[0],e=K[1];4 3=d.l[k];d.l[k]=w(3,e.z(0),7);8 d},w:6(5,c){8 d.N().O(5,c)},1s:6(5){8 d.O(5,x).17()},1z:6(5){8 d.N().1s(5)},1j:6(){4 p=d;v.J(p.l,6(5,7){1y p.l[5]});8 p},1F:6(Q){4 D=Q.B(/^.*?[#](.+?)(?:\\?.+)?$/,"$1");4 S=Q.B(/^.*?[?](.+?)(?:#.+)?$/,"$1");8 M C(Q.h==S.h?\'\':S,Q.h==D.h?\'\':D)},1x:6(){8 d.N().1j()},N:6(){8 M C(d)},17:6(){6 F(G){4 R=I G=="X"?f(G,L)?[]:{}:G;b(I G==\'X\'){6 1k(o,5,7){b(f(o,L))o.u(7);n o[5]=7}v.J(G,6(5,7){b(!f(7))8 j;1k(R,5,F(7))})}8 R}d.l=F(d.l);8 d},1B:6(){8 d.N().17()},1D:6(){4 i=0,U=[],W=[],p=d;4 16=6(E){E=E+"";b($V)E=E.B(/ /g,"+");8 1C(E)};4 1n=6(1i,5,7){b(!f(7)||7===r)8;4 o=[16(5)];b(7!==j){o.u("=");o.u(16(7))}1i.u(o.P(""))};4 F=6(R,k){4 12=6(5){8!k||k==""?[5].P(""):[k,"[",5,"]"].P("")};v.J(R,6(5,7){b(I 7==\'X\')F(7,12(5));n 1n(W,12(5),7)})};F(d.l);b(W.h>0)U.u($D);U.u(W.P($11));8 U.P("")}};8 M C(1q.S,1q.D)}}(v.1o||{});',62,106,'|||target|var|key|function|value|return|||if|val|this|tokens|is||length||true|base|keys||else||self||false|||push|jQuery|set|null|token|slice|settings|replace|queryObject|hash|str|build|orig|index|typeof|each|parsed|Array|new|copy|SET|join|url|obj|search|match|queryString|spaces|chunks|object|split|get||separator|newKey|prefix|parse|numbers|encode|COMPACT|temp|while|Object|shift|parseInt|test|decodeURIComponent|type|number|GET|arr|EMPTY|add|rx|path|addFields|query|suffix|location|exec|REMOVE|constructor|arguments|undefined|for|empty|delete|remove|parseFloat|compact|encodeURIComponent|toString|extend|load|has|prototype'.split('|'),0,{}));
