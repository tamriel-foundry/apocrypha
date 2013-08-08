/*! Delete Comments */
$(document).ready(function(){
    $("a.delete-comment-link").click( function() {
	
		/* Confirm the user's desire to delete the comment */
		confirmation = confirm("Permanently delete this comment?");
		if(confirmation){
		
			/* Visual tooltip */
			button = $(this);
			button.text('Deleting...');
			
			/* Get the arguments */
			commentid	= $(this).data('id');
			nonce		= $(this).data('nonce');

			/* Submit the POST AJAX */
			$.post( ajaxurl, { 
				'action'	: 'apoc_delete_comment',
				'_wpnonce'	: nonce,
				'commentid' : commentid,
				}, 
				function( resp ){	
					if( resp ){	
						$( 'li#comment-' + commentid + ' div.reply-body' ).slideUp( 'slow', 
							function() { $( 'li#comment-' + commentid ).remove(); }
						);						
					}
				}
			);
		}
	});
});